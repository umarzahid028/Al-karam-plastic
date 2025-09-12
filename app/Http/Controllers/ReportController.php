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

    $query = DB::table('purchase_returns')
        ->join('purchase_return_items','purchase_returns.id','=','purchase_return_items.purchase_return_id')
        ->join('purchase_items','purchase_return_items.purchase_item_id','=','purchase_items.id')
        ->join('raw_materials','purchase_items.raw_material_id','=','raw_materials.id')
        ->join('purchases','purchase_returns.purchase_id','=','purchases.id')   // <-- join purchases
        ->join('raw_suppliers','purchases.supplier_id','=','raw_suppliers.id')
        ->select(
            'purchase_returns.return_date',
            'purchase_returns.remarks',
            'purchases.invoice_no',                    // <-- make sure this is here
            'raw_suppliers.company_name as supplier_name',
            'raw_materials.material_name',
            'purchase_return_items.quantity',
            'purchase_return_items.price',
            'purchase_return_items.subtotal'
        )
        ->orderBy('purchase_returns.return_date','desc');

    if ($fromDate) {
        $query->whereDate('purchase_returns.return_date','>=',$fromDate);
    }
    if ($toDate) {
        $query->whereDate('purchase_returns.return_date','<=',$toDate);
    }

    $returns = $query->get();
    $grandTotal = $returns->sum('subtotal');

    return view('report.purchase_return_report', [
        'returns'    => $returns,
        'grandTotal' => $grandTotal,
        'fromDate'   => $fromDate?->format('Y-m-d'),
        'toDate'     => $toDate?->format('Y-m-d'),
    ]);
}

public function salesSummary(Request $request)
{
    $type = $request->input('type', 'customer'); // customer or raw_supplier
    $from = $request->input('from_date', Carbon::now()->startOfMonth()->toDateString());
    $to   = $request->input('to_date',   Carbon::now()->endOfMonth()->toDateString());

    if ($type === 'raw_supplier') {
        // Raw Supplier Sales
        $sales = DB::table('sales_invoices as si')
            ->join('raw_suppliers as s', 'si.buyer_id', '=', 's.id')
            ->join('sales_invoice_items as sii', 'si.id', '=', 'sii.sales_invoice_id')
            ->select(
                'si.id',
                'si.invoice_no',
                'si.invoice_date',
                's.company_name as party_name',
                's.email as party_email',
                's.contact_no as party_phone',
                DB::raw('SUM(sii.qty) as total_qty'),
                DB::raw('SUM(sii.total) as gross_amount'),
                DB::raw('SUM(si.total_amount) as net_amount')
            )
            ->whereBetween('si.invoice_date', [$from, $to])
            ->groupBy(
                'si.id','si.invoice_no','si.invoice_date',
                's.company_name','s.email','s.contact_no'
            )
            ->orderByDesc('si.invoice_date')
            ->get();

        $totalDiscount = Schema::hasColumn('sales_invoices','discount')
            ? DB::table('sales_invoices')->whereBetween('invoice_date', [$from, $to])->sum('discount')
            : 0;

        $totalTax = Schema::hasColumn('sales_invoices','tax')
            ? DB::table('sales_invoices')->whereBetween('invoice_date', [$from, $to])->sum('tax')
            : 0;

    } else {
        // Customer Sales
        $sales = DB::table('customer_invoices as ci')
            ->join('customers as c', 'ci.buyer_id', '=', 'c.id')
            ->join('customer_invoice_items as cii', 'ci.id', '=', 'cii.customer_invoice_id')
            ->select(
                'ci.id',
                'ci.invoice_no',
                'ci.invoice_date',
                'c.name as party_name',
                'c.email as party_email',
                'c.contact_no as party_phone',
                DB::raw('SUM(cii.qty) as total_qty'),
                DB::raw('SUM(cii.total) as gross_amount'),
                DB::raw('SUM(ci.total_amount) as net_amount')
            )
            ->whereBetween('ci.invoice_date', [$from, $to])
            ->groupBy(
                'ci.id','ci.invoice_no','ci.invoice_date',
                'c.name','c.email','c.contact_no'
            )
            ->orderByDesc('ci.invoice_date')
            ->get();

        $totalDiscount = Schema::hasColumn('customer_invoices','discount')
            ? DB::table('customer_invoices')->whereBetween('invoice_date', [$from, $to])->sum('discount')
            : 0;

        $totalTax = Schema::hasColumn('customer_invoices','tax')
            ? DB::table('customer_invoices')->whereBetween('invoice_date', [$from, $to])->sum('tax')
            : 0;
    }

    $grossTotal = $sales->sum('gross_amount');
    $grandTotal = $sales->sum('net_amount');

    return view('report.sales_summary', compact(
        'type','from','to','grossTotal','totalDiscount','totalTax','grandTotal','sales'
    ));
}
public function rawSupplierPurchaseSummary(Request $request)
{
    // 1️⃣ Inputs
    $from = $request->input('from_date', Carbon::now()->startOfMonth()->toDateString());
    $to   = $request->input('to_date', Carbon::now()->endOfMonth()->toDateString());

    // 2️⃣ Query: Raw Supplier Purchases
    $purchases = DB::table('purchases as p')
        ->join('raw_suppliers as s', 'p.supplier_id', '=', 's.id')
        ->join('purchase_items as pi', 'p.id', '=', 'pi.purchase_id')
        ->select(
            'p.id',
            'p.invoice_no',
            'p.purchase_date',
            's.company_name as supplier_name',
            's.email as supplier_email',
            's.contact_no as supplier_phone',
            DB::raw('SUM(pi.quantity) as total_qty'),
            DB::raw('SUM(pi.total_price) as gross_amount'),
            DB::raw('SUM(p.total_amount) as net_amount')
        )
        ->whereBetween('p.purchase_date', [$from, $to])
        ->groupBy(
            'p.id',
            'p.invoice_no',
            'p.purchase_date',
            's.company_name',
            's.email',
            's.contact_no'
        )
        ->orderByDesc('p.purchase_date')
        ->get();

    // 3️⃣ Totals
    $grossTotal = $purchases->sum('gross_amount');
    $grandTotal = $purchases->sum('net_amount');

    $totalDiscount = Schema::hasColumn('purchases','discount')
        ? DB::table('purchases')->whereBetween('purchase_date', [$from, $to])->sum('discount')
        : 0;

    $totalTax = Schema::hasColumn('purchases','tax')
        ? DB::table('purchases')->whereBetween('purchase_date', [$from, $to])->sum('tax')
        : 0;

    // 4️⃣ Return view
    return view('report.raw_supplier_purchase_summary', compact(
        'from', 'to',
        'grossTotal', 'totalDiscount', 'totalTax', 'grandTotal',
        'purchases'
    ));
}
}