<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\RawStock;
use App\Models\RawStockLog;
use App\Models\Ledger;

class SalesReturnController extends Controller
{
    // Show search form
    public function index() {
        return view('sales_returns.index');
    }

    // Search invoice GET
    public function searchGet(Request $request) {
        $invoice_no = $request->query('invoice_no');
        if (!$invoice_no) return redirect()->route('sales_returns.index');

        $invoice = SalesInvoice::where('invoice_no', $invoice_no)
            ->with(['items.product'])
            ->first();

        if (!$invoice) return redirect()->route('sales_returns.index')
            ->with('error', 'Invoice not found!');

        // Only items with remaining quantity > 0
        $invoice->items = $invoice->items->filter(fn($item) => $item->qty > 0);

        return view('sales_returns.items', compact('invoice'));
    }

    // Search invoice POST
    public function search(Request $request) {
        $request->validate(['invoice_no' => 'required|string']);

        $invoice = SalesInvoice::where('invoice_no', $request->invoice_no)
            ->with(['items.product'])
            ->first();

        if (!$invoice) return back()->with('error', 'Invoice not found!');

        $invoice->items = $invoice->items->filter(fn($item) => $item->qty > 0);

        return view('sales_returns.items', compact('invoice'));
    }

    // Store sales return
    public function store(Request $request, SalesInvoice $invoice) {
        $request->validate([
            'items' => 'required|array|min:1',
            'quantities' => 'required|array',
            'remarks' => 'nullable|string',
        ]);

        DB::transaction(function() use ($request, $invoice) {

            $salesReturn = SalesReturn::create([
                'sales_invoice_id' => $invoice->id,
                'return_date' => now(),
                'total_return_amount' => 0,
                'remarks' => $request->remarks,
            ]);

            $totalReturn = 0;
            $items = SalesInvoiceItem::whereIn('id', $request->items)->get();

            foreach($items as $item) {
                $returnQty = $request->quantities[$item->id] ?? 0;
                $returnQty = min($returnQty, $item->qty);
                if ($returnQty <= 0) continue;

                $subtotal = $item->price * $returnQty;

                // Store return item
                SalesReturnItem::create([
                    'sales_return_id' => $salesReturn->id,
                    'sales_invoice_item_id' => $item->id,
                    'quantity' => $returnQty,
                    'price' => $item->price,
                    'subtotal' => $subtotal,
                ]);

                $totalReturn += $subtotal;

                // Update stock
                $stock = RawStock::firstOrCreate(['rawpro_id' => $item->product_id]);
                $stock->increment('quantity_in', $returnQty);

                // Add log entry
                RawStockLog::create([
                    'rawpro_id' => $item->product_id,
                    'trans_type' => 'in',
                    'qty' => $returnQty,
                    'price' => $item->price,
                    'total_amount' => $subtotal,
                    'remarks' => 'Sales Return, Invoice #'.$invoice->invoice_no,
                    'user_id' => auth()->id(),
                    'trans_date' => now(),
                ]);

                // Reduce invoice remaining qty
                $item->decrement('qty', $returnQty);
            }

            $salesReturn->update(['total_return_amount' => $totalReturn]);

            // Reduce invoice total
            $invoice->decrement('total_amount', $totalReturn);

            // Ledger entry
            Ledger::create([
                'party_id' => $invoice->buyer_id,
                'party_type' => 'buyer',
                'ref_type' => 'sales_return',
                'invoice_no' => $invoice->invoice_no,
                'invoice_date' => now(),
                'description' => 'Sales Return',
                'debit' => $totalReturn,
                'credit' => 0,
            ]);

        });

        return redirect()->back()->with('success', 'Sales return saved successfully!');
    }
}
