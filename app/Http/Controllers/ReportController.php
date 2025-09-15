<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Schema; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function index()
    {
        return view('report.index'); 
    }
    
    public function totalSalesReport(Request $request)
    {
        // Filters
        $fromDate = $request->from_date ? Carbon::parse($request->from_date) : null;
        $toDate   = $request->to_date ? Carbon::parse($request->to_date) : null;
    
        // Sales query with buyer name
        $salesQuery = DB::table('sales_invoices')
            ->join('sales_invoice_items', 'sales_invoices.id', '=', 'sales_invoice_items.sales_invoice_id')
            ->join('products', 'sales_invoice_items.product_id', '=', 'products.id')
            ->join('customers', 'sales_invoices.buyer_id', '=', 'customers.id')
            ->select(
                'sales_invoices.invoice_no',
                'sales_invoices.invoice_date',
                'customers.name as buyer_name',
                DB::raw('SUM(sales_invoice_items.qty) as total_qty'),
                DB::raw('SUM(sales_invoice_items.total) as total_line')
            )
            ->groupBy('sales_invoices.id', 'sales_invoices.invoice_no', 'sales_invoices.invoice_date', 'customers.name')
            ->orderBy('sales_invoices.invoice_date', 'desc');
    
        if ($fromDate) {
            $salesQuery->whereDate('sales_invoices.invoice_date', '>=', $fromDate);
        }
        if ($toDate) {
            $salesQuery->whereDate('sales_invoices.invoice_date', '<=', $toDate);
        }
    
        $records = $salesQuery->get();
    
        // Grand total (sum of line totals) — this is what your blade expects as $grandTotal
        $grandTotal = $records->sum('total_line') ?: 0.00;
    
        // Also keep totalSales for compatibility (if you used it elsewhere)
        $totalSales = $grandTotal;
    
        // Stock movement (increase/decrease)
        $stockQuery = DB::table('raw_stocks')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(quantity_in) as total_in'),
                DB::raw('SUM(quantity_out) as total_out')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc');
    
        if ($fromDate) $stockQuery->whereDate('created_at', '>=', $fromDate);
        if ($toDate) $stockQuery->whereDate('created_at', '<=', $toDate);
    
        $stocks = $stockQuery->get();
    
        return view('report.sales_report', [
            'records'     => $records,
            'stocks'      => $stocks,
            'totalSales'  => $totalSales,
            'grandTotal'  => $grandTotal,   // <-- added
            'fromDate'    => $fromDate?->format('Y-m-d'),
            'toDate'      => $toDate?->format('Y-m-d'),
        ]);
    }
    
    public function salesReturnReport(Request $request)
{
    $fromDate = $request->from_date ? Carbon::parse($request->from_date) : null;
    $toDate   = $request->to_date ? Carbon::parse($request->to_date) : null;

    $query = DB::table('sales_returns')
        ->join('sales_return_items', 'sales_returns.id', '=', 'sales_return_items.sales_return_id')
        ->join('sales_invoice_items', 'sales_return_items.sales_invoice_item_id', '=', 'sales_invoice_items.id')
        ->join('products', 'sales_invoice_items.product_id', '=', 'products.id')
        ->join('sales_invoices', 'sales_invoices.id', '=', 'sales_returns.sales_invoice_id')
        ->join('customers', 'sales_invoices.buyer_id', '=', 'customers.id')
        ->select(
            'sales_returns.id as return_id',
            'sales_returns.return_date',
            'sales_invoices.invoice_no',
            'customers.name as buyer_name',
            'products.product_name as product_name',
            'sales_return_items.quantity as returned_qty',
            'sales_return_items.subtotal as return_amount',
            'sales_returns.remarks'
        )
        ->orderBy('sales_returns.return_date', 'desc');

    if ($fromDate) $query->whereDate('sales_returns.return_date', '>=', $fromDate);
    if ($toDate) $query->whereDate('sales_returns.return_date', '<=', $toDate);

    $returns = $query->get();
    $totalReturns = $returns->sum('return_amount');

    return view('report.return_sales_report', [
        'returns' => $returns,
        'totalReturns' => $totalReturns,
        'fromDate' => $fromDate?->format('Y-m-d'),
        'toDate' => $toDate?->format('Y-m-d'),
    ]);
}
public function totalPurchaseReport(Request $request)
{
    $fromDate = $request->from_date ? Carbon::parse($request->from_date) : null;
    $toDate   = $request->to_date ? Carbon::parse($request->to_date) : null;

    $query = DB::table('purchases')
    ->join('purchase_items', 'purchases.id', '=', 'purchase_items.purchase_id')
    ->join('raw_materials', 'purchase_items.raw_material_id', '=', 'raw_materials.id')
    ->leftJoin('raw_suppliers', 'purchases.supplier_id', '=', 'raw_suppliers.id') // ✅ correct table
    ->select(
        'purchases.id as purchase_id',
        'purchases.invoice_no',
        'purchases.purchase_date',
        'raw_suppliers.company_name as supplier_name',   // ✅ match the table alias
        'raw_materials.material_name as material_name',
        'purchase_items.quantity',
        'purchase_items.unit_price',
        'purchase_items.total_price'
    )
    ->orderBy('purchases.purchase_date', 'desc');
    if ($fromDate) $query->whereDate('purchases.purchase_date', '>=', $fromDate);
    if ($toDate)   $query->whereDate('purchases.purchase_date', '<=', $toDate);

    $purchases  = $query->get();
    $grandTotal = $purchases->sum('total_price');

    return view('report.purchase_report', [
        'purchases'  => $purchases,
        'grandTotal' => $grandTotal,
        'fromDate'   => $fromDate?->format('Y-m-d'),
        'toDate'     => $toDate?->format('Y-m-d'),
    ]);
}

