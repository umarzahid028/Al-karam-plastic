@extends('layouts.app') {{-- apke main layout ka naam app.blade.php ya jo ho --}}

@section('title', 'Add Product')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h4 class="mb-4">Add New Product</h4>

        <form id="productForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Product Code</label>
                    <input type="text" id="product_code" class="form-control" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Product Name</label>
                    <input type="text" id="product_name" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Group</label>
                    <input type="text" id="product_group" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit</label>
                    <input type="text" id="unit" class="form-control" placeholder="kg">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sale Price</label>
                    <input type="number" step="0.01" id="sale_price" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cost Price</label>
                    <input type="number" step="0.01" id="cost_price" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Size</label>
                    <input type="text" id="size" class="form-control" placeholder="10mm">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Packing</label>
                    <input type="text" id="packing_sqr" class="form-control" placeholder="Bundle">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pieces / Bundle</label>
                    <input type="number" id="pieces_per_bundle" class="form-control" value="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Weight</label>
                    <input type="number" step="0.01" id="weight" class="form-control">
                </div>
            </div>

            <h6 class="mt-4">Opening Stock (Optional)</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Qty</label>
                    <input type="number" id="opening_qty" class="form-control" value="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" id="opening_price" class="form-control" value="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Remarks</label>
                    <input type="text" id="opening_remarks" class="form-control" placeholder="Opening Stock">
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">Save Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById("productForm").addEventListener("submit", function(e){
    e.preventDefault();

    const data = {
        product_code: document.getElementById("product_code").value,
        product_name: document.getElementById("product_name").value,
        product_group: document.getElementById("product_group").value,
        unit: document.getElementById("unit").value,
        sale_price: parseFloat(document.getElementById("sale_price").value)||0,
        cost_price: parseFloat(document.getElementById("cost_price").value)||0,
        size: document.getElementById("size").value,
        packing_sqr: document.getElementById("packing_sqr").value,
        pieces_per_bundle: parseInt(document.getElementById("pieces_per_bundle").value)||0,
        weight: parseFloat(document.getElementById("weight").value)||0,
        opening_qty: parseInt(document.getElementById("opening_qty").value)||0,
        opening_price: parseFloat(document.getElementById("opening_price").value)||0,
        opening_remarks: document.getElementById("opening_remarks").value
    };

    fetch("{{ url('/api/products') }}", {
        method:"POST",
        headers:{
            "Content-Type":"application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(resp => {
        if(resp.success){
            alert("Product added successfully!");
            document.getElementById("productForm").reset();
        }else{
            alert("Error: "+resp.message);
        }
    })
    .catch(err => alert("Request failed: "+err));
});
</script>
@endsection
