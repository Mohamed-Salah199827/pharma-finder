<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'pharmacy' => new PharmacyResource($this->whenLoaded('pharmacy')),
            'product_variant_id' => $this->product_variant_id,
            'price' => (float) $this->price,
            'quantity' => (int) $this->quantity,
            'is_available' => (bool) $this->is_available,
            'last_stock_update' => optional($this->last_stock_update)?->toIso8601String(),
        ];
    }
}