public function totalPurchaseReturnReport(Request $request)
{
    $fromDate = $request->from_date ? Carbon::parse($request->from_date) : null;
    $toDate   = $request->to_date ? Carbon::parse($request->to_date) : null;
    $week     = $request->week ?? null; // new week input

    $query = DB::table('purchase_returns')
        ->join('purchase_return_items','purchase_returns.id','=','purchase_return_items.purchase_return_id')
        ->join('purchase_items','purchase_return_items.purchase_item_id','=','purchase_items.id')
        ->join('raw_materials','purchase_items.raw_material_id','=','raw_materials.id')
        ->join('purchases','purchase_returns.purchase_id','=','purchases.id')
        ->join('raw_suppliers','purchases.supplier_id','=','raw_suppliers.id')
        ->select(
            'purchase_returns.return_date',
            'purchase_returns.remarks',
            'purchases.invoice_no',
            'raw_suppliers.company_name as supplier_name',
            'raw_materials.material_name',
            'purchase_return_items.quantity',
            'purchase_return_items.price',
            'purchase_return_items.subtotal'
        )
        ->orderBy('purchase_returns.return_date','desc');

    // Apply week filter if selected
    if ($week) {
        // week format: YYYY-Www
        [$year, $weekNumber] = explode('-W', $week);
        $startOfWeek = Carbon::now()->setISODate($year, $weekNumber)->startOfWeek();
        $endOfWeek   = Carbon::now()->setISODate($year, $weekNumber)->endOfWeek();
        $query->whereBetween('purchase_returns.return_date', [$startOfWeek, $endOfWeek]);
    } else {
        if ($fromDate) {
            $query->whereDate('purchase_returns.return_date','>=',$fromDate);
        }
        if ($toDate) {
            $query->whereDate('purchase_returns.return_date','<=',$toDate);
        }
    }

    $returns = $query->get();
    $grandTotal = $returns->sum('subtotal');

    return view('report.purchase_return_report', [
        'returns'    => $returns,
        'grandTotal' => $grandTotal,
        'fromDate'   => $fromDate?->format('Y-m-d'),
        'toDate'     => $toDate?->format('Y-m-d'),
        'week'       => $week, // pass week to the view
    ]);
}


