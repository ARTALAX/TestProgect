<?php

namespace Modules\Order\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Address\Models\Address;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderStatus;
use Modules\User\Models\User;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement(OrderStatus::cases()),
            'address_id' => Address::factory(),
            'total_price' => $this->faker->randomFloat(nbMaxDecimals: 2, min: 10, max: 1000),
        ];
    }
}
