<?php

namespace App\Http\Controllers;

use App\Models\RawSupplier;
use Illuminate\Http\Request;

class RawSupplierController extends Controller
{
    // GET /api/raw-suppliers
    public function index()
    {
        return response()->json(RawSupplier::all());
    }

    // POST /api/raw-suppliers
    public function store(Request $request)
    {
        $request->validate([
            'supplier_code'  => 'required|string|unique:raw_suppliers,supplier_code',
            'company_name'   => 'required|string|max:255',
            'name'            => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'contact_no'     => 'nullable|string|max:20',
            'opening_balance'=> 'nullable|numeric',
            'status'         => 'nullable|in:active,inactive',
        ]);

        $supplier = RawSupplier::create($request->all());
        return response()->json($supplier, 201);
    }

    // GET /api/raw-suppliers/{id}
    public function show($id)
    {
        $supplier = RawSupplier::findOrFail($id);
        return response()->json($supplier);
    }

    // PUT/PATCH /api/raw-suppliers/{id}
    public function update(Request $request, $id)
    {
        $supplier = RawSupplier::findOrFail($id);

        $request->validate([
            'supplier_code'  => 'sometimes|string|unique:raw_suppliers,supplier_code,'.$id,
            'company_name'   => 'sometimes|string|max:255',
            'name'          => 'nullable|name|max:255',
            'email'          => 'nullable|email|max:255',
            'contact_no'     => 'nullable|string|max:20',
            'opening_balance'=> 'nullable|numeric',
            'status'         => 'nullable|in:active,inactive',
        ]);

        $supplier->update($request->all());
        return response()->json($supplier);
    }

    // DELETE /api/raw-suppliers/{id}
    public function destroy($id)
    {
        RawSupplier::destroy($id);
        return response()->json(null, 204);
    }
}
