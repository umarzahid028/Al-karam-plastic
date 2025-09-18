<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GatePass;
use App\Models\SalesInvoice;
use App\Models\User;
use Illuminate\Support\Str;

class GatePassController extends Controller
{
    // Show all gate passes (index page)
    public function index()
    {
        
        $gatePasses = GatePass::with(['invoice', 'user'])->orderBy('id', 'asc')->paginate(5);
        return view('gate_pass.index', compact('gatePasses'));
    }

    // Show create form
    public function create()
    {
        $invoices = SalesInvoice::all();
        $users = User::all();
        return view('gate_pass.create', compact('invoices', 'users'));
    }

    // Store new gate pass
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:sales_invoices,id',
            'user_id' => 'required|exists:users,id',
            'force_new' => 'nullable|boolean',
        ]);

        $existingPass = GatePass::where('invoice_id', $request->invoice_id)->latest()->first();

        $isDuplicate = false;
        if ($existingPass && !$request->force_new) {
            $isDuplicate = true;
        }

        $invoice = SalesInvoice::find($request->invoice_id);
        $qty = $invoice->items->sum('qty');

        // random alphanumeric pass number
        $passNo = 'GP-' . strtoupper(Str::random(6));

        $gatePass = GatePass::create([
            'gate_pass_no' => $passNo,
            'invoice_id' => $request->invoice_id,
            'user_id' => $request->user_id,
            'gate_name' => '', // optional
            'qty' => $qty,
            'status' => $isDuplicate ? 'DUPLICATE PASS' : 'OK',
        ]);

        return redirect()->route('gatepass.show', $gatePass->id);
    }

    // Show single gate pass
    public function show($id)
    {
        $gatePass = GatePass::with(['user', 'invoice.items.product', 'invoice.buyer'])->findOrFail($id);
        $totalItems = $gatePass->invoice->items->sum('qty');

        return view('gate_pass.show', compact('gatePass', 'totalItems'));
    }
}