public function rawSupplierPurchaseSummary(Request $request)
{
    // ✅ Agar user ne dates bheji hain to unka use karo, warna current month
    $from = $request->input('from_date');
    $to   = $request->input('to_date');

    if (!$from || !$to) {
        $from = Carbon::now()->startOfMonth()->toDateString();
        $to   = Carbon::now()->endOfMonth()->toDateString();
    }

    // 1️⃣ Purchases with supplier (LEFT JOIN so missing supplier bhi aaye)
    $purchases = DB::table('purchases as p')
        ->leftJoin('raw_suppliers as s', 'p.supplier_id', '=', 's.id')
        ->select(
            'p.id',
            'p.invoice_no',
            'p.purchase_date',
            's.company_name as supplier_name',
            'p.payment_method',
            'p.total_amount',
            'p.paid_amount',
            'p.status'
        )
        ->whereBetween('p.purchase_date', [$from, $to])
        ->orderByDesc('p.purchase_date')
        ->get();

    // 2️⃣ Purchase items group by purchase
    $purchaseItems = DB::table('purchase_items as pi')
        ->join('raw_materials as rm', 'pi.raw_material_id', '=', 'rm.id')
        ->select(
            'pi.purchase_id',
            'rm.material_name',
            'pi.quantity',
            'pi.unit_price',
            'pi.total_price'
        )
        ->whereIn('pi.purchase_id', $purchases->pluck('id'))
        ->get()
        ->groupBy('purchase_id');

    // 3️⃣ Totals
    $grossTotal = $purchases->sum('total_amount');
    $paidTotal  = $purchases->sum('paid_amount');

    return view('report.raw_supplier_purchase_summary', compact(
        'from', 'to', 'purchases', 'purchaseItems', 'grossTotal', 'paidTotal'
    ));
}
public function rawMaterialItemReport(Request $request)
{
    $from = $request->input('from_date', Carbon::now()->startOfMonth()->toDateString());
    $to   = $request->input('to_date',   Carbon::now()->endOfMonth()->toDateString());
// 👉 NEW: Week filter
if ($request->filled('week')) {
    // HTML <input type="week"> ka format hota hai: YYYY-Www (e.g. 2025-W37)
    [$year, $week] = explode('-W', $request->week);

    // Week start (Monday) aur end (Sunday) nikalne ke liye Carbon helper
    $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek();
    $endOfWeek   = Carbon::now()->setISODate($year, $week)->endOfWeek();

    $from = $startOfWeek->toDateString();
    $to   = $endOfWeek->toDateString();
}
    $report = DB::table('raw_materials as rm')
        ->leftJoin('raw_material_issue_items as rii', 'rm.id', '=', 'rii.rawpro_id')
        ->leftJoin('raw_material_issues as ri', 'rii.issue_id', '=', 'ri.id')
        ->leftJoin('stores as s', 'rm.store_id', '=', 's.id')
        ->select(
            'rm.id',
            'rm.material_code',
            'rm.material_name',
            'rm.unit',
            'rm.stocks as opening_stock',
            DB::raw('COALESCE(SUM(rii.qty),0) as total_issued'),
            DB::raw('MAX(ri.issue_date) as last_issue_date'),
            DB::raw('MAX(s.store_name) as store_name'),
            // issued_by and approved_by are plain text columns in raw_material_issues
            DB::raw('MAX(ri.issued_by) as issued_by'),
            DB::raw('MAX(ri.approved_by) as approved_by'),
            // make sure closing never goes below zero
            DB::raw('GREATEST(rm.stocks - COALESCE(SUM(rii.qty),0), 0) as closing_stock')
        )
        ->whereBetween('ri.issue_date', [$from, $to])
        ->groupBy(
            'rm.id',
            'rm.material_code',
            'rm.material_name',
            'rm.unit',
            'rm.stocks',
            's.store_name'
        )
        ->orderBy('rm.material_name')
        ->get();

    return view('report.raw_material_item_report', compact('from', 'to', 'report'));
}


public function ordersSummary(Request $request)
{
    // Default date range: first to last day of the current month
    $from = $request->input('from_date', now()->startOfMonth()->toDateString());
    $to   = $request->input('to_date',   now()->endOfMonth()->toDateString());

    // ✅ Optional Week filter (HTML <input type="week"> returns e.g. "2025-W37")
    if ($request->filled('week')) {
        [$year, $week] = explode('-W', $request->week);

        // Get Monday-to-Sunday range for that ISO week
        $from = Carbon::now()->setISODate($year, $week)->startOfWeek()->toDateString();
        $to   = Carbon::now()->setISODate($year, $week)->endOfWeek()->toDateString();
    }

    // ✅ Group orders by supplier and count totals
    $bySupplier = DB::table('sales_invoices as si')
        ->join('raw_suppliers as rs', 'rs.id', '=', 'si.buyer_id')
        ->selectRaw("
            rs.company_name AS supplier,
            COUNT(*) AS total_orders,
            SUM(CASE WHEN si.status = 'pending' THEN 1 ELSE 0 END) AS pending_orders,
            -- Treat both 'completed' and 'paid' as completed
            SUM(CASE WHEN si.status IN ('completed','paid') THEN 1 ELSE 0 END) AS completed_orders,
            SUM(si.total_amount) AS total_amount
        ")
        ->whereBetween('si.invoice_date', [$from, $to])
        ->groupBy('rs.company_name')
        ->orderBy('rs.company_name')
        ->get();

    // ✅ Overall totals for the summary boxes in the Blade view
    $totals = (object) [
        'total_orders'     => $bySupplier->sum('total_orders'),
        'pending_orders'   => $bySupplier->sum('pending_orders'),
        'completed_orders' => $bySupplier->sum('completed_orders'),
        'grand_total'      => $bySupplier->sum('total_amount'),
    ];

    // Pass everything to the Blade view
    return view('report.orders_summary', compact('from', 'to', 'bySupplier', 'totals'));
}

}