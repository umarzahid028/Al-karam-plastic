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

    public function store(Request $request) {
        $request->validate([
            'purchase_code' => 'required|string|unique:purchases,purchase_code',
            'supplier_id'   => 'required|integer',
            'purchase_date' => 'required|date',
            'invoice_no'    => 'required|string',
            'invoice_date'  => 'required|date',
            'payment_method'=> 'required|string|in:cash,bank,credit',
            'total_amount'  => 'required|numeric|min:0',
            'status'        => 'required|string|in:pending,completed',
            'items'         => 'required|array|min:1',
        ]);

        try {
            DB::transaction(function() use ($request) {

                // 1️⃣ Create Purchase
                $purchase = Purchase::create($request->only([
                    'purchase_code',
                    'supplier_id',
                    'purchase_date',
                    'invoice_no',
                    'invoice_date',
                    'payment_method',
                    'total_amount',
                    'status',
                    'description'
                ]));

                // 2️⃣ Create Purchase Items
                foreach($request->items as $item) {
                    $qty   = isset($item['qty']) ? (float)$item['qty'] : 0;
                    $price = isset($item['price']) ? (float)$item['price'] : 0;

                    PurchaseItem::create([
                        'purchase_id'     => $purchase->id,
                        'raw_material_id' => $item['material_id'],
                        'quantity'        => $qty,
                        'unit_price'      => $price,
                        'total_price'     => $qty * $price,
                    ]);
                }

                // 3️⃣ DOUBLE-ENTRY Ledger

                // Debit: Inventory / Purchases (system account)
                Ledger::create([
                    'party_id'     => 'SYS',  // placeholder for system account
                    'party_type'   => 'user',
                    'ref_type'     => 'purchase',
                    'invoice_no'   => $purchase->invoice_no,
                    'invoice_date' => $purchase->invoice_date,
                    'description'  => 'Purchase ' . $purchase->purchase_code . ' (Inventory Debit)',
                    'debit'        => $purchase->total_amount,
                    'credit'       => 0,
                ]);

                // Credit: Supplier (Accounts Payable)
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

            // Return JSON or redirect
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Purchase saved successfully with items and double-entry ledger!'
                ]);
            }
            return redirect()->route('purchases.index')
                             ->with('success', 'Purchase saved successfully with items and double-entry ledger!');

        } catch (\Exception $e) {
            return $request->wantsJson()
                ? response()->json(['success'=>false, 'message'=>$e->getMessage()])
                : redirect()->back()->with('error', 'Failed to save purchase: ' . $e->getMessage());
        }
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
    
    
    

}
