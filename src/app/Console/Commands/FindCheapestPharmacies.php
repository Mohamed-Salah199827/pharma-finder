<?php

namespace App\Console\Commands;

use App\Domain\Repositories\InventoryRepository;
use Illuminate\Console\Command;

class FindCheapestPharmacies extends Command
{
    protected $signature = 'app:find-cheapest-pharmacies {product_variant_id}
        {--min-quantity=0}
        {--output=}';

    protected $description = 'Get top 5 cheapest pharmacies for a variant';

    public function handle(InventoryRepository $repo)
    {
        $id = (int) $this->argument('product_variant_id');
        $min = (int) $this->option('min-quantity');

        $rows = $repo->cheapestForVariant($id, $min, 5);

        $data = [
            'variant_id' => $id,
            'pharmacies' => $rows->map(fn($r) => [
                'pharmacy_id' => $r->pharmacy_id,
                'pharmacy_name' => $r->pharmacy->name,
                'price' => (float) $r->price,
                'quantity' => (int) $r->quantity,
                'last_stock_update' => optional($r->last_stock_update)?->toIso8601String(),
            ])->values(),
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        if ($path = $this->option('output')) {
            file_put_contents($path, $json);
            $this->info("Saved to {$path}");
        } else {
            $this->line($json);
        }

        return self::SUCCESS;
    }
}
