<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\{
    ManufacturerController, CategoryController, ProductController,
    ProductVariantController, PharmacyController, InventoryController,
    SearchController
};
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::prefix('v1')->group(function () {

    Route::apiResource('manufacturers', ManufacturerController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('product-variants', ProductVariantController::class);
    Route::apiResource('pharmacies', PharmacyController::class);

    Route::post('/pharmacies/{pharmacy}/inventory/bulk', [InventoryController::class, 'bulk']);

    Route::get('/search/variants', [SearchController::class, 'variants']);
});