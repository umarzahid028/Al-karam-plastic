<?php

namespace App\Http\Controllers;
use App\Models\LedgerEntry;
use Illuminate\Http\Request;

class LedgerEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(LedgerEntry::all());
    }


    /**
     * Store a newly created resource in storage.
     */
    
    /**
     * Display the specified resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'party_id'      => 'required|string',
            'party_type'    => 'required|string',
            'ref_type'      => 'required|string',
            'invoice_no'    => 'nullable|string',
            'invoice_date'  => 'nullable|date',
            'description'   => 'nullable|string',
            'debit'         => 'nullable|numeric',
            'credit'        => 'nullable|numeric',
        ]);

        $ledger = LedgerEntry::create($request->all());
        return response()->json($ledger, 201);
    }
    public function show($id)
    {
        return response()->json(LedgerEntry::findOrFail($id));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ledger = LedgerEntry::findOrFail($id);
        $ledger->update($request->all());
        return response()->json($ledger);
    }


    /**
     * Remove the specified resource from storage.
     */
   

     public function destroy($id)
     {
         LedgerEntry::destroy($id);
         return response()->json(null, 204);
     }
}
