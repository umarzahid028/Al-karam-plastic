<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // List all products
    public function index()
    {
        return response()->json(Product::all());
    }

    // Create new product
    public function store(Request $request)
    {
        $request->validate([
            'product_code'       => 'required|string|unique:products,product_code',
            'product_name'       => 'required|string',
            'product_group'      => 'required|string',
            'unit'               => 'required|string',
            'sale_price'         => 'required|numeric',
            'cost_price'         => 'required|numeric',
            'size'               => 'nullable|string',
            'packing_sqr'        => 'nullable|string',
            'pieces_per_bundle'  => 'nullable|integer',
            'weight'             => 'nullable|numeric',
        ]);

        $product = Product::create($request->all());

        return response()->json($product, 201);
    }

    // Show single product
    public function show($id)
    {
        return response()->json(Product::findOrFail($id));
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'product_code'       => 'sometimes|string|unique:products,product_code,' . $id,
            'product_name'       => 'sometimes|string',
            'product_group'      => 'sometimes|string',
            'unit'               => 'sometimes|string',
            'sale_price'         => 'sometimes|numeric',
            'cost_price'         => 'sometimes|numeric',
            'size'               => 'nullable|string',
            'packing_sqr'        => 'nullable|string',
            'pieces_per_bundle'  => 'nullable|integer',
            'weight'             => 'nullable|numeric',
        ]);

        $product->update($request->all());

        return response()->json($product);
    }

    // Delete product
    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json(null, 204);
    }
}
