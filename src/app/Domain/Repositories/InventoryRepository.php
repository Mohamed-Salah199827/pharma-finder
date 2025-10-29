<?php

namespace App\Domain\Repositories;

use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class InventoryRepository
{
    public function upsertMany(int $pharmacyId, array $rows): void
    {
        $now = now();
        $payload = array_map(function ($row) use ($pharmacyId, $now) {
            return array_merge($row, [
                'pharmacy_id' => $pharmacyId,
                'updated_at' => $now,
            ]);
        }, $rows);

        DB::table('inventories')->upsert(
            $payload,
            ['pharmacy_id', 'product_variant_id'], 
            ['price', 'quantity', 'is_available', 'last_stock_update', 'updated_at']
        );
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
