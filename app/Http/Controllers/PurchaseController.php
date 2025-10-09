<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\RawSupplier;
use App\Models\Ledger;
use App\Models\RawMaterial;

class PurchaseController extends Controller
{
    public function index() {
        // $entries = Purchase:paginate(5);
    
        $purchases = Purchase::paginate(5);
        return view('purchases.index', compact('purchases'));
    }

    public function create() {
        $suppliers = RawSupplier::all();
        $materials = RawMaterial::all();
        return view('purchases.create', compact('suppliers', 'materials'));
    }


    public function suppliers() {
        return response()->json(RawSupplier::all());
    }

    public function materials() {
        return response()->json(RawMaterial::all());
    }
    public function show(Purchase $purchase)
    {
        $items = $purchase->items()->with('material')->get();
        return view('purchases.show', compact('purchase', 'items'));
    }
    public function store(Request $request)
{


    // Validate
    $validated = $request->validate([
        'purchase_code' => 'required|string|unique:purchases,purchase_code',
        'supplier_id'   => 'required|integer',
        'purchase_date' => 'required|date',
          'invoice_no' => 'required|string',
        'invoice_date'  => 'required|date',
        'payment_method'=> 'required|string|in:cash,bank,credit',
        'total_amount'  => 'required|numeric|min:0',
        'status'        => 'required|string|in:pending,completed',
        'description'   => 'nullable|string',
        'items'         => 'required|array|min:1',
        'items.*.material_id' => 'required|integer|exists:raw_materials,id',
        'items.*.qty'         => 'required|numeric|min:1',
        'items.*.unit'        => 'nullable|string',
        'items.*.price'       => 'required|numeric|min:0',
    ]);

   
    try {
        DB::transaction(function () use ($validated) {
            $purchase = Purchase::create([
                'purchase_code'   => $validated['purchase_code'],
                'supplier_id'     => $validated['supplier_id'],
                'purchase_date'   => $validated['purchase_date'],
                'invoice_no'      => $validated['invoice_no'],
                'invoice_date'    => $validated['invoice_date'],
                'payment_method'  => $validated['payment_method'],
                'total_amount'    => $validated['total_amount'],
                'status'          => $validated['status'],
                'description'     => $validated['description'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                PurchaseItem::create([
                    'purchase_id'     => $purchase->id,
                    'raw_material_id' => $item['material_id'],
                    'quantity'        => $item['qty'],
                    'unit_price'      => $item['price'],
                    'total_price'     => $item['qty'] * $item['price'],
                ]);
            }

            Ledger::create([
                'party_id'     => 'SYS',
                'party_type'   => 'user',
                'ref_type'     => 'purchase',
                'invoice_no'   => $purchase->invoice_no,
                'invoice_date' => $purchase->invoice_date,
                'description'  => 'Purchase ' . $purchase->purchase_code . ' (Inventory Debit)',
                'debit'        => $purchase->total_amount,
                'credit'       => 0,
            ]);

            Ledger::create([
                'party_id'     => 'SP-' . str_pad($purchase->supplier_id, 3, '0', STR_PAD_LEFT),
                'party_type'   => 'supplier',
                'ref_type'     => 'purchase',
                'invoice_no'   => $purchase->invoice_no,
                'invoice_date' => $purchase->invoice_date,
                'description'  => 'Purchase ' . $purchase->purchase_code . ' (Accounts Payable)',
                'debit'        => 0,
                'credit'       => $purchase->total_amount,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Purchase saved successfully with items and ledger!',
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ]);
    }
}

}    