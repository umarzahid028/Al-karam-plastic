<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    // Existing JSON listing method
    public function list()
    {
        $stores = Store::where('status','active')->get(['id','store_name']);
        return response()->json($stores);
    }

    // Display all stores
    public function index()
    {
        $stores = Store::all();
        return view('stores.index', compact('stores'));
    }

    // Show form to add a store
    public function create()
    {
        return view('stores.create');
    }

    // Save a new store
    public function store(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'manager_id' => 'nullable|integer',
        ]);

        Store::create([
            'store_name' => $request->store_name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'manager_id' => $request->manager_id,
            'status' => 'active', // default status
        ]);

        return redirect()->route('stores.index')->with('success', 'Store added successfully!');
    }

    // Show form to edit store (only status editable)
    public function edit(Store $store)
    {
        return view('stores.edit', compact('store'));
    }

    // Update store status
    public function update(Request $request, Store $store)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        // Only update status
        $store->status = $request->status;
        $store->save();

        return redirect()->route('stores.index')->with('success', 'Store status updated successfully!');
    }
}
