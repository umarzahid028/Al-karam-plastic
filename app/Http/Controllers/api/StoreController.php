<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        return response()->json(Store::with('manager')->get());
    }

    public function store(Request $request)
    {
        $store = Store::create($request->only([
            'store_name', 'address', 'phone_number', 'manager_id', 'status'
        ]));

        return response()->json($store, 201);
    }

    public function show($id)
    {
        return response()->json(Store::with('manager')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        $store->update($request->only([
            'store_name', 'address', 'phone_number', 'manager_id', 'status'
        ]));

        return response()->json($store, 200);
    }

    public function destroy($id)
    {
        Store::destroy($id);
        return response()->json(null, 204);
    }
}
