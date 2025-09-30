
@extends('layouts.app')   {{-- uses the sidebar + navbar master layout --}}

@section('title','Add New Raw Material')

@push('styles')
<style>

.container {
    max-width: 700px;
    margin: 50px auto;
}
.card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    padding: 30px;
}
.card h3 {
    margin-bottom: 25px;
    color: #333;
}
.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
}
/* Primary dashboard-style button */
.btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: none;
    color: #fff;
    font-weight: 600;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.btn-primary:hover,
.btn-primary:focus {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(29, 78, 216, 0.35);
}

.btn-primary:active {
    transform: translateY(0);
    box-shadow: 0 3px 8px rgba(29, 78, 216, 0.3);
}

/* Secondary button muted dashboard style */
.btn-secondary {
    background: #64748b;  /* slate gray */
    border: none;
    color: #fff;
    border-radius: 8px;
    transition: background 0.2s ease;
}
.btn-secondary:hover {
    background: #475569;
}

label {
    font-weight: 500;
    color: #555;
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="card">
        <h3>Create New Raw Material</h3>
        <form id="rawMaterialForm">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Material Code</label>
                    <input type="text" class="form-control" id="material_code" placeholder="Enter code" required>
                </div>
                <div class="col-md-6">
                    <label>Material Name</label>
                    <input type="text" class="form-control" id="material_name" placeholder="Enter name" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Unit</label>
                    <input type="text" class="form-control" id="unit" placeholder="e.g., litre" required>
                </div>
                <div class="col-md-4">
                    <label>Packing</label>
                    <input type="text" class="form-control" id="packing" placeholder="e.g., bottle/box">
                </div>
                <div class="col-md-4">
                    <label>Purchase Price</label>
                    <input type="number" class="form-control" id="purchase_price" placeholder="0.00" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Stock</label>
                    <input type="number" class="form-control" id="stocks" placeholder="0" required>
                </div>
                <div class="col-md-6">
                    <label>Store</label>
                    <select id="store_id" class="form-select" required>
                        <option value="">Loading stores...</option>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save Material</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='/raw-material'">
                    Back
                </button>
            </div>
            
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
let stores = [];
// Fetch stores
fetch("/stores-json")
    .then(res => res.json())
    .then(data => {
        const storeSelect = document.getElementById("store_id");
        storeSelect.innerHTML = `<option value="">Select Store</option>` +
            data.map(s => `<option value="${s.id}">${s.store_name}</option>`).join('');
    })
    .catch(err => {
        document.getElementById("store_id").innerHTML = '<option value="">Failed to load stores</option>';
        console.error(err);
    });

// Submit form
document.getElementById("rawMaterialForm").addEventListener("submit", function(e){
    e.preventDefault();

    const data = {
        material_code: document.getElementById("material_code").value,
        material_name: document.getElementById("material_name").value,
        unit: document.getElementById("unit").value,
        packing: document.getElementById("packing").value,
        purchase_price: document.getElementById("purchase_price").value,
        stocks: document.getElementById("stocks").value,
        store_id: document.getElementById("store_id").value
    };

    fetch("/api/raw-material", { 
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(data)
})
.then(res => res.json())
.then(resp => {
    if(resp.success){
        alert(resp.message);
        location.href = "/raw-material/create";
    } else {
        alert("Error: " + resp.message);
    }
})
.catch(err => alert("Request failed: " + err));
});
</script>
@endpush
