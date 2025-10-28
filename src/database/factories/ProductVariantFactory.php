<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = strtoupper(fake()->randomElement(['500mg', '250mg', '100mg'])) . ' ' .
            fake()->randomElement(['Tablets', 'Capsules', 'Syrup']) .
            ' - ' . fake()->numberBetween(10, 40) . ' pack';

        return [
            'product_id' => Product::inRandomOrder()->value('id') ?? Product::factory(),
            'name' => $name,
            'sku' => strtoupper(Str::random(10)),
            'description' => fake()->sentence(8),
        ];
    }
}
