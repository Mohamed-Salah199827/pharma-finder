<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Jobs\ImportInventoryJob;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function bulk(Request $request, Pharmacy $pharmacy)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:0',
        ]);

        dispatch(new ImportInventoryJob($pharmacy->id, $data['items']));

        return response()->json(['message' => 'Queued'], 202);
    }
}
