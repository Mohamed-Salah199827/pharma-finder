<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'manufacturer' => new ManufacturerResource($this->whenLoaded('manufacturer')),
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
