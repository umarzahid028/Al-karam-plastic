<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Schema; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\RawStock;    // ✅ Add this
use App\Models\Product;     // ✅ Add this if using Product
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

public function salesSummary(Request $request)
{
    $type = $request->input('type', 'customer'); // customer or raw_supplier
    $from = $request->input('from_date', null);
    $to   = $request->input('to_date', null);
    $week = $request->input('week', null); // new week input

    // if week is selected, calculate start and end dates
    if ($week) {
        [$year, $weekNumber] = explode('-W', $week);
        $from = Carbon::now()->setISODate($year, $weekNumber)->startOfWeek()->toDateString();
        $to   = Carbon::now()->setISODate($year, $weekNumber)->endOfWeek()->toDateString();
    } else {
        $from = $from ?? Carbon::now()->startOfMonth()->toDateString();
        $to   = $to   ?? Carbon::now()->endOfMonth()->toDateString();
    }

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
        'type','from','to','week','grossTotal','totalDiscount','totalTax','grandTotal','sales'
    ));
}

public function stockReport(Request $request)
{
    $fromDate = $request->from_date ? Carbon::parse($request->from_date) : null;
    $toDate   = $request->to_date ? Carbon::parse($request->to_date) : null;

    // Get stock movements
    $stocks = DB::table('raw_stocks')
        ->join('raw_materials', 'raw_stocks.rawpro_id', '=', 'raw_materials.id')
        ->leftJoin('raw_stock_logs', 'raw_stocks.rawpro_id', '=', 'raw_stock_logs.rawpro_id')
        ->select(
            'raw_materials.material_name',
            DB::raw('SUM(raw_stocks.quantity_in) as total_in'),
            DB::raw('SUM(raw_stocks.quantity_out) as total_out'),
            DB::raw('AVG(raw_stock_logs.price) as avg_price') // average purchase price
        )
        ->groupBy('raw_stocks.rawpro_id', 'raw_materials.material_name')
        ->orderBy('raw_materials.material_name')
        ->get();

    return view('report.stock_report', [
        'stocks' => $stocks,
        'fromDate' => $fromDate?->format('Y-m-d'),
        'toDate' => $toDate?->format('Y-m-d'),
    ]);
}
public function saleStockReport(Request $request)
{
    $fromDate = $request->from_date ? Carbon::parse($request->from_date) : null;
    $toDate   = $request->to_date ? Carbon::parse($request->to_date) : null;

    // Total sold per material
    $soldQuery = DB::table('sales_invoice_items as sii')
        ->join('products as p', 'sii.product_id', '=', 'p.id')
        ->select(
            'p.id as product_id',
            'p.product_name',
            DB::raw('SUM(sii.qty) as total_sold'),
            DB::raw('p.cost_price as purchase_price')
        )
        ->groupBy('p.id', 'p.product_name', 'p.cost_price');

    if ($fromDate) $soldQuery->whereDate('sii.created_at', '>=', $fromDate);
    if ($toDate)   $soldQuery->whereDate('sii.created_at', '<=', $toDate);

    $sold = $soldQuery->get();

    // Sirf sold materials hi report me laao
    $stocks = [];
    foreach ($sold as $s) {
        $stocks[] = (object)[
            'material_name' => $s->product_name,
            'total_in' => $s->total_sold,
            'total_out' => $s->total_sold,
            'current_stock' => 0,
            'purchase_price' => $s->purchase_price,
            'stock_value' => $s->total_sold * $s->purchase_price
        ];
    }

    // Convert array to collection taake blade me sum(), count() use ho sake
    $stocks = collect($stocks);

    return view('report.sale_stock_report', compact('stocks'));
}
public function saleSheetReport(Request $request)
{
    $fromDate = $request->from_date ? Carbon::parse($request->from_date) : null;
    $toDate   = $request->to_date ? Carbon::parse($request->to_date) : null;

    $sales = DB::table('sales_invoices as si')
    ->join('sales_invoice_items as sii', 'si.id', '=', 'sii.sales_invoice_id')
    ->join('products as p', 'sii.product_id', '=', 'p.id')
    ->join('customers as c', 'si.buyer_id', '=', 'c.id')
    ->select(
        'si.invoice_no',
        'si.invoice_date',
        'c.name as buyer_name',
        'p.product_name',
        'sii.qty',
        DB::raw('p.sale_price as rate'),           // use sale_price from products
        DB::raw('sii.qty * p.sale_price as total') // calculate total
    )
    ->when($fromDate, fn($q) => $q->whereDate('si.invoice_date', '>=', $fromDate))
    ->when($toDate, fn($q) => $q->whereDate('si.invoice_date', '<=', $toDate))
    ->orderBy('si.invoice_date', 'desc')
    ->get();

    return view('report.sale_sheet', compact('sales', 'fromDate', 'toDate'));
}
public function ledgerReport(Request $request)
{
    $fromDate = $request->from_date ? Carbon::parse($request->from_date) : null;
    $toDate   = $request->to_date ? Carbon::parse($request->to_date) : null;

    $query = DB::table('ledgers')
        ->select('invoice_date', 'party_id', 'description', 'debit', 'credit')
        ->orderBy('invoice_date', 'asc');

    if ($fromDate) $query->whereDate('invoice_date', '>=', $fromDate);
    if ($toDate)   $query->whereDate('invoice_date', '<=', $toDate);

    $ledgers = $query->get();

    return view('report.ledger_report', compact('ledgers', 'fromDate', 'toDate'));
}
public function paymentsReport(Request $request)
{
    $fromDate = $request->from_date ? Carbon::parse($request->from_date) : null;
    $toDate   = $request->to_date ? Carbon::parse($request->to_date) : null;

    $payments = DB::table('ledgers')
        ->where('ref_type', 'payment') // only payments
        ->when($fromDate, fn($q) => $q->whereDate('invoice_date', '>=', $fromDate))
        ->when($toDate, fn($q) => $q->whereDate('invoice_date', '<=', $toDate))
        ->orderBy('invoice_date', 'asc')
        ->get();

    return view('report.payments', [
        'payments' => $payments,
        'fromDate' => $fromDate,
        'toDate'   => $toDate,
    ]);
}


