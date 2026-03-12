<?php

namespace Modules\CartItem\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Cart\Models\Cart;
use Modules\CartItem\Models\CartItem;
use Modules\Product\Models\Product;

class CartItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = CartItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        /** @var Cart $cart */
        $cart = Cart::inRandomOrder()->first() ?? Cart::factory()->create();

        /** @var Product $product */
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();

        return [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => $this->faker->numberBetween(int1: 1, int2: 5),
        ];
    }
}
