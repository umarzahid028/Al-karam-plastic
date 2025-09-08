<?php

namespace App\Http\Controllers;
use App\Models\RawSupplier;
use App\Models\RawStock;
use Illuminate\Http\Request;

class RawStockController extends Controller
{
    public function index()
    {
        return response()->json(
            RawStock::with(['product', 'rawsupplier', 'user'])->get()

        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'rawpro_id'        => 'required|exists:products,id',   // <-- change here
            'supplier_id'      => 'required|exists:raw_suppliers,id',
            'user_id'          => 'required|exists:users,id',
            'quantity_in'      => 'nullable|numeric',
            'quantity_out'     => 'nullable|numeric',
            'supplier_inv_date'=> 'nullable|date',
        ]);
        

        $stock = RawStock::create($request->all());

        return response()->json($stock->load(['product', 'rawsupplier', 'user']), 201);
    }

    public function show($id)
    {
        return response()->json(
            RawStock::with(['product', 'rawsupplier', 'user'])->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $stock = RawStock::findOrFail($id);
        $stock->update($request->all());

        return response()->json($stock);
    }

    public function destroy($id)
    {
        RawStock::destroy($id);
        return response()->json(null, 204);
    }
}
