<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    public function index()
    {
        return response()->json(RawMaterial::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_code'  => 'required|string|unique:raw_materials,material_code',
            'material_name'  => 'required|string|max:255',
            'purchase_price' => 'required|numeric',
            'unit'           => 'required|string|max:50',
            'packing'        => 'nullable|string|max:100',
            'stocks'         => 'nullable|integer',
            'store_id'       => 'required|exists:stores,id',
        ]);

        $rawMaterial = RawMaterial::create($request->all());
        return response()->json($rawMaterial, 201);
    }

    public function show(string $id)
    {
        $rawMaterial = RawMaterial::findOrFail($id);
        return response()->json($rawMaterial);
    }

    public function update(Request $request, string $id)
    {
        $rawMaterial = RawMaterial::findOrFail($id);

        $request->validate([
            'material_code'  => 'sometimes|string|unique:raw_materials,material_code,' . $id,
            'material_name'  => 'sometimes|string|max:255',
            'purchase_price' => 'sometimes|numeric',
            'unit'           => 'sometimes|string|max:50',
            'packing'        => 'nullable|string|max:100',
            'stocks'         => 'nullable|integer',
            'store_id'       => 'sometimes|exists:stores,id',
        ]);

        $rawMaterial->update($request->all());
        return response()->json($rawMaterial);
    }

    public function destroy(string $id)
    {
        RawMaterial::destroy($id);
        return response()->json(null, 204);
    }
}
