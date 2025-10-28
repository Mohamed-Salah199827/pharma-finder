<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PharmacyResource;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function index()
    {
        return PharmacyResource::collection(Pharmacy::paginate());
    }
    public function show(Pharmacy $pharmacy)
    {
        return new PharmacyResource($pharmacy);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        return new PharmacyResource(Pharmacy::create($data));
    }

    public function update(Request $r, Pharmacy $pharmacy)
    {
        $data = $r->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        $pharmacy->update($data);
        return new PharmacyResource($pharmacy);
    }

    public function destroy(Pharmacy $pharmacy)
    {
        $pharmacy->delete();
        return response()->json(['message' => 'deleted']);
    }
}
