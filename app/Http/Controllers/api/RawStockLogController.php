<?php

namespace App\Http\Controllers;

use App\Models\RawStockLog;
use Illuminate\Http\Request;

class RawStockLogController extends Controller
{
    public function index()
    {
        return response()->json(
            RawStockLog::with(['product', 'user'])->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'rawpro_id'    => 'required|exists:products,id',
            'trans_type'   => 'required|in:in,out',
            'qty'          => 'required|numeric|min:0',
            'price'        => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'remarks'      => 'nullable|string',
            'user_id'      => 'required|exists:users,id',
            'trans_date'   => 'required|date',
        ]);

        $log = RawStockLog::create($request->all());

        return response()->json($log->load(['product', 'user']), 201);
    }

    public function show($id)
    {
        return response()->json(
            RawStockLog::with(['product', 'user'])->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $log = RawStockLog::findOrFail($id);
        $log->update($request->all());

        return response()->json($log);
    }

    public function destroy($id)
    {
        RawStockLog::destroy($id);
        return response()->json(null, 204);
    }
}
