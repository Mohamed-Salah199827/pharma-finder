<?php

namespace App\Domain\Services;

use App\Domain\Repositories\InventoryRepository;
use App\Models\ProductVariant;

class InventoryService
{
    public function __construct(private InventoryRepository $inventories)
    {
    }

    /** items: [['sku'=>'PAN-500-24','price'=>12.5,'quantity'=>20], ...] */
    public function bulkImport(int $pharmacyId, array $items): void
    {
        $skus = collect($items)->pluck('sku')->unique()->values();
        $map = ProductVariant::whereIn('sku', $skus)->pluck('id', 'sku');

        $rows = [];
        $now = now();

        foreach ($items as $i) {
            $vid = $map[$i['sku']] ?? null;
            if (!$vid)
                continue;
            $qty = (int) ($i['quantity'] ?? 0);
            $rows[] = [
                'product_variant_id' => $vid,
                'price' => round($i['price'], 2),
                'quantity' => $qty,
                'is_available' => $qty > 0,
                'last_stock_update' => $now,
                'created_at' => $now,
            ];
        }

        if ($rows) {
            $this->inventories->upsertMany($pharmacyId, $rows);
        }
    }
}
