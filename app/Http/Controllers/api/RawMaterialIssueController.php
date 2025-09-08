<?php
namespace App\Http\Controllers;

use App\Models\RawMaterialIssue;
use App\Models\RawMaterialIssueItem;
use Illuminate\Http\Request;

class RawMaterialIssueController extends Controller
{
    public function index()
    {
        return response()->json(RawMaterialIssue::with('items')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'issue_no'    => 'required|string|unique:raw_material_issues,issue_no',
            'issue_date'  => 'required|date',
            'issued_by'   => 'required|string',
            'issued_to'   => 'required|string',
            'approved_by' => 'nullable|string',
            'remarks'     => 'nullable|string',
            'items'       => 'required|array|min:1',
          'items.*.rawpro_id' => 'required|exists:raw_materials,material_code',
            'items.*.qty'       => 'required|numeric',
            'items.*.unit'      => 'required|string',
        ]);

        $issue = RawMaterialIssue::create($request->only([
            'issue_no', 'issue_date', 'issued_by', 'issued_to', 'approved_by', 'remarks'
        ]));
        foreach ($request->items as $item) {
            // pehle material_code se id nikaal lo
            $rawMaterialId = \App\Models\RawMaterial::where('material_code', $item['rawpro_id'])->value('id');
        
            $issue->items()->create([
                'rawpro_id' => $rawMaterialId,
                'qty'       => $item['qty'],
                'unit'      => $item['unit'],
            ]);
        }
        

        return response()->json($issue->load('items'), 201);
    }

    public function show($id)
    {
        return response()->json(RawMaterialIssue::with('items')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $issue = RawMaterialIssue::findOrFail($id);
        $issue->update($request->all());
        return response()->json($issue);
    }

    public function destroy($id)
    {
        RawMaterialIssue::destroy($id);
        return response()->json(null, 204);
    }
}
