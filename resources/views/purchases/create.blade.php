@extends('layouts.app')   {{-- master layout with navbar & sidebar --}}

@section('title','Create Purchase')

@push('styles')
<style>
    body { background:#f5f7fa; }
    .purchase-container {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 6px 25px rgba(0,0,0,0.15);
    }
    .row-item {
        display:flex; gap:10px; margin-bottom:10px;
        align-items:center; background:#f9f9f9;
        padding:5px 10px; border-radius:6px;
    }
    .row-item select, .row-item input { flex:1; }
    .btn-add { margin-bottom:20px; }
    .btn-remove {
        background:#dc3545; color:white; border:none;
        padding:0 10px; border-radius:4px;
    }
    .btn-remove:hover { background:#c82333; }
</style>
@endpush

@section('content')
<div class="purchase-container">
    <h3 class="mb-4">Create New Purchase</h3>

    <form id="purchaseForm">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Purchase Code</label>
                <input type="text" class="form-control" id="purchase_code" placeholder="PUR-001" required>
            </div>
            <div class="col-md-4">
                <label>Supplier</label>
                <select class="form-select" id="supplier_id" required>
                    <option value="">Loading suppliers...</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Purchase Date</label>
                <input type="date" class="form-control" id="purchase_date" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Invoice No</label>
                <input type="text" class="form-control" id="invoice_no" placeholder="INV-001" required>
            </div>
            <div class="col-md-4">
                <label>Invoice Date</label>
                <input type="date" class="form-control" id="invoice_date" required>
            </div>
            <div class="col-md-4">
                <label>Payment Method</label>
                <select class="form-select" id="payment_method" required>
                    <option value="">-- Select --</option>
                    <option value="cash">Cash</option>
                    <option value="bank">Bank</option>
                    <option value="credit">Credit</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Status</label>
                <select class="form-select" id="status" required>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Total Amount</label>
                <input type="number" class="form-control" id="total_amount" placeholder="0.00" readonly>
            </div>
            <div class="col-md-4">
                <label>Description</label>
                <input type="text" class="form-control" id="description" placeholder="Optional">
            </div>
        </div>

        <h5 class="mt-4">Purchase Items</h5>
        <div id="itemsContainer"></div>
        <button type="button" class="btn btn-secondary btn-add" id="addItemBtn">+ Add Item</button>

        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Save Purchase</button>
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let suppliers = [];
let materials = [];

// Load suppliers & materials
Promise.all([
    fetch("{{ url('/api/suppliers') }}").then(res => res.json()),
    fetch("{{ url('/api/raw-materials') }}").then(res => res.json())
])
.then(([supData, matData]) => {
    suppliers = supData;
    materials = matData;
    document.getElementById("supplier_id").innerHTML =
        `<option value="">Select Supplier</option>` +
        suppliers.map(s => `<option value="${s.id}">${s.name}</option>`).join('');
})
.catch(err => console.error(err));

// Add item row
document.getElementById("addItemBtn").addEventListener("click", () => {
    const container = document.getElementById("itemsContainer");
    const row = document.createElement("div");
    row.classList.add("row-item");

    row.innerHTML = `
        <select class="form-select item-material" required>
            <option value="">Select Material</option>
            ${materials.map(m =>
                `<option value="${m.id}" data-unit="${m.unit}" data-price="${m.purchase_price}">
                    ${m.material_name} (${m.material_code})
                 </option>`).join('')}
        </select>
        <input type="number" class="form-control item-qty" placeholder="Qty" min="1" required>
        <input type="text" class="form-control item-unit" placeholder="Unit" readonly>
        <input type="number" class="form-control item-price" placeholder="Price" readonly>
        <button type="button" class="btn-remove">&times;</button>
    `;
    container.appendChild(row);

    const materialSelect = row.querySelector(".item-material");
    const unitInput = row.querySelector(".item-unit");
    const priceInput = row.querySelector(".item-price");
    const qtyInput = row.querySelector(".item-qty");

    materialSelect.addEventListener("change", () => {
        const selected = materialSelect.selectedOptions[0];
        unitInput.value = selected.dataset.unit || '';
        priceInput.value = selected.dataset.price || '';
        calculateTotal();
    });

    qtyInput.addEventListener("input", calculateTotal);
    row.querySelector(".btn-remove").addEventListener("click", () => {
        row.remove();
        calculateTotal();
    });
});

function calculateTotal() {
    let total = 0;
    document.querySelectorAll("#itemsContainer .row-item").forEach(row => {
        const qty = parseFloat(row.querySelector(".item-qty").value) || 0;
        const price = parseFloat(row.querySelector(".item-price").value) || 0;
        total += qty * price;
    });
    document.getElementById("total_amount").value = total.toFixed(2);
}

document.getElementById("purchaseForm").addEventListener("submit", function(e){
    e.preventDefault();
    const items = [];
    document.querySelectorAll("#itemsContainer .row-item").forEach(row => {
        const matId = row.querySelector(".item-material").value;
        const qty = row.querySelector(".item-qty").value;
        const unit = row.querySelector(".item-unit").value;
        const price = row.querySelector(".item-price").value;
        if(matId && qty) items.push({material_id: matId, qty, unit, price});
    });
    if(items.length === 0){
        alert("Add at least one item!");
        return;
    }

    const data = {
        purchase_code: document.getElementById("purchase_code").value,
        supplier_id: document.getElementById("supplier_id").value,
        purchase_date: document.getElementById("purchase_date").value,
        invoice_no: document.getElementById("invoice_no").value,
        invoice_date: document.getElementById("invoice_date").value,
        payment_method: document.getElementById("payment_method").value,
        status: document.getElementById("status").value,
        description: document.getElementById("description").value,
        total_amount: document.getElementById("total_amount").value,
        items: items
    };

    fetch("{{ route('purchases.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(resp => {
        if(resp.success){
            alert(resp.message);
            window.location.href = "{{ route('purchases.index') }}";
        } else if(resp.errors) {
            alert("Validation Error:\n" + Object.values(resp.errors).flat().join("\n"));
        } else {
            alert("Error: " + (resp.message || "Unknown error"));
        }
    })
    .catch(err => alert("Request failed: " + err));
});
</script>
@endpush
