@extends('layouts.app')

@section('title', 'Products List')

@push('styles')
<style>
    .product-container {
        max-width: 1100px;
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .page-header h3 {
        letter-spacing: .4px;
        color: #1e293b;
    }
    .btn-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .btn-info:hover,
    .btn-info:focus {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    }
    #searchInput {
        margin-bottom: 15px;
        max-width: 320px;
    }
    .table thead th {
        background: #f1f5f9;
        color: #334155;
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
    }
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }
    .btn-danger {
        background-color: #dc2626;
        border: none;
    }
    .btn-danger:hover {
        background-color: #b91c1c;
    }
</style>
@endpush

@section('content')
<div class="product-container">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-box-seam me-2"></i> Products List</h3>
        <a href="{{ route('products.create') }}" class="btn btn-info text-white">
            + Add New Product
        </a>
    </div>

    <input type="text"
           id="searchInput"
           class="form-control"
           placeholder="Search product..."
           onkeyup="filterTable()">

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover align-middle" id="productsTable">
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
                    {{-- <th class="text-center">Actions</th> --}}
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
                        {{-- <td class="text-center">
                            <form action="{{ route('products.destroy', $p->id) }}"
                                  method="POST"
                                  class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-btn">
                                    Delete
                                </button>
                            </form>
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>

    <div class="d-flex justify-content-start mt-3">
        <a href="{{ route('welcome') }}" class="btn btn-secondary">
            Back
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    document.querySelectorAll("#productsTable tbody tr").forEach(row => {
        const match = Array.from(row.cells).some(cell =>
            cell.textContent.toLowerCase().includes(input)
        );
        row.style.display = match ? "" : "none";
    });
}

// SweetAlert delete confirmation
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This product will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});

// Flash messages
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: '{{ session('success') }}',
    timer: 2000,
    showConfirmButton: false
});
@endif

@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '{{ session('error') }}',
});
@endif
</script>
@endpush
