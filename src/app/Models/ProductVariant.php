<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class ProductVariant extends Model
{
    use Searchable,HasFactory;
    protected $fillable = ['product_id', 'name', 'sku', 'description'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'product_variant_id');
    }

    public function toSearchableArray(): array
    {
        $this->loadMissing('product.manufacturer', 'product.category');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'product' => $this->product?->name,
            'manufacturer' => $this->product?->manufacturer?->name,
            'category' => $this->product?->category?->name,
            'category_path' => $this->product?->category?->path,
            'description' => (string) $this->description,
        ];
    }
}
