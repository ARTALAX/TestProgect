<?php

namespace Modules\Order\Services;

use Modules\Address\Models\Address;
use Modules\Cart\Models\Cart;
use Modules\Order\Models\Order;
use Modules\OrderItem\Models\OrderItem;
use Modules\User\Models\User;

class OrderService
{
    public function createOrder(User $user, array $addressData): Order
    {
        $cart = Cart::where('user_id', $user->id)->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            throw new \RuntimeException(message: 'В корзине нет товаров');
        }

        // Проверка лимитов
        $pizzaCount = $cart->items->sum(fn ($item) => 'pizza' === $item->product->category ? $item->quantity : 0);
        $drinkCount = $cart->items->sum(fn ($item) => 'drink' === $item->product->category ? $item->quantity : 0);

        if ($pizzaCount > 10 || $drinkCount > 20) {
            throw new \RuntimeException(message: 'Превышен лимит товаров');
        }

        $address = Address::create(array_merge($addressData, ['user_id' => $user->id]));

        $order = Order::create([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'total' => $cart->total(),
            'status' => Order::STATUS_CREATED,
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        $cart->items()->delete();

        return $order->load('items.product', 'address');
    }

    public function updateStatus(Order $order, string $status): Order
    {
        $order->status = $status;
        $order->save();

        return $order;
    }

    public function cancelOrder(Order $order, User $user): Order
    {
        if ($order->user_id !== $user->id) {
            throw new \RuntimeException(message: 'Доступ запрещен');
        }

        if (Order::STATUS_COMPLETED === $order->status) {
            throw new \RuntimeException(message: 'Невозможно отменить завершенный заказ');
        }

        $order->status = Order::STATUS_CANCELLED;
        $order->save();

        return $order;
    }
}
