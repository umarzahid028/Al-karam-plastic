@extends('layouts.app')

@section('title', 'Products List')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #f0f2f5;
   
}
.container {
    background: #fff;
    border-radius: 16px;
    padding: 30px;
    max-width: 1100px;
    margin: 50px auto;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}
h3 {
    font-weight: 600;
    color: #333;
}
.table {
    border-radius: 12px;
    overflow: hidden;
}
.table thead {
    background: #f9fafb;
    font-weight: 600;
}
.table-hover tbody tr:hover {
    background: #f1f5f9;
    transition: 0.2s;
}

/* Primary gradient button */
.btn-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: none;
    color: #fff !important;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 15px;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.btn-info:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(29, 78, 216, 0.35);
}

/* Back button */
.btn-secondary {
    background: #64748b;
    border: none;
    color: #fff;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.2s ease;
}
.btn-secondary:hover {
    background: #475569;
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(71, 85, 105, 0.3);
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Products List</h3>
        <a href="{{ route('products.create') }}" class="btn btn-info">
            + Add New Product
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle shadow-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Group</th>
                    <th>Unit</th>
                    <th>Sale Price</th>
                    <th>Cost Price</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                <tr>
                    <td>{{ $products->firstItem() + $loop->index }}</td>
                    <td>{{ $p->product_code }}</td>
                    <td>{{ $p->product_name }}</td>
                    <td>{{ $p->product_group ?? '-' }}</td>
                    <td>{{ $p->unit }}</td>
                    <td>{{ rtrim(rtrim(number_format($p->sale_price, 2), '0'), '.') }}</td>
                    <td>{{ rtrim(rtrim(number_format($p->cost_price, 2), '0'), '.') }}</td>
                    <td>{{ $p->current_stock }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-3">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Back Button -->
    <div class="d-flex justify-content-start mt-3">
        <a href="{{ route('welcome') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection
