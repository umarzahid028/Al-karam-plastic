@extends('layouts.app')   {{-- uses the sidebar + navbar master layout --}}

@section('title','Create Raw Material Issue')

@push('styles')
<style>
body { font-family: Arial, sans-serif; background:#f5f7fa; }
.issue-container {
    max-width: 950px;
    margin: 0 auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.15);
}
.row-material { 
    display: flex; gap: 10px; margin-bottom: 10px; 
    align-items: center; background:#f9f9f9; padding:5px 10px; border-radius:6px;
}
.row-material select, .row-material input { flex:1; }
.btn-add { margin-bottom: 20px; }
.btn-remove {
    background:#dc3545; color:white; border:none;
    padding:0 10px; border-radius:4px;
}
.btn-remove:hover { background:#c82333; }
</style>
@endpush

@section('content')
<div class="issue-container mt-5">
    <h3 class="mb-4">Create Raw Material Issue</h3>

    <form action="{{ route('raw_materials.issue.store') }}" method="POST">
        @csrf

        {{-- Header fields --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Issue No:</label>
                <input type="text" name="issue_no" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Issue Date:</label>
                <input type="date" name="issue_date" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Issued By:</label>
                <input type="text" name="issued_by" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Issued To:</label>
                <input type="text" name="issued_to" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>Remarks:</label>
                <input type="text" name="remarks" class="form-control" placeholder="Optional">
            </div>
        </div>

        {{-- Materials --}}
        <h5 class="mt-4">Materials to Issue</h5>
        <div id="materialsContainer"></div>
        <button type="button" id="addMaterialBtn" class="btn btn-secondary btn-add">+ Add Material</button>

        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Save Issue</button>
            <a href="{{ route('raw_materials.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let rawMaterials = @json($rawMaterials ?? []);
let stores = @json($stores ?? []);

document.getElementById("addMaterialBtn").addEventListener("click", () => {
    const container = document.getElementById("materialsContainer");
    const rowCount = container.children.length;
    const row = document.createElement("div");
    row.classList.add("row-material");

    const storeOptions = stores.map(s => `<option value="${s.id}">${s.store_name}</option>`).join('');

    row.innerHTML = `
        <select name="items[${rowCount}][rawpro_id]" class="form-select material-select" required>
            <option value="">Select Material</option>
            ${rawMaterials.map(m => `<option value="${m.id}" data-unit="${m.unit}" data-packing="${m.packing}" data-store="${m.store_id}">${m.material_name} (${m.material_code})</option>`).join('')}
        </select>
        <input type="number" name="items[${rowCount}][qty]" class="form-control" placeholder="Qty" required min="1">
        <input type="text" name="items[${rowCount}][unit]" class="form-control material-unit" placeholder="Unit">
        <input type="text" name="items[${rowCount}][packing]" class="form-control material-packing" placeholder="Packing">
        <select name="items[${rowCount}][store_id]" class="form-select material-store">
            <option value="">Select Store</option>
            ${storeOptions}
        </select>
        <button type="button" class="btn-remove">&times;</button>
    `;
    container.appendChild(row);

    const materialSelect = row.querySelector(".material-select");
    const unitInput = row.querySelector(".material-unit");
    const packingInput = row.querySelector(".material-packing");
    const storeSelect = row.querySelector(".material-store");

    materialSelect.addEventListener("change", () => {
        const selectedOption = materialSelect.selectedOptions[0];
        unitInput.value = selectedOption.dataset.unit || '';
        packingInput.value = selectedOption.dataset.packing || '';
        storeSelect.value = selectedOption.dataset.store || '';
    });

    row.querySelector(".btn-remove").addEventListener("click", () => row.remove());
});
</script>
@endpush
