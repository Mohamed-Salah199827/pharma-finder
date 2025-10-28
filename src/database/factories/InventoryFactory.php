<?php

namespace Database\Factories;

use App\Models\Pharmacy;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qty = fake()->numberBetween(0, 100);
        return [
            'pharmacy_id' => Pharmacy::inRandomOrder()->value('id') ?? Pharmacy::factory(),
            'product_variant_id' => ProductVariant::inRandomOrder()->value('id') ?? ProductVariant::factory(),
            'quantity' => $qty,
            'is_available' => $qty > 0,
            'price' => fake()->randomFloat(2, 5, 500),
            'last_stock_update' => now(),
        ];
    }
}
