<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   static $password;
        return [
            'account' => fake()->unique()->safeEmail,
            'nickname' => fake()->name,
            'password' => $password ?: $password = bcrypt('123456')
        ];
    }
}
