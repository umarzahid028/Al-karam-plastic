@extends('layouts.app') {{-- Replace with your main layout --}}

@section('title', 'Products List')

@push('styles')
<style>
.table-hover tbody tr:hover { 
    background:#f1f1f1; 
}
.btn-info { 
    background:#17a2b8; 
    color:white; 
}
.btn-info:hover { 
    background:#138496; 
}
.btn-warning {
    color: #fff;
}
.btn-warning:hover {
    background:#17a2b8; 
    color:white; 
}
</style>
@endpush
@section('content')
<div class="container mt-5 p-4 bg-white rounded shadow-sm" style="max-width:1000px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Products List</h3>
        {{-- <a href="{{ route('products.create') }}" class="btn btn-info">
            + Add New Product
        </a> --}}
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Group</th>
                    <th>Unit</th>
                    <th>Sale Price</th>
                    <th>Cost Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
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
                    <td>
                        <a href="{{ route('products.update', $p->id) }}" class="btn btn-sm btn-info">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-3">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <div class="d-flex justify-content-between mt-3">
        <button class="btn btn-secondary" onclick="window.location.href='/'">Back</button>
    </div>
</div>
@endsection


