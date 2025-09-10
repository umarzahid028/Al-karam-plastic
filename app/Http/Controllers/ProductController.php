<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\RawStock;
use App\Models\RawStockLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // Web view
    public function index() {
        // 10 products per page
        $products = Product::paginate(5);
        return view('products.index', compact('products'));
    }

    public function indesx() {
        // 10 products per page
        $products = Product::paginate(5);
        return view('products.update-index', compact('products'));
    }

    public function create() {
        return view('products.create');
    }
    
    // Store product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_code' => 'required|unique:products',
            'product_name' => 'required',
            'sale_price'   => 'required|numeric',
            'cost_price'   => 'required|numeric',
        ]);

        $product = Product::create([
            'product_code' => $request->product_code,
            'product_name' => $request->product_name,
            'product_group'=> $request->product_group,
            'unit'         => $request->unit,
            'sale_price'   => $request->sale_price,
            'cost_price'   => $request->cost_price,
            'size'         => $request->size,
            'packing_sqr'  => $request->packing_sqr,
            'pieces_per_bundle' => $request->pieces_per_bundle ?? 0,
            'weight'       => $request->weight,
        ]);

        if ($request->opening_qty > 0) {
            RawStock::create([
                'rawpro_id'    => $product->id,
                'quantity_in'  => $request->opening_qty,
                'quantity_out' => 0,
            ]);

            RawStockLog::create([
                'rawpro_id'    => $product->id,
                'trans_type'   => 'IN',
                'qty'          => $request->opening_qty,
                'price'        => $request->opening_price ?? 0,
                'total_amount' => ($request->opening_qty * ($request->opening_price ?? 0)),
                'remarks'      => $request->opening_remarks ?? 'Opening Stock',
                'user_id'      => Auth::id() ?? 1,
                'trans_date'   => now(),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Product added successfully!']);
    }

    // JSON list for table
    public function list()
    {
        $products = Product::select(
            'products.id',
            'products.product_code',
            'products.product_name',
            'products.product_group',
            'products.unit',
            'products.sale_price',
            'products.cost_price',
            DB::raw('COALESCE(SUM(raw_stocks.quantity_in - raw_stocks.quantity_out),0) as current_stock')
        )
        ->leftJoin('raw_stocks', 'products.id', '=', 'raw_stocks.rawpro_id')
        ->groupBy(
            'products.id',
            'products.product_code',
            'products.product_name',
            'products.product_group',
            'products.unit',
            'products.sale_price',
            'products.cost_price'
        )
        ->get();

        return response()->json($products);
    }
    // Update product
    public function show($id)
{
    $product = Product::with('rawStocks')->findOrFail($id);
    return view('products.update', compact('product'));
}


public function update(Request $request, $id)
{
    $validated = $request->validate([
        'product_code' => 'required|unique:products,product_code,' . $id,
        'product_name' => 'required',
        'sale_price'   => 'required|numeric',
        'cost_price'   => 'required|numeric',
    ]);

    $product = Product::findOrFail($id);

    // Update product details
    $product->update([
        'product_code' => $request->product_code,
        'product_name' => $request->product_name,
        'product_group'=> $request->product_group,
        'unit'         => $request->unit,
        'sale_price'   => $request->sale_price,
        'cost_price'   => $request->cost_price,
        'size'         => $request->size,
        'packing_sqr'  => $request->packing_sqr,
        'pieces_per_bundle' => $request->pieces_per_bundle ?? 0,
        'weight'       => $request->weight,
    ]);

    // Agar opening_qty di gayi hai to update karein
    if ($request->has('opening_qty')) {
        $stock = RawStock::where('rawpro_id', $product->id)->first();

        if ($stock) {
            // Update existing stock
            $stock->update([
                'quantity_in'  => $request->opening_qty,
                'quantity_out' => 0,
            ]);
        } else {
            // Create new stock agar pehle entry nahi thi
            $stock = RawStock::create([
                'rawpro_id'    => $product->id,
                'quantity_in'  => $request->opening_qty,
                'quantity_out' => 0,
            ]);
        }

        // Stock log me entry update ya new add karein
        RawStockLog::updateOrCreate(
            [
                'rawpro_id'  => $product->id,
                'trans_type' => 'IN',
                'remarks'    => 'Opening Stock',
            ],
            [
                'qty'          => $request->opening_qty,
                'price'        => $request->opening_price ?? 0,
                'total_amount' => ($request->opening_qty * ($request->opening_price ?? 0)),
                'user_id'      => Auth::id() ?? 1,
                'trans_date'   => now(),
            ]
        );
    }

    return response()->json(['success' => true, 'message' => 'Product updated successfully!']);
}

}
