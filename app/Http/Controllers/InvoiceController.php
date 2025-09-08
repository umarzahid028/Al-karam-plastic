<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\RawSupplier; 
use App\Models\User;        
use App\Models\RawStock;
use App\Models\RawStockLog;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\Ledger;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function create()
    {
        $buyers = RawSupplier::select('id', 'company_name')->orderBy('company_name')->get();
        $salespersons = User::select('id', 'name')->orderBy('name')->get();
    
        $year = date('y'); // last two digits of year, e.g., 25

        // Let user input the invoice prefix/number
        $userInput = ''; // you can fill this from a form field if needed
        
        // Final invoice: user input + '-' + year
        $invoiceNo = $userInput . '-' . $year;
       
    
        return view('sale-invoice', compact('buyers', 'salespersons', 'invoiceNo'));
    }
    public function search(Request $request)
    {
        $query = $request->get('q');
        $type  = $request->get('type'); // id, name, group
    
        $products = \DB::table('products');
    
        if ($type === 'id') {
            $products = $products->where('product_code', 'like', "%$query%");
        } elseif ($type === 'name') {
            $products = $products->where('product_name', 'like', "%$query%");
        } elseif ($type === 'group') {
            $products = $products->where('product_group', 'like', "%$query%");
        }
    
        $products = $products->limit(10)->get([
            'id',
            'product_code',
            'product_name',
            'product_group',
            'unit',
            'sale_price',
            'cost_price',
            'size',
            'packing_sqr',
            'pieces_per_bundle',
            'weight'
        ]);
    
        return response()->json($products);
    }
    
public function getBalance($id)
{
    $buyer = \App\Models\RawSupplier::find($id);

    if (!$buyer) {
        return response()->json(['success' => false, 'balance' => 0]);
    }

    return response()->json([
        'success' => true,
        'balance' => $buyer->opening_balance
    ]);
}
public function store(Request $request)
{
    DB::beginTransaction();
    try {
        // 1️⃣ Save invoice
        $invoice = SalesInvoice::create([
            'buyer_id'     => $request->buyer_id,
            'invoice_no'   => $request->invoice_no,
            'invoice_date' => $request->invoice_date,
            'total_amount' => $request->total_amount,
            'remarks'      => $request->remarks ?? null,
        ]);

        // 2️⃣ Save invoice items & update stock
        foreach ($request->items as $item) {
            $productId = is_numeric($item['product_id']) 
                ? $item['product_id'] 
                : optional(Product::where('product_code', $item['product_id'])->first())->id;

            SalesInvoiceItem::create([
                'sales_invoice_id' => $invoice->id,
                'product_id'       => $productId,
                'qty'              => $item['qty'],
                'price'            => $item['price'],
                'total'            => $item['total'],
            ]);

            // Stock update
            $stock = RawStock::firstOrCreate(
                ['rawpro_id' => $productId],
                ['quantity_in' => 0, 'quantity_out' => 0]
            );

            $stock->quantity_out += $item['qty'];
            $stock->save();

            RawStockLog::create([
                'rawpro_id'    => $productId,
                'trans_type'   => 'out',
                'qty'          => $item['qty'],
                'price'        => $item['price'],
                'total_amount' => $item['total'],
                'remarks'      => 'Sale Invoice #' . $invoice->invoice_no,
                'user_id'      => auth()->id() ?? 1,
                'trans_date'   => $request->invoice_date,
            ]);
        }

        // 3️⃣ DOUBLE-ENTRY LEDGER

        // Debit: Customer owes (Accounts Receivable)
        Ledger::create([
            'party_id'     => $invoice->buyer_id,
            'party_type'   => 'supplier', // or 'customer' if you rename
            'ref_type'     => 'sale',
            'invoice_no'   => $invoice->invoice_no,
            'invoice_date' => $invoice->invoice_date,
            'description'  => 'Sale Invoice #' . $invoice->invoice_no,
            'debit'        => $invoice->total_amount,
            'credit'       => 0,
        ]);

        // Credit: Revenue (system account)
        Ledger::create([
            'party_id'     => 'SYS',  // system account
            'party_type'   => 'user',
            'ref_type'     => 'sale',
            'invoice_no'   => $invoice->invoice_no,
            'invoice_date' => $invoice->invoice_date,
            'description'  => 'Revenue from Sale Invoice #' . $invoice->invoice_no,
            'debit'        => 0,
            'credit'       => $invoice->total_amount,
        ]);

        DB::commit();
        return response()->json(['success' => true, 'id' => $invoice->id]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

}