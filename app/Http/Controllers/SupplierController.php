<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RawSupplier;
use App\Models\Ledger;
class SupplierController extends Controller
{
    // Show create form
    public function create()
    {
        return view('suppliers.create');
    }

    // List all suppliers (for /suppliers)
    public function index()
    {
        $suppliers = RawSupplier::paginate(5);
        return view('suppliers.index', compact('suppliers'));
    }

    // Store supplier via AJAX
    public function store(Request $request)
    {
        $request->validate([
            'supplier_code' => 'required|unique:raw_suppliers,supplier_code',
            'company_name'  => 'required|string|max:255',
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'contact_no'    => 'nullable|string|max:50',
            'opening_balance'=> 'nullable|numeric',
            'status'        => 'required|in:active,inactive,onhold',
        ]);
    
        $supplier = RawSupplier::create([
            'supplier_code'  => $request->supplier_code,
            'company_name'   => $request->company_name,
            'name'           => $request->name,
            'email'          => $request->email,
            'contact_no'     => $request->contact_no,
            'opening_balance'=> $request->opening_balance ?? 0,
            'status'         => $request->status,
        ]);
    
        // ✅ Create Ledger entry for opening balance
        if(($request->opening_balance ?? 0) > 0){
            \App\Models\Ledger::create([
                'party_id'     => $supplier->supplier_code, // e.g., SP-003
                'party_type'   => 'supplier',
                'ref_type'     => 'opening_balance',
                'invoice_no'   => null,
                'invoice_date' => now()->format('Y-m-d'),
                'description'  => 'Opening balance for supplier ' . $supplier->name,
                'debit'        => $request->opening_balance,
                'credit'       => 0,
            ]);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Supplier created successfully',
            'data' => $supplier
        ]);
    }
    
    public function updateStatus(Request $request, $id)
    {
        $supplier = RawSupplier::findOrFail($id);
    
        // Validate request
        $request->validate([
            'status' => 'required|in:active,inactive,on hold',
        ]);
    
        $supplier->status = $request->status;
        $supplier->save();
    
        return response()->json([
            'success' => true,
            'status' => $supplier->status
        ]);
    }
    

}
