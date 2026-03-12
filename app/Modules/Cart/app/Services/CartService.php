<?php

namespace Modules\Cart\Services;

use Modules\Cart\Models\Cart;
use Modules\CartItem\Models\CartItem;
use Modules\Product\Models\Product;
use Modules\User\Models\User;

class CartService
{
    public function getUserCart(User $user): Cart
    {
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $cart->load('items.product');

        return $cart;
    }

    public function addItem(User $user, int $productId, int $quantity): CartItem
    {
        $cart = $this->getUserCart(user: $user);
        $product = Product::findOrFail($productId);

        $this->checkLimits(cart: $cart, product: $product, quantity: $quantity);

        $item = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        $item->quantity = ($item->quantity ?? 0) + $quantity;
        $item->save();

        return $item;
    }

    public function updateItem(User $user, int $itemId, int $quantity): CartItem
    {
        $item = CartItem::findOrFail($itemId);

        if ($item->cart->user_id !== $user->id) {
            throw new \RuntimeException(message: 'Товар не найден в вашей корзине');
        }

        $product = $item->product;
        $cart = $item->cart;

        $this->checkLimits(cart: $cart, product: $product, quantity: $quantity, currentItem: $item);

        $item->quantity = $quantity;
        $item->save();

        return $item;
    }

    public function deleteItem(User $user, int $itemId): void
    {
        $item = CartItem::findOrFail($itemId);

        if ($item->cart->user_id !== $user->id) {
            throw new \RuntimeException(message: 'Товар не найден');
        }

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
