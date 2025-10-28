<?php

namespace App\Domain\Repositories;

use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class InventoryRepository
{
    public function upsertMany(int $pharmacyId, array $rows): void
    {
        $now = now();
        foreach (array_chunk($rows, 1000) as $chunk) {
            foreach ($chunk as &$r) {
                $r['pharmacy_id'] = $pharmacyId;
                $r['created_at'] = $r['created_at'] ?? $now;
                $r['updated_at'] = $now;
            }
            DB::table('inventories')->upsert(
                $chunk,
                ['pharmacy_id', 'product_variant_id'],
                ['price', 'quantity', 'is_available', 'last_stock_update', 'updated_at']
            );
        }
    }

    public function cheapestForVariant(int $variantId, ?int $minQty = null, int $limit = 5)
    {
        $q = Inventory::with('pharmacy')
            ->where('product_variant_id', $variantId)
            ->where('is_available', true);

        if ($minQty !== null) {
            $q->where('quantity', '>=', $minQty);
        }

        return $q->orderBy('price')->limit($limit)->get();
    }
}
