<?php

namespace Modules\Address\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Address\Models\Address;
use Modules\User\Models\User;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'region' => $this->faker->word(),
            'city' => $this->faker->city(),
            'street' => $this->faker->word(),
            'house' => $this->faker->randomDigit(),
            'entrance' => $this->faker->randomDigit(),
            'apartment' => $this->faker->word(),
            'postcode' => $this->faker->randomDigit(),
        ];
    }
}
