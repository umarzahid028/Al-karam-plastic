@extends('layouts.app')

@section('title', 'Products List')

@push('styles')
<style>
body {
    background: #f0f2f5;
}
.table-hover tbody tr:hover { 
    background: #f9fafb; 
    transition: 0.2s;
}
/* Primary button (Add User, View) */
.btn-info {
    background: linear-gradient(135deg, #3b82f6, #497be6);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 18px;    /* bigger */
    font-size: 15px;       /* slightly larger */
    font-weight: 600;
    transition: all 0.2s ease;
}
.btn-info:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(37,99,235,0.25);
}

/* Secondary button (Back) */
.btn-secondary {
    background: #64748b;
    border: none;
    color: #fff;
    border-radius: 8px;
    padding: 10px 18px;
    font-size: 15px;
    font-weight: 600;
    transition: all 0.2s ease;
}
.btn-secondary:hover {
    background: #475569;
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(71,85,105,0.25);
}

.btn-warning {
    background: #f59e0b;
    border: none;
    color: #fff;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 6px;
}
.btn-warning:hover {
    background: #d97706; 
}
.btn-danger {
    background: #dc2626;
    border: none;
    color: #fff;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 6px;
}
.btn-danger:hover {
    background: #b91c1c;
}
</style>
@endpush

@section('content')
<div class="container mt-5 p-4 bg-white rounded shadow-sm" style="max-width:1100px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Products List</h3>
        <a href="{{ route('products.create') }}" class="btn btn-info text-white">
            + Add New Product
        </a>
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
                    <th class="text-center">Actions</th>
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
                    <td class="text-center d-flex gap-2 justify-content-center">
                        <!-- View -->
                        {{-- <a href="{{ route('products.show', $p->id) }}" class="btn btn-sm btn-warning">View</a> --}}
                        <!-- Edit -->
                        <a href="{{ route('products.update', $p->id) }}" class="btn btn-sm btn-info text-white">Edit</a>
                        <!-- Delete -->
                        <form action="{{ route('products.destroy', $p->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this product?')" 
                              style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
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
