<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Totals
        $totalSales     = DB::table('sales_invoices')->sum('total_amount');
        $totalPurchases = DB::table('purchases')->sum('total_amount');

        // Stock = total_in - total_out
        $totalStock = DB::table('raw_stocks')
            ->selectRaw('COALESCE(SUM(quantity_in - quantity_out),0) as total')
            ->value('total');

        // Monthly data (last 6 months)
        $months    = [];
        $sales     = [];
        $purchases = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');

            $sales[] = DB::table('sales_invoices')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');

            $purchases[] = DB::table('purchases')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');
        }

        // Stock distribution by product
        $stockLabels = [];
        $stockData   = [];

        $stocks = DB::table('raw_stocks')
            ->join('products', 'raw_stocks.rawpro_id', '=', 'products.id')
            ->select(
                'products.product_name as product_name',
                DB::raw('COALESCE(SUM(quantity_in - quantity_out),0) as qty')
            )
            ->groupBy('products.product_name')
            ->get();

        foreach ($stocks as $s) {
            $stockLabels[] = $s->product_name;
            $stockData[]   = $s->qty;
        }

        $lowStockProducts = DB::table('raw_stocks')
        ->join('products', 'raw_stocks.rawpro_id', '=', 'products.id')
        ->select(
            'products.product_name',
            DB::raw('COALESCE(SUM(raw_stocks.quantity_in - raw_stocks.quantity_out),0) as stock')
        )
        ->groupBy('products.id', 'products.product_name')
        ->havingRaw('stock < 20') // yahan threshold apni marzi se rakh lo
        ->get();
        $lowStockProducts = DB::table('raw_stocks')
        ->join('products', 'raw_stocks.rawpro_id', '=', 'products.id')
        ->select(
            'products.product_name',
            DB::raw('COALESCE(SUM(raw_stocks.quantity_in - raw_stocks.quantity_out),0) as stock')
        )
        ->groupBy('products.id', 'products.product_name')
        ->havingRaw('stock < 20') // yahan threshold apni marzi se rakh lo
        ->get();
    
        return view('welcome', compact(
            'totalSales',
            'totalPurchases',
            'totalStock',
            'months',
            'sales',
            'purchases',
            'stockLabels',
            'stockData',
            'lowStockProducts'
        ));
        
    }
}
