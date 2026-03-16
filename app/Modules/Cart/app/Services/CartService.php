<?php

namespace Modules\Cart\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Cart\Models\Cart;
use Modules\CartItem\Models\CartItem;
use Modules\Product\Models\Product;
use Modules\User\Models\User;

class CartService
{
    public function getUserCart(User $user): Cart
    {
        return Cache::remember("cart:{$user->id}", 3600, function () use ($user) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
            $cart->load('items.product');

            return $cart;
        });
    }

    public function addItem(User $user, int $productId, int $quantity): CartItem
    {
        return DB::transaction(function () use ($user, $productId, $quantity) {
            $cart = Cart::where('user_id', $user->id)
                ->lockForUpdate()
                ->firstOrCreate(['user_id' => $user->id])
            ;

            $cart->load('items.product');

            $product = Product::findOrFail($productId);

            $this->checkLimits(cart: $cart, product: $product, quantity: $quantity);

            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->lockForUpdate()
                ->first()
            ;

            if (!$item) {
                $item = new CartItem(attributes: [
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => 0,
                ]);
            }

            $item->quantity += $quantity;
            $item->save();

            return $item;
        });
    }

    public function updateItem(CartItem $item, int $quantity): CartItem
    {
        $product = $item->product;
        $cart = $item->cart;

        $this->checkLimits(cart: $cart, product: $product, quantity: $quantity, currentItem: $item);

        $item->quantity = $quantity;
        $item->save();

        return $item;
    }

    public function deleteItem(CartItem $item): void
    {
        $item->delete();
    }

    private function checkLimits(Cart $cart, Product $product, int $quantity, ?CartItem $currentItem = null): void
    {
        $cart->load(relations: 'items.product');

        $pizza = 0;
        $drink = 0;

        foreach ($cart->items as $item) {
            if ($currentItem && $item->id === $currentItem->id) {
                continue;
            }

            if ('pizza' === $item->product->category) {
                $pizza += $item->quantity;
            }

            if ('drink' === $item->product->category) {
                $drink += $item->quantity;
            }
        }

        if ('pizza' === $product->category && $pizza + $quantity > 10) {
            throw new \RuntimeException(message: 'Максимум 10 пицц');
        }

        if ('drink' === $product->category && $drink + $quantity > 20) {
            throw new \RuntimeException(message: 'Максимум 20 напитков');
        }
    }
}
