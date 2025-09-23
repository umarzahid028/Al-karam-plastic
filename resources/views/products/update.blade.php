@extends('layouts.app') {{-- Replace with your main layout --}}

@section('title', 'Edit Product')

@push('styles')
<style>
body {
    background: #f8f9fa;
}
.card-header {
    color: black;
    border-top-left-radius: 16px;
    border-top-right-radius: 16px;
}
.form-label {
    font-weight: 600;
    color: #495057;
}
.btn-primary {
    background: #0d6efd;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
}
.btn-primary:hover {
    background: #0b5ed7;
}
.btn-secondary {
    border-radius: 8px;
    padding: 10px 20px;
}
.form-control {
    border-radius: 8px;
    box-shadow: none !important;
}
</style>
@endpush
@section('content')
<div class="container mt-5">
    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-white">
            <h4 class="mb-0">Edit Product</h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Product Code</label>
                        <input type="text" name="product_code" class="form-control" value="{{ $product->product_code }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="product_name" class="form-control" value="{{ $product->product_name }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Group</label>
                        <input type="text" name="product_group" class="form-control" value="{{ $product->product_group }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Unit</label>
                        <input type="text" name="unit" class="form-control" value="{{ $product->unit }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Sale Price</label>
                        <input type="number" step="0.01" name="sale_price" class="form-control" value="{{ $product->sale_price }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cost Price</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ $product->cost_price }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Opening Qty</label>
                        <input type="number" name="opening_qty" class="form-control" value="{{ $product->opening_qty }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Opening Price</label>
                        <input type="number" step="0.01" name="opening_price" class="form-control" value="{{ $product->opening_price }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Opening Remarks</label>
                        <input type="text" name="opening_remarks" class="form-control" value="{{ $product->opening_remarks }}">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


