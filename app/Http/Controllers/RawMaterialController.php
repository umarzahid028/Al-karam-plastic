<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RawMaterial;
use App\Models\RawMaterialIssue;
use App\Models\RawMaterialIssueItem;

class RawMaterialController extends Controller
{
   
    public function index() {
        // Raw materials ko 10 per page paginate karein
        $entries = RawMaterial::paginate(5);
    
        return view('raw-materials.index', compact('entries'));
    }
    
    public function list() {
        $materials = RawMaterial::all();
        return response()->json($materials);
    }

    public function createIssue() {
        return view('raw-materials.create'); // Form to issue raw materials
    }
   
    
    public function createIssues() {
        $rawMaterials = \App\Models\RawMaterial::all();
        $stores = \App\Models\Store::all();
        return view('raw-materials.create-rawissues', compact('rawMaterials', 'stores'));
    }
    
    public function storeIssue(Request $request) {
        // Validate request
        $request->validate([
            'issue_no' => 'required|string',
            'issue_date' => 'required|date',
            'issued_by' => 'required|string',
            'issued_to' => 'required|string',
            'remarks' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.rawpro_id' => 'required|exists:raw_materials,id',
            'items.*.qty' => 'required|numeric|min:1',
        ]);
    
        $issue = \App\Models\RawMaterialIssue::create([
            'issue_no' => $request->issue_no,
            'issue_date' => $request->issue_date,
            'issued_by' => $request->issued_by,
            'issued_to' => $request->issued_to,
            'approved_by' => auth()->user()->name ?? 'manager', // or whatever default
            'remarks' => $request->remarks,
        ]);
    
        foreach ($request->items as $item) {
            \App\Models\RawMaterialIssueItem::create([
                'issue_id' => $issue->id,
                'rawpro_id' => $item['rawpro_id'],
                'qty' => $item['qty'],
                'unit' => $item['unit'] ?? '',
                'packing' => $item['packing'] ?? '',
                'store_id' => $item['store_id'] ?? null,
            ]);
    
            // Update stock
            $material = \App\Models\RawMaterial::find($item['rawpro_id']);
            if($material) {
                $material->stocks -= $item['qty'];
                $material->save();
            }
        }
    
        return redirect()->route('raw_materials.index')->with('success', 'Raw Material Issue saved successfully!');
    }
    
    
    
    public function showIssues(Request $request)
{
    $materialId = $request->query('material_id');

    // Load material with store
    $material = RawMaterial::with('store')->findOrFail($materialId);

    // Load related issue items
    $issueItems = RawMaterialIssueItem::where('rawpro_id', $materialId)
                        ->with('issue')
                        ->get();

    return view('raw-materials.raw-detail', compact('material', 'issueItems'));
}
public function store(Request $request) {
    $material = RawMaterial::create($request->only([
        'material_code','material_name','unit','packing','purchase_price','stocks','store_id'
    ]));

    return response()->json(['success'=>true,'message'=>'Raw Material saved!']);
}
public function destroy($id)
{
    $material = RawMaterial::findOrFail($id);

    // Check if there are linked purchase records
    if ($material->purchaseItems()->exists()) {
        return redirect()->route('raw_materials.index')
            ->with('error', 'Cannot delete — material is linked with purchase records.');
    }

    $material->delete();

    return redirect()->route('raw_materials.index')
        ->with('success', 'Raw material deleted successfully!');
}


    
}
