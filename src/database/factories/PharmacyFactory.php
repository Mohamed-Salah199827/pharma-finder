<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pharmacy>
 */
class PharmacyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Pharmacy ' . strtoupper(fake()->unique()->bothify('??###')),
            'address' => fake()->address(),
            'latitude' => fake()->randomFloat(7, 24.0, 31.0),
            'longitude' => fake()->randomFloat(7, 29.0, 35.0),
        ];
    }
}
