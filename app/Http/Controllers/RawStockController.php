<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RawStock;
use App\Models\RawStockLog;
use App\Models\Product;
use App\Models\SalesInvoiceItem;

class RawStockController extends Controller
{
    public function index()
    {
        // Load all products and attach current_stock
        $products = Product::orderBy('product_name')->get()->map(function($p) {
            $totalIn = RawStock::where('rawpro_id', $p->id)->sum('quantity_in');
            $totalOut = RawStock::where('rawpro_id', $p->id)->sum('quantity_out');
            $totalOutSales = SalesInvoiceItem::where('product_id', $p->id)->sum('qty');
            $p->current_stock = $totalIn - ($totalOut + $totalOutSales);
            return $p;
        });

        return view('raw-stocks.index', compact('products'));
    }

    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $type = $request->get('type');

        $query = Product::query();

        if ($type === 'id') {
            $query->where('product_code', 'like', "%$q%");
        } elseif ($type === 'name') {
            $query->where('product_name', 'like', "%$q%");
        } elseif ($type === 'group') {
            $query->where('product_group', 'like', "%$q%");
        } else {
            $query->where(function($q2) use ($q) {
                $q2->where('product_name','like',"%$q%")
                   ->orWhere('product_code','like',"%$q%");
            });
        }

        $products = $query->limit(50)->get()->map(function($p) {
            $totalIn = RawStock::where('rawpro_id', $p->id)->sum('quantity_in');
            $totalOut = RawStock::where('rawpro_id', $p->id)->sum('quantity_out');
            $totalOutSales = SalesInvoiceItem::where('product_id', $p->id)->sum('qty');
            $p->current_stock = $totalIn - ($totalOut + $totalOutSales);
            return $p;
        });

        return response()->json($products);
    }

    public function history(Product $product)
    {
        // raw stock logs (both purchases and sales-out if we logged them)
        $logs = RawStockLog::where('rawpro_id', $product->id)
                    ->orderBy('trans_date', 'asc')
                    ->get();

        // If you also want to include sales that weren't logged in raw_stock_logs,
        // you can append them here. (But we'll log sales in controller below.)

        $totalIn = $logs->filter(fn($l) => strtolower($l->trans_type) === 'in')->sum('qty');
        $totalOut = $logs->filter(fn($l) => strtolower($l->trans_type) === 'out')->sum('qty');
        $currentStock = $totalIn - $totalOut;

        return response()->json([
            'product' => $product,
            'logs' => $logs,
            'total_in' => $totalIn,
            'total_out' => $totalOut,
            'current_stock' => $currentStock
        ]);
    }
}
