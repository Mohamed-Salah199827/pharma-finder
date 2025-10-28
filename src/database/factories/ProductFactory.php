<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;

    public function definition(): array
    {
        static $seq = 1; 

        return [
            'name' => ucfirst(implode(' ', fake()->words(2))) . ' ' . $seq++,
            'manufacturer_id' => Manufacturer::inRandomOrder()->value('id') ?? Manufacturer::factory(),
            'category_id' => Category::inRandomOrder()->value('id') ?? Category::factory(),
        ];
    }
}
