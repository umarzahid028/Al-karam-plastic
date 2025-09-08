<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\RawMaterial;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    // List all purchases
    public function index()
    {
        $purchases = Purchase::with('items.rawMaterial')->get();
        return response()->json($purchases);
    }

    // Create a new purchase with items
    public function store(Request $request)
    {
        $request->validate([
            'purchase_code' => 'required|string|unique:purchases,purchase_code',
            'supplier_id'   => 'required|string',
            'purchase_date' => 'required|date',
            'items'         => 'required|array|min:1',
            'items.*.raw_material_id' => 'required|exists:raw_materials,id',
            'items.*.unit_price'      => 'required|numeric',
            'items.*.quantity'        => 'required|integer',
        ]);

        // Calculate total amount
        $totalAmount = 0;
        foreach ($request->items as $item) {
            $totalAmount += $item['unit_price'] * $item['quantity'];
        }

        // Create purchase
        $purchase = Purchase::create([
            'purchase_code' => $request->purchase_code,
            'supplier_id'   => $request->supplier_id,
            'purchase_date' => $request->purchase_date,
            'total_amount'  => $totalAmount,
            'status'        => $request->status ?? 'pending',
            'description'   => $request->description,
        ]);

        // Create items
        foreach ($request->items as $item) {
            PurchaseItem::create([
                'purchase_id'     => $purchase->id,
                'raw_material_id' => $item['raw_material_id'],
                'unit_price'      => $item['unit_price'],
                'quantity'        => $item['quantity'],
                'total_price'     => $item['unit_price'] * $item['quantity'],
            ]);
        }

        return response()->json($purchase->load('items.rawMaterial'), 201);
    }

    // Show a specific purchase with items
    public function show($id)
    {
        $purchase = Purchase::with('items.rawMaterial')->findOrFail($id);
        return response()->json($purchase);
    }

    // Update purchase (not items in this basic example)
    public function update(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);

        $request->validate([
            'status' => 'sometimes|in:pending,completed,cancelled',
            'description' => 'nullable|string',
        ]);

        $purchase->update($request->all());

        return response()->json($purchase);
    }

    // Delete a purchase (items will be deleted automatically)
    public function destroy($id)
    {
        Purchase::destroy($id);
        return response()->json(null, 204);
    }
}
