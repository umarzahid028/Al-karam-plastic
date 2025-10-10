@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h4 class="mb-4">Add New Product</h4>

        <form id="productForm" method="POST" action="{{ route('products.store') }}">
            @csrf
        
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Product Code</label>
                    <input type="text" name="product_code" id="product_code" class="form-control" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="product_name" id="product_name" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Group</label>
                    <input type="text" name="product_group" id="product_group" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit</label>
                    <input type="text" name="unit" id="unit" class="form-control" placeholder="kg">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sale Price</label>
                    <input type="number" step="0.01" name="sale_price" id="sale_price" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cost Price</label>
                    <input type="number" step="0.01" name="cost_price" id="cost_price" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Size</label>
                    <input type="text" name="size" id="size" class="form-control" placeholder="10mm">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Packing</label>
                    <input type="text" name="packing_sqr" id="packing_sqr" class="form-control" placeholder="Bundle">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pieces / Bundle</label>
                    <input type="number" name="pieces_per_bundle" id="pieces_per_bundle" class="form-control" value="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Weight</label>
                    <input type="number" step="0.01" name="weight" id="weight" class="form-control">
                </div>
            </div>

            <h6 class="mt-4">Opening Stock (Optional)</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Qty</label>
                    <input type="number" name="opening_qty" id="opening_qty" class="form-control" value="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" name="opening_price" id="opening_price" class="form-control" value="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Remarks</label>
                    <input type="text" name="opening_remarks" id="opening_remarks" class="form-control" placeholder="Opening Stock">
                </div>
            </div>

            <div class="gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Save Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- ✅ SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById("productForm").addEventListener("submit", function(e){
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);

    fetch("{{ route('products.store') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(res => res.json())
    .then(resp => {
        if (resp.success) {
            Swal.fire({
                title: " Success!",
                text: resp.message,
                icon: "success",
                showConfirmButton: false,
                timer: 1800
            });
            form.reset();
        } else {
            Swal.fire({
                title: "❌ Error!",
                text: resp.message || "Something went wrong!",
                icon: "error"
            });
        }
    })
    .catch(err => {
        Swal.fire({
            title: "⚠️ Failed!",
            text: "Request failed: " + err,
            icon: "warning"
        });
    });
});
</script>
@endpush
