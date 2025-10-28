<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Pharmacy;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class LargeSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Manufacturer::factory()->count(200)->create();

        $root = Category::factory()->create(['name' => 'Pain Relief']);
        Category::factory()->count(30)->create(['parent_id' => $root->id]);

        $productCount = 50000;
        $variantsPerProduct = 1;

        for ($i = 0; $i < $productCount; $i += 1000) {
            $batch = Product::factory()->count(1000)->create();
            $batch->each(function ($p) use ($variantsPerProduct) {
                ProductVariant::factory()->count($variantsPerProduct)->create(['product_id' => $p->id]);
            });
        }

        $pharmacyCount = 20000;
        for ($i = 0; $i < $pharmacyCount; $i += 2000) {
            Pharmacy::factory()->count(2000)->create();
        }

        $variantIds = ProductVariant::inRandomOrder()->limit(200000)->pluck('id');
        Pharmacy::query()->inRandomOrder()->limit(5000)->get()
            ->each(function ($ph) use ($variantIds) {
                $pick = $variantIds->random(100)->values();
                $rows = [];
                $now = now();
                foreach ($pick as $vid) {
                    $qty = fake()->numberBetween(0, 50);
                    $rows[] = [
                        'pharmacy_id' => $ph->id,
                        'product_variant_id' => $vid,
                        'quantity' => $qty,
                        'is_available' => $qty > 0,
                        'price' => fake()->randomFloat(2, 5, 500),
                        'last_stock_update' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                DB::table('inventories')->insert($rows);
            });
    }
}
