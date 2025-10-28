<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
     use HasFactory;

    protected $fillable = ['pharmacy_id', 'product_variant_id', 'quantity', 'is_available', 'price', 'last_stock_update'];
    protected $casts = ['is_available' => 'boolean', 'last_stock_update' => 'datetime'];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
