<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerInvoice;
use App\Models\CustomerInvoiceItem;
use App\Models\RawMaterial;
use App\Models\Ledger;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    // Customers list
    public function index()
    {
        $customers = Customer::paginate(5);
        return view('customers.index', compact('customers'));
    }

    // Show form
    public function create()
    {
        return view('customers.create');
    }

    // Store new customer
    public function store(Request $request)
    {
        $request->validate([
            'customer_code'   => 'required|unique:customers,customer_code',
            'name'            => 'required|string|max:255',
            'contact_no'      => 'required|string|max:20',
            'email'           => 'nullable|email',
            'address'         => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
            'status'          => 'required|in:active,inactive,onhold',
        ]);

        $customer = Customer::create($request->all());

        // ✅ Ledger entry for opening balance (Customer = Credit)
        if (($request->opening_balance ?? 0) > 0) {
            Ledger::create([
                'party_id'     => $customer->id,
                'party_type'   => 'customer',
                'ref_type'     => 'opening_balance',
                'invoice_no'   => null,
                'invoice_date' => now()->format('Y-m-d'),
                'description'  => 'Opening balance for customer ' . $customer->name,
                'debit'        => 0,
                'credit'       => $request->opening_balance,
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer created successfully!');
    }

    // ✅ Update status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,onhold',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->status = $request->status;
        $customer->save();

        return response()->json([
            'success' => true,
            'status'  => $customer->status
        ]);
    }

    // ================================
    // ✅ Create Customer Invoice
    // ================================
    public function createInvoice()
    {
        $customers = Customer::all();
        $products  = RawMaterial::all();
        return view('customers.customer_invoice', compact('customers', 'products'));
    }

    public function storeInvoice(Request $request)
    {
        $request->validate([
            'buyer_id'     => 'required|exists:customers,id',
            'invoice_no'   => 'required|string|unique:customer_invoices,invoice_no',
            'invoice_date' => 'required|date',
            'items'        => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:raw_materials,id',
            'items.*.qty'        => 'required|numeric|min:1',
            'items.*.price'      => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $total = collect($request->items)->sum(fn($item) => $item['qty'] * $item['price']);

            // ✅ Save invoice in main table
            $invoice = CustomerInvoice::create([
                'buyer_id'     => $request->buyer_id,
                'invoice_no'   => $request->invoice_no,
                'invoice_date' => $request->invoice_date,
                'total_amount' => $total,
                'remarks'      => $request->remarks,
            ]);

            // ✅ Save items in child table
            foreach ($request->items as $item) {
                CustomerInvoiceItem::create([
                    'customer_invoice_id' => $invoice->id,
                    'product_id'          => $item['product_id'],
                    'qty'                 => $item['qty'],
                    'price'               => $item['price'],
                    'total'               => $item['qty'] * $item['price'],
                ]);
            }

            // ✅ Ledger entries for Customer Invoice
            // 1. Customer owes (Debit)
            Ledger::create([
                'party_id'     => $request->buyer_id,
                'party_type'   => 'customer',
                'ref_type'     => 'sale',
                'invoice_no'   => $request->invoice_no,
                'invoice_date' => $request->invoice_date,
                'description'  => 'Customer Sale Invoice',
                'debit'        => $total,
                'credit'       => 0,
            ]);

            // 2. Sales revenue (Credit)
            Ledger::create([
               'party_id'     => $request->buyer_id,
                'party_type'   => 'user', // system account
                'ref_type'     => 'sale',
                'invoice_no'   => $request->invoice_no,
                'invoice_date' => $request->invoice_date,
                'description'  => 'Sales Revenue',
                'debit'        => 0,
                'credit'       => $total,
            ]);
        });

        return redirect()->route('customers.index')->with('success', 'Invoice created successfully!');
    }
}
