<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function salesDetailReport(Request $request)
    {
        $query = DB::table('customer_invoice_items')
            ->join('customer_invoices', 'customer_invoice_items.customer_invoice_id', '=', 'customer_invoices.id')
            ->join('customers', 'customer_invoices.buyer_id', '=', 'customers.id')
            ->join('raw_materials', 'customer_invoice_items.product_id', '=', 'raw_materials.id')
            ->select(
                'customers.name as customer_name',
                'customers.address',
                DB::raw("TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(customers.address, ',', 2), ',', -1)) as city"), // 👈 city extract
                'customer_invoices.invoice_no',
                'customer_invoices.invoice_date',
                'raw_materials.material_name as product',
                'customer_invoice_items.qty',
                'customer_invoice_items.price',
                DB::raw('(customer_invoice_items.qty * customer_invoice_items.price) as line_total')
            );
    
        // Date filters
        if ($request->filled('from')) {
            $query->whereDate('customer_invoices.invoice_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('customer_invoices.invoice_date', '<=', $request->to);
        }
    
        // City filter (LIKE)
        if ($request->filled('city')) {
            $query->where('customers.address', 'LIKE', "%{$request->city}%");
        }
    
        $records = $query->orderBy('customer_invoices.invoice_date', 'desc')->get();
    
        $total = $records->sum('line_total');
    
        return view('reports.sales_detail', [
            'records' => $records,
            'title'   => 'Customer Sales Detail',
            'total'   => $total,
            'from'    => $request->from,
            'to'      => $request->to,
            'city'    => $request->city,
        ]);
    }
    
    public function purchaseDetailReport()
    {
        $records = DB::table('purchase_items')
            ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->join('raw_suppliers', 'purchases.supplier_id', '=', 'raw_suppliers.id')
            ->join('raw_materials', 'purchase_items.raw_material_id', '=', 'raw_materials.id')
            ->select(
                'raw_suppliers.name as supplier_name',
                'purchases.invoice_no',
                'purchases.invoice_date',
                'raw_materials.material_name as product',
                'purchase_items.quantity',
                'purchase_items.unit_price',
                DB::raw('(purchase_items.quantity * purchase_items.unit_price) as line_total')
            )
            ->orderBy('purchases.invoice_date', 'desc')
            ->get();
    
        // 👇 Total purchase amount calculate karo
        $total = $records->sum('line_total');
    
        return view('reports.purchase_detail', [
            'records' => $records,
            'title'   => 'Supplier Purchases Detail',
            'total'   => $total,  // 👈 ab blade me available hoga
        ]);
    }
    
public function stockReport(Request $request)
{
    $query = DB::table('raw_materials as rm')
        ->leftJoin(DB::raw("
            (SELECT raw_material_id, SUM(quantity) as purchased_qty 
             FROM purchase_items 
             GROUP BY raw_material_id) as p
        "), 'rm.id', '=', 'p.raw_material_id')
        ->leftJoin(DB::raw("
            (SELECT product_id, SUM(qty) as sold_qty 
             FROM customer_invoice_items 
             GROUP BY product_id) as s
        "), 'rm.id', '=', 's.product_id')
        ->select(
            'rm.material_code',
            'rm.material_name',
            DB::raw('COALESCE(p.purchased_qty,0) as total_purchased'),
            DB::raw('COALESCE(s.sold_qty,0) as total_sold'),
            DB::raw('(COALESCE(p.purchased_qty,0) - COALESCE(s.sold_qty,0)) as current_stock')
        );

    // filters
    if ($request->filled('code')) {
        $query->where('rm.material_code', 'like', '%'.$request->code.'%');
    }
    if ($request->filled('name')) {
        $query->where('rm.material_name', 'like', '%'.$request->name.'%');
    }

    $records = $query->get();

    // totals
    $totals = [
        'purchased' => $records->sum('total_purchased'),
        'sold'      => $records->sum('total_sold'),
        'stock'     => $records->sum('current_stock'),
    ];

    return view('reports.stock', [
        'records' => $records,
        'totals'  => $totals,
        'title'   => 'Stock Position',
        'filters' => $request->only(['code','name']),
    ]);
}
public function summaryReport()
{
    $totalSales = DB::table('customer_invoices')->sum('total_amount');
    $totalPurchases = DB::table('purchases')->sum('total_amount');
    $profitLoss = $totalSales - $totalPurchases;

    // Recent 5 Sales
    $recentSales = DB::table('customer_invoices')
        ->join('customers', 'customer_invoices.buyer_id', '=', 'customers.id')
        ->select('customer_invoices.invoice_no', 'customers.name as customer_name', 'customer_invoices.total_amount')
        ->orderBy('customer_invoices.invoice_date', 'desc')
        ->limit(5)
        ->get();

    // Recent 5 Purchases
    $recentPurchases = DB::table('purchases')
        ->join('raw_suppliers', 'purchases.supplier_id', '=', 'raw_suppliers.id')
        ->select('purchases.invoice_no', 'raw_suppliers.name as supplier_name', 'purchases.total_amount')
        ->orderBy('purchases.invoice_date', 'desc')
        ->limit(5)
        ->get();

    // Chart Data (Last 12 months)
    $months = [];
    $salesData = [];
    $purchaseData = [];
    for ($i = 11; $i >= 0; $i--) {
        $month = now()->subMonths($i)->format('M Y');
        $months[] = $month;

        $salesData[] = DB::table('customer_invoices')
            ->whereMonth('invoice_date', now()->subMonths($i)->month)
            ->whereYear('invoice_date', now()->subMonths($i)->year)
            ->sum('total_amount');

        $purchaseData[] = DB::table('purchases')
            ->whereMonth('invoice_date', now()->subMonths($i)->month)
            ->whereYear('invoice_date', now()->subMonths($i)->year)
            ->sum('total_amount');
    }

    return view('reports.summary', [
        'totalSales' => $totalSales,
        'totalPurchases' => $totalPurchases,
        'profitLoss' => $profitLoss,
        'recentSales' => $recentSales,
        'recentPurchases' => $recentPurchases,
        'chartLabels' => $months,
        'chartSales' => $salesData,
        'chartPurchases' => $purchaseData,
    ]);
}
          
    
}    