public function stockSummary(Request $request)
{
    $from = $request->input('from_date');   // e.g. 2025-09-01
    $to   = $request->input('to_date');     // optional range end

    // Base product query
    $products = Product::select('id','product_code','product_name','unit')->get();

    foreach ($products as $p) {
        // --- Opening up to "from" date ---
        $p->opening_qty = DB::table('raw_stock_logs')
            ->where('rawpro_id', $p->id)
            ->when($from, fn($q) => $q->where('trans_date', '<', $from))
            ->selectRaw("COALESCE(SUM(CASE WHEN trans_type='in' THEN qty ELSE -qty END),0) as opening")
            ->value('opening');

        // --- Purchased & Sold inside the selected date range ---
        $p->purchased_qty = DB::table('raw_stock_logs')
            ->where('rawpro_id', $p->id)
            ->when($from, fn($q) => $q->where('trans_date','>=',$from))
            ->when($to,   fn($q) => $q->where('trans_date','<=',$to))
            ->where('trans_type','in')
            ->sum('qty');

        $p->sold_qty = DB::table('raw_stock_logs')
            ->where('rawpro_id', $p->id)
            ->when($from, fn($q) => $q->where('trans_date','>=',$from))
            ->when($to,   fn($q) => $q->where('trans_date','<=',$to))
            ->where('trans_type','out')
            ->sum('qty');

        $p->closing_qty = ($p->opening_qty + $p->purchased_qty) - $p->sold_qty;
    }

    // Totals for the summary row
    $totals = [
        'opening'   => $products->sum('opening_qty'),
        'purchased' => $products->sum('purchased_qty'),
        'sold'      => $products->sum('sold_qty'),
        'closing'   => $products->sum('closing_qty'),
    ];

    return view('report.stock-summary',
        compact('products','totals','from','to'));
}


public function dailySheet(Request $request)
{
    $date = $request->input('date', Carbon::today()->toDateString());

    // ---- Summary totals ----
    $sales = DB::table('sales_invoices')
        ->whereDate('invoice_date', $date)
        ->selectRaw('COUNT(*) as invoices,
                     SUM(total_amount) as gross,
                     SUM(paid_amount)  as paid')
        ->first();

    $purchases = DB::table('purchases')
        ->whereDate('purchase_date', $date)
        ->selectRaw('COUNT(*) as invoices,
                     SUM(total_amount) as gross,
                     SUM(paid_amount)  as paid')
        ->first();

    $expenses = DB::table('expenses')
        ->whereDate('expense_date', $date)
        ->sum('amount');

    $stock = DB::table('raw_stock_logs')
        ->whereDate('trans_date', $date)
        ->selectRaw("
            SUM(CASE WHEN trans_type='in'  THEN qty ELSE 0 END) as qty_in,
            SUM(CASE WHEN trans_type='out' THEN qty ELSE 0 END) as qty_out
        ")->first();

    // ---- Sales detail: join to raw_suppliers (buyer_id) ----
    $salesList = DB::table('sales_invoices as si')
        ->join('raw_suppliers as rs', 'si.buyer_id', '=', 'rs.id')
        ->whereDate('si.invoice_date', $date)
        ->select(
            'si.invoice_no',
            'rs.name as buyer_name',    // change to rs.company_name if you prefer
            'si.total_amount',
            'si.paid_amount'
        )
        ->orderBy('si.id', 'desc')
        ->get();

    // ---- Purchase detail: also join to raw_suppliers ----
    $purchaseList = DB::table('purchases as p')
        ->join('raw_suppliers as rs', 'p.supplier_id', '=', 'rs.id')
        ->whereDate('p.purchase_date', $date)
        ->select(
            'p.invoice_no',
            'rs.name as supplier_name', // or rs.company_name if that’s your display name
            'p.total_amount',
            'p.paid_amount'
        )
        ->orderBy('p.id', 'desc')
        ->get();

        $expenseList = DB::table('expenses')
        ->whereDate('expense_date', $date)
        ->select(
            'expense_category',
            'description',
            'amount',
            'vendor',
            'payment_method'
        )
        ->orderBy('id', 'desc')
        ->get();
    
    return view('report.daily-sheet', compact(
        'date',
        'sales',
        'purchases',
        'expenses',
        'stock',
        'salesList',
        'purchaseList',
        'expenseList'
    ));
}
}