<?php

namespace Modules\Order\Services;

use Illuminate\Support\Facades\DB;
use Modules\Address\Models\Address;
use Modules\Cart\Models\Cart;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderStatus;
use Modules\User\Models\User;

class OrderService
{
    public function createOrder(User $user, array $addressData): Order
    {
        return DB::transaction(function () use ($user, $addressData) {
            $cart = Cart::where('id', $user->id)
                ->with('items.product')
                ->lockForUpdate()
                ->first()
            ;

            if (!$cart || $cart->items->isEmpty()) {
                throw new \RuntimeException(message: 'В корзине нет товаров');
            }

            // Теперь создаём заказ в транзакции для безопасности

            $pizzaCount = $cart->items->sum(fn ($item) => 'pizza' === $item->product->category ? $item->quantity : 0);
            $drinkCount = $cart->items->sum(fn ($item) => 'drink' === $item->product->category ? $item->quantity : 0);

            if ($pizzaCount > 10 || $drinkCount > 20) {
                throw new \RuntimeException(message: 'Превышен лимит товаров');
            }

            $address = Address::create([...$addressData, 'user_id' => $user->id]);

            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'total' => $cart->total(),
                'status' => OrderStatus::CREATED,
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            // Очищаем корзину
            $cart->items()->delete();

            // Инвалидация кеша

            return $order->load('items.product', 'address');
        });
    }

    public function updateStatus(Order $order, string $status): Order
    {
        $order->status = $status;
        $order->save();

        return $order;
    }

    public function cancelOrder(Order $order, User $user): Order
    {
        $order->cancel();

        return $order;
    }
}
