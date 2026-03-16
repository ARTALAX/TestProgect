<?php

namespace Modules\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Models\Product;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'price' => $this->faker->randomFloat(nbMaxDecimals: 2, min: 10, max: 1000),
            'weight' => $this->faker->randomFloat(nbMaxDecimals: 2, min: 0.1, max: 10),
            'category' => $this->faker->randomElement(['pizza', 'drink']),
        ];
    }
}
