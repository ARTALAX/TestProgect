<?php

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Enums\UserRole;
use Modules\User\Models\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'role' => $this->faker->randomElement(UserRole::cases()),
            'password' => bcrypt(value: 'password'),
        ];
    }
}
