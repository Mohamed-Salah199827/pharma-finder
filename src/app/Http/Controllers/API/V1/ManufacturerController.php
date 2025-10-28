<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ManufacturerResource;
use App\Models\Manufacturer;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    public function index()
    {
        return ManufacturerResource::collection(Manufacturer::paginate());
    }
    public function show(Manufacturer $manufacturer)
    {
        return new ManufacturerResource($manufacturer);
    }

    public function store(Request $r)
    {
        $data = $r->validate(['name' => 'required|string|max:255']);
        return new ManufacturerResource(Manufacturer::create($data));
    }

    public function update(Request $r, Manufacturer $manufacturer)
    {
        $data = $r->validate(['name' => 'required|string|max:255']);
        $manufacturer->update($data);
        return new ManufacturerResource($manufacturer);
    }

    public function destroy(Manufacturer $manufacturer)
    {
        $manufacturer->delete();
        return response()->json(['message' => 'deleted']);
    }
}
