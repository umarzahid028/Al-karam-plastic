<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use Illuminate\Http\Request;

class SalesInvoiceController extends Controller
{
    public function index()
    {
        return SalesInvoice::with(['items.product', 'buyer'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'buyer_id' => 'required|exists:raw_suppliers,id',
            'invoice_no' => 'required|unique:sales_invoices,invoice_no',
            'invoice_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $invoice = SalesInvoice::create($request->only([
            'buyer_id', 'invoice_no', 'invoice_date', 'total_amount', 'remarks'
        ]));

        foreach ($request->items as $item) {
            SalesInvoiceItem::create([
                'sales_invoice_id' => $invoice->id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'total' => $item['qty'] * $item['price'],
            ]);
        }

        return $invoice->load(['items.product', 'buyer']);
    }

    public function show($id)
    {
        return SalesInvoice::with(['items.product', 'buyer'])->findOrFail($id);
    }

    public function destroy($id)
    {
        SalesInvoice::destroy($id);
        return response()->json(null, 204);
    }
}
