<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->unique()->word()),
            'parent_id' => null,
            'slug' => fake()->unique()->word(),
            'path' => '/',
        ];
    }
    public function child(Category $parent): static
    {
        return $this->state(fn() => [
            'parent_id' => $parent->id,
        ]);
    }
}
