<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\SalesInvoice;
use App\Models\Purchase;
use App\Models\RawSupplier;
use DB;

class PaymentController extends Controller
{
    // Show payment form + lists
    public function index()
    {
        $buyers = RawSupplier::orderBy('company_name')->get();
        $suppliers = RawSupplier::orderBy('company_name')->get();
        $payments = Payment::latest()->with(['customer','supplier','saleInvoice','purchase'])->limit(50)->get();

        return view('payments.index', compact('buyers','suppliers','payments'));
    }

    // Store customer payment (receive)
    public function storeCustomer(Request $request)
    {
        $data = $request->validate([
            'buyer_id' => 'required|integer',
            'invoice_id' => 'nullable|integer',
            'invoice_no' => 'nullable|string',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:cash,bank,credit',
            'description' => 'nullable|string'
        ]);

        $payment = Payment::create(array_merge($data, [
            'type' => 'customer',
            'party_id' => $data['buyer_id']
        ]));

        // update invoice paid amount if invoice_id provided
        if(!empty($data['invoice_id'])) {
            $invoice = SalesInvoice::find($data['invoice_id']);
            if($invoice){
                $invoice->paid_amount = ($invoice->paid_amount ?? 0) + $data['amount'];
                if($invoice->paid_amount >= $invoice->total_amount) $invoice->status = 'paid';
                elseif($invoice->paid_amount > 0) $invoice->status = 'partial';
                else $invoice->status = 'pending';
                $invoice->save();
            }
        }

        return redirect()->back()->with('success','Customer payment recorded.');
    }

    // Store supplier payment (pay)
    public function storeSupplier(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => 'required|integer',
            'invoice_id' => 'nullable|integer',
            'invoice_no' => 'nullable|string',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:cash,bank,credit',
            'description' => 'nullable|string'
        ]);

        $payment = Payment::create([
            'type' => 'supplier',
            'party_id' => $data['supplier_id'],
            'invoice_id' => $data['invoice_id'] ?? null,
            'invoice_no' => $data['invoice_no'] ?? null,
            'payment_date' => $data['payment_date'],
            'amount' => $data['amount'],
            'method' => $data['method'],
            'description' => $data['description'] ?? null
        ]);

        if(!empty($data['invoice_id'])){
            $purchase = Purchase::find($data['invoice_id']);
            if($purchase){
                $purchase->paid_amount = ($purchase->paid_amount ?? 0) + $data['amount'];
                if($purchase->paid_amount >= $purchase->total_amount) $purchase->status = 'paid';
                elseif($purchase->paid_amount > 0) $purchase->status = 'partial';
                else $purchase->status = 'pending';
                $purchase->save();
            }
        }

        return redirect()->back()->with('success','Supplier payment recorded.');
    }

    // Customers outstanding report
    public function customersOutstanding()
    {
        $customers = DB::table('raw_suppliers as b')
            ->select('b.id','b.company_name',
                DB::raw('COALESCE(SUM(si.total_amount),0) as total_sales'),
                DB::raw('COALESCE(SUM(p.amount),0) as total_paid'),
                DB::raw('(COALESCE(SUM(si.total_amount),0) - COALESCE(SUM(p.amount),0)) as balance')
            )
            ->leftJoin('sales_invoices as si','b.id','si.buyer_id')
            ->leftJoin('payments as p', function($join){
                $join->on('b.id','=','p.party_id')->where('p.type','customer');
            })
            ->groupBy('b.id','b.company_name')
            ->get();

        return view('reports.customers_outstanding', compact('customers'));
    }

    // Suppliers outstanding report
    public function suppliersOutstanding()
    {
        $suppliers = DB::table('raw_suppliers as s')
            ->select('s.id','s.company_name',
                DB::raw('COALESCE(SUM(pu.total_amount),0) as total_purchase'),
                DB::raw('COALESCE(SUM(pay.amount),0) as total_paid'),
                DB::raw('(COALESCE(SUM(pu.total_amount),0) - COALESCE(SUM(pay.amount),0)) as balance')
            )
            ->leftJoin('purchases as pu','s.id','pu.supplier_id')
            ->leftJoin('payments as pay', function($join){
                $join->on('s.id','=','pay.party_id')->where('pay.type','supplier');
            })
            ->groupBy('s.id','s.company_name')
            ->get();

        return view('reports.suppliers_outstanding', compact('suppliers'));
    }

    // Pending Receivables (payments to receive from customers)
    public function pendingReceivables(Request $request)
    {
        $query = SalesInvoice::with('buyer')
            ->whereRaw('COALESCE(total_amount,0) > COALESCE(paid_amount,0)');

        if ($request->filled('search')) {
            $query->whereHas('buyer', function($q) use ($request) {
                $q->where('company_name','like','%'.$request->search.'%');
            });
        }

        $pendingInvoices = $query->get();

        return view('reports.pending_receivables', compact('pendingInvoices'));
    }

    // Pending Payables (payments to pay to suppliers)
    public function pendingPayables(Request $request)
    {
        $query = Purchase::with('supplier')
            ->whereRaw('COALESCE(total_amount,0) > COALESCE(paid_amount,0)');

        if ($request->filled('search')) {
            $query->whereHas('supplier', function($q) use ($request) {
                $q->where('company_name','like','%'.$request->search.'%');
            });
        }

        $pendingPurchases = $query->get();

        return view('reports.pending_payables', compact('pendingPurchases'));
    }

    // Dashboard
    public function dashboard()
    {
        $totalSales = DB::table('sales_invoices')->sum('total_amount');
        $totalPurchases = DB::table('purchases')->sum('total_amount');
        $totalExpenses = DB::table('expenses')->sum('amount') ?? 0;

        $customerOutstanding = DB::selectOne("
            SELECT (COALESCE(SUM(si.total_amount),0) - COALESCE(SUM(p.amount),0)) as outstanding
            FROM raw_suppliers b
            LEFT JOIN sales_invoices si ON b.id = si.buyer_id
            LEFT JOIN payments p ON b.id = p.party_id AND p.type='customer'
        ")->outstanding ?? 0;

        $supplierOutstanding = DB::selectOne("
            SELECT (COALESCE(SUM(pu.total_amount),0) - COALESCE(SUM(pay.amount),0)) as outstanding
            FROM raw_suppliers s
            LEFT JOIN purchases pu ON s.id = pu.supplier_id
            LEFT JOIN payments pay ON s.id = pay.party_id AND pay.type='supplier'
        ")->outstanding ?? 0;

        $netProfit = $totalSales - $totalPurchases - $totalExpenses;

        return view('dashboard', compact(
            'totalSales',
            'totalPurchases',
            'totalExpenses',
            'customerOutstanding',
            'supplierOutstanding',
            'netProfit'
        ));
    }
}
