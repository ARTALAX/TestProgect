<?php

namespace Modules\OrderItem\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Order\Models\Order;
use Modules\OrderItem\Models\OrderItem;
use Modules\Product\Models\Product;

class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        /** @var Order $order */
        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();

        /** @var Product $product */
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();

        return [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $this->faker->numberBetween(int1: 1, int2: 5),
            'price' => $product->price, // фиксируем цену на момент заказа
        ];
    }
}
