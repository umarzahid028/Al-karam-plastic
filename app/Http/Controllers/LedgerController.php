<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ledger;

class LedgerController extends Controller
{
    public function index()
{
    $entries = Ledger::orderBy('id', 'asc')->paginate(5);

    // Totals
    $sales = \App\Models\Ledger::where('ref_type', 'sale')
                ->where('credit', '>', 0)
                ->sum('credit');

    $purchases = \App\Models\Ledger::where('ref_type', 'purchase')
                ->where('debit', '>', 0)
                ->sum('debit');

    // Expenses (assuming you have expenses table)
    $expenses = \App\Models\Expense::sum('amount');

    // Net P&L
    $profitOrLoss = $sales - ($purchases + $expenses);

    return view('ledger.index', compact('entries', 'sales', 'purchases', 'expenses', 'profitOrLoss'));
}

    
    
    // Optional: manual entry form
    // public function create()
    // {
    //     return view('ledger.create');
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'party_id'     => 'required|string',
    //         'party_type'   => 'required|string|in:customer,supplier,user',
    //         'ref_type'     => 'required|string|in:sale,purchase,invoice,payment',
    //         'invoice_no'   => 'nullable|string',
    //         'invoice_date' => 'required|date',
    //         'description'  => 'nullable|string',
    //         'debit'        => 'numeric|min:0',
    //         'credit'       => 'numeric|min:0',
    //     ]);
    
    //     Ledger::create($request->all());
    
    //     return redirect()->route('ledger.index')->with('success', 'Ledger entry added!');
    // }

    // =========================
    // Automatic double-entry
    // =========================

    // Sale
    public function recordSale($customerId, $invoiceNo, $amount, $date)
    {
        // Customer owes (Debit)
        Ledger::create([
            'party_id' => $customerId,
            'party_type' => 'customer',
            'ref_type' => 'sale',
            'invoice_no' => $invoiceNo,
            'invoice_date' => $date,
            'description' => 'Sale Invoice',
            'debit' => $amount,
            'credit' => 0,
        ]);

        // Sales Revenue (Credit)
        Ledger::create([
            'party_id' => null,
            'party_type' => 'user', // system account
            'ref_type' => 'sale',
            'invoice_no' => $invoiceNo,
            'invoice_date' => $date,
            'description' => 'Sale Revenue',
            'debit' => 0,
            'credit' => $amount,
        ]);
    }

    // Purchase
    public function recordPurchase($supplierId, $invoiceNo, $amount, $date)
    {
        // Inventory / Purchases (Debit)
        Ledger::create([
            'party_id' => null,
            'party_type' => 'user', // system account
            'ref_type' => 'purchase',
            'invoice_no' => $invoiceNo,
            'invoice_date' => $date,
            'description' => 'Purchase Invoice',
            'debit' => $amount,
            'credit' => 0,
        ]);

        // Supplier owes (Credit)
        Ledger::create([
            'party_id' => $supplierId,
            'party_type' => 'supplier',
            'ref_type' => 'purchase',
            'invoice_no' => $invoiceNo,
            'invoice_date' => $date,
            'description' => 'Accounts Payable',
            'debit' => 0,
            'credit' => $amount,
        ]);
    }

    // Customer payment
    public function recordCustomerPayment($customerId, $amount, $date, $invoiceNo = null)
    {
        // Cash / Bank (Debit)
        Ledger::create([
            'party_id' => null,
            'party_type' => 'user',
            'ref_type' => 'payment',
            'invoice_no' => $invoiceNo,
            'invoice_date' => $date,
            'description' => 'Payment received',
            'debit' => $amount,
            'credit' => 0,
        ]);

        // Reduce Accounts Receivable (Credit)
        Ledger::create([
            'party_id' => $customerId,
            'party_type' => 'customer',
            'ref_type' => 'payment',
            'invoice_no' => $invoiceNo,
            'invoice_date' => $date,
            'description' => 'Payment applied to invoice',
            'debit' => 0,
            'credit' => $amount,
        ]);
    }

    // Supplier payment
    public function recordSupplierPayment($supplierId, $amount, $date, $invoiceNo = null)
    {
        // Reduce Accounts Payable (Debit)
        Ledger::create([
            'party_id' => $supplierId,
            'party_type' => 'supplier',
            'ref_type' => 'payment',
            'invoice_no' => $invoiceNo,
            'invoice_date' => $date,
            'description' => 'Payment made to supplier',
            'debit' => $amount,
            'credit' => 0,
        ]);

        // Cash / Bank (Credit)
        Ledger::create([
            'party_id' => null,
            'party_type' => 'user',
            'ref_type' => 'payment',
            'invoice_no' => $invoiceNo,
            'invoice_date' => $date,
            'description' => 'Cash outflow',
            'debit' => 0,
            'credit' => $amount,
        ]);
    }
}
