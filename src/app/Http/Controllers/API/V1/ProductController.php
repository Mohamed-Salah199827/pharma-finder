<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $q = Product::with(['manufacturer', 'category'])->paginate();
        return ProductResource::collection($q);
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load(['manufacturer', 'category']));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|max:255',
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'category_id' => 'required|exists:categories,id',
        ]);
        $p = Product::create($data);
        return new ProductResource($p->load(['manufacturer', 'category']));
    }

    public function update(Request $r, Product $product)
    {
        $data = $r->validate([
            'name' => 'required|string|max:255',
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'category_id' => 'required|exists:categories,id',
        ]);
        $product->update($data);
        return new ProductResource($product->load(['manufacturer', 'category']));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'deleted']);
    }
}
