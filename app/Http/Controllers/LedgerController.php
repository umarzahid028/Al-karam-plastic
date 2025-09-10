<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ledger;
use App\Models\Expense;
use App\Models\SalesInvoice;
use App\Models\RawSupplier;

class LedgerController extends Controller
{
    public function index()
    {
        $entries = Ledger::orderBy('id', 'asc')->paginate(5);

        // Totals
        $sales = Ledger::where('ref_type', 'sale')
                    ->where('credit', '>', 0)
                    ->sum('credit');

        $purchases = Ledger::where('ref_type', 'purchase')
                    ->where('debit', '>', 0)
                    ->sum('debit');

        $expenses = Expense::sum('amount');

        // Net P&L
        $profitOrLoss = $sales - ($purchases + $expenses);

        return view('ledger.index', compact('entries', 'sales', 'purchases', 'expenses', 'profitOrLoss'));
    }

    // =========================
    // Record Sale (Manual Call)
    // =========================
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
            'party_type' => 'user', 
            'ref_type' => 'sale',
            'invoice_no' => $invoiceNo,
            'invoice_date' => $date,
            'description' => 'Sale Revenue',
            'debit' => 0,
            'credit' => $amount,
        ]);
    }

    // =========================
    // Record Sale from Invoice
    // =========================
    public function recordSaleFromInvoice($invoiceId)
    {
        $invoice = SalesInvoice::with('items')->findOrFail($invoiceId);

        $amount = $invoice->total_amount;
        $date = $invoice->invoice_date;
        $customerId = $invoice->buyer_id;
        $invoiceNo = $invoice->invoice_no;

        // Customer Debit (Accounts Receivable)
        Ledger::create([
            'party_id' => $customerId,
            'party_type' => 'customer',
            'ref_type' => 'sale',
            'invoice_no' => $invoiceNo,
            'invoice_date' => $date,
            'description' => 'Sale Invoice #' . $invoiceNo,
            'debit' => $amount,
            'credit' => 0,
        ]);

        // Sales Revenue Credit
        Ledger::create([
            'party_id' => null,
            'party_type' => 'user',
            'ref_type' => 'sale',
            'invoice_no' => $invoiceNo,
            'invoice_date' => $date,
            'description' => 'Revenue from Invoice #' . $invoiceNo,
            'debit' => 0,
            'credit' => $amount,
        ]);
    }

    // =========================
    // Purchase
    // =========================
    public function recordPurchase($supplierId, $invoiceNo, $amount, $date)
    {
        // Inventory / Purchases (Debit)
        Ledger::create([
            'party_id' => null,
            'party_type' => 'user',
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

    // =========================
    // Customer payment
    // =========================
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

    // =========================
    // Supplier payment
    // =========================
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

    // =========================
    // Customer Balance
    // =========================
    public function customerBalance($customerId)
    {
        $debit = Ledger::where('party_id', $customerId)
                    ->where('party_type', 'customer')
                    ->sum('debit');

        $credit = Ledger::where('party_id', $customerId)
                    ->where('party_type', 'customer')
                    ->sum('credit');

        return $debit - $credit; 
        // Positive = customer ne dene hain
        // Negative = customer ko dene hain
    }

    // =========================
    // Supplier Balance
    // =========================
    public function supplierBalance($supplierId)
    {
        $debit = Ledger::where('party_id', $supplierId)
                    ->where('party_type', 'supplier')
                    ->sum('debit');

        $credit = Ledger::where('party_id', $supplierId)
                    ->where('party_type', 'supplier')
                    ->sum('credit');

        return $credit - $debit;
        // Positive = supplier ko dene hain
        // Negative = supplier ne wapis dena hai
    }

    // =========================
    // Show All Balances
    // =========================
    public function balances()
    {
        $customers = RawSupplier::all();
        $suppliers = RawSupplier::all();

        $customerBalances = [];
        foreach ($customers as $c) {
            $customerBalances[$c->id] = $this->customerBalance($c->id);
        }

        $supplierBalances = [];
        foreach ($suppliers as $s) {
            $supplierBalances[$s->id] = $this->supplierBalance($s->id);
        }

        return view('ledger.balances', compact('customers', 'suppliers', 'customerBalances', 'supplierBalances'));
    }
}
