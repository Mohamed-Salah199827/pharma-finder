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
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $path = $request->file('file')->store('imports');

        ImportInventoryJob::dispatch($pharmacy->id, $path);

        return response()->json([
            'message' => 'Queued',
            'file' => $path,
        ], 202);
    }
}
