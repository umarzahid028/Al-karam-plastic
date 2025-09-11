<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;

class PurchaseReturnController extends Controller
{
    // Show search form
    public function index()
    {
        return view('purchase_returns.index');
    }

    // Search purchase by invoice number (GET)
    public function searchGet(Request $request)
    {
        $invoice_no = $request->query('invoice_no');
        if (!$invoice_no) return redirect()->route('purchase_returns.index');

        $purchase = Purchase::where('invoice_no', $invoice_no)
            ->with(['items.rawMaterial', 'returns.items'])
            ->first();

        if (!$purchase) return redirect()->route('purchase_returns.index')
            ->with('error', 'Invoice not found!');

        // Show only items with remaining quantity > 0
        $purchase->items = $purchase->items->filter(fn($item) => $item->quantity > 0);

        return view('purchase_returns.items', compact('purchase'));
    }

    // Search purchase by invoice number (POST)
    public function search(Request $request)
    {
        $request->validate(['invoice_no' => 'required|string']);

        $purchase = Purchase::where('invoice_no', $request->invoice_no)
            ->with(['items.rawMaterial', 'returns.items'])
            ->first();

        if (!$purchase) return back()->with('error', 'Invoice not found!');

        $purchase->items = $purchase->items->filter(fn($item) => $item->quantity > 0);

        return view('purchase_returns.items', compact('purchase'));
    }

    // Store purchase return
    public function store(Request $request, Purchase $purchase)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'quantities' => 'required|array',
            'remarks' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $purchase) {

            $return = PurchaseReturn::create([
                'purchase_id' => $purchase->id,
                'return_date' => now(),
                'total_return_amount' => 0,
                'remarks' => $request->remarks,
            ]);

            $totalReturn = 0;
            $items = PurchaseItem::whereIn('id', $request->items)->get();

            foreach ($items as $pItem) {
                $returnQty = $request->quantities[$pItem->id] ?? 0;
                $returnQty = min($returnQty, $pItem->quantity);

                if ($returnQty <= 0) continue;

                $unitPrice = $pItem->unit_price ?? ($pItem->total_price / max($pItem->quantity,1));
                $subtotal = $unitPrice * $returnQty;

                PurchaseReturnItem::create([
                    'purchase_return_id' => $return->id,
                    'purchase_item_id' => $pItem->id,
                    'quantity' => $returnQty,
                    'price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                $totalReturn += $subtotal;

                // Reduce remaining quantity for future returns
                $pItem->decrement('quantity', $returnQty);
            }

            $return->update(['total_return_amount' => $totalReturn]);

            $purchase->decrement('total_amount', $totalReturn);

            \App\Models\Ledger::create([
                'party_id' => $purchase->supplier_id,
                'party_type' => 'supplier',
                'ref_type' => 'purchase_return',
                'invoice_no' => $purchase->invoice_no,
                'invoice_date' => now(),
                'description' => 'Purchase Return',
                'debit' => 0,
                'credit' => $totalReturn,
            ]);
        });

        return redirect()->back()->with('success', 'Return saved successfully!');
    }
}
