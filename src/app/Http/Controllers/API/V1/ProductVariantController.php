<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductVariantResource;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index()
    {
        return ProductVariantResource::collection(
            ProductVariant::with('product.manufacturer', 'product.category')->paginate()
        );
    }

    public function show(ProductVariant $productVariant)
    {
        return new ProductVariantResource($productVariant->load('product.manufacturer', 'product.category'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:product_variants,sku',
            'description' => 'nullable|string',
        ]);
        $v = ProductVariant::create($data);
        return new ProductVariantResource($v->load('product.manufacturer', 'product.category'));
    }

    public function update(Request $r, ProductVariant $productVariant)
    {
        $data = $r->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'sku' => "required|string|max:255|unique:product_variants,sku,{$productVariant->id}",
            'description' => 'nullable|string',
        ]);
        $productVariant->update($data);
        return new ProductVariantResource($productVariant->load('product.manufacturer', 'product.category'));
    }

    public function destroy(ProductVariant $productVariant)
    {
        $productVariant->delete();
        return response()->json(['message' => 'deleted']);
    }
}
