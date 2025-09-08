<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // GET /api/customers
    public function index()
    {
        return response()->json(Customer::all());
    }

    // POST /api/customers
    public function store(Request $request)
    {
        $request->validate([
            'customer_code'   => 'required|string|unique:customers,customer_code',
            'name'            => 'required|string|max:255',
            'email'           => 'nullable|email|max:255',
            'contact_no'      => 'nullable|string|max:20',
            'address'         => 'nullable|string|max:500',
            'opening_balance' => 'nullable|numeric',
            'status'          => 'nullable|in:active,inactive',
        ]);

        $customer = Customer::create($request->all());
        return response()->json($customer, 201);
    }

    // GET /api/customers/{id}
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }

    // PUT/PATCH /api/customers/{id}
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'customer_code'   => 'sometimes|string|unique:customers,customer_code,'.$id,
            'name'            => 'sometimes|string|max:255',
            'email'           => 'nullable|email|max:255',
            'contact_no'      => 'nullable|string|max:20',
            'address'         => 'nullable|string|max:500',
            'opening_balance' => 'nullable|numeric',
            'status'          => 'nullable|in:active,inactive',
        ]);

        $customer->update($request->all());
        return response()->json($customer);
    }

    // DELETE /api/customers/{id}
    public function destroy($id)
    {
        Customer::destroy($id);
        return response()->json(null, 204);
    }
}
