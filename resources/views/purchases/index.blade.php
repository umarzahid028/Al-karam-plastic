@extends('layouts.app')   {{-- uses your master layout with navbar & sidebar --}}

@section('title','Purchases List')

@push('styles')
<style>
    body { background:#f5f7fa; }
    .purchase-container {
        max-width: 1000px;
        margin: 40px auto;
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 6px 25px rgba(0,0,0,0.15);
    }
    .table-hover tbody tr:hover { background-color: #f1f1f1; }
    .btn-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); /
    border: none;
    color: #fff;
    font-weight: 600;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(115, 149, 224, 0.25);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .btn-info:hover { background:#85a8de ; }

    
    #searchInput { 
        margin-bottom: 15px; max-width: 300px;
     }
    @media (max-width: 768px) {
        .table-responsive { overflow-x: auto; }
    }
</style>
@endpush

@section('content')
<div class="purchase-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Purchases</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('purchases.create') }}" class="btn btn-info text-white">+ Add Purchase</a>
            <a href="{{ url('/') }}" class="btn btn-secondary text-white">Back</a>
        </div>
    </div>

    <!-- Search input -->
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search Purchase..." onkeyup="filterTable()">

    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="purchasesTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Purchase Code</th>
                    <th>Supplier</th>
                    <th>Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                <tr>
                    <td>{{ $purchases->firstItem() + $loop->index }}</td>
                    <td>{{ $purchase->purchase_code }}</td>
                    {{-- show supplier name if relationship is set --}}
                    <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                    <td>{{ $purchase->purchase_date }}</td>
                    <td>{{ number_format($purchase->total_amount, 2) }}</td>
                    <td>{{ ucfirst($purchase->status) }}</td>
                    <td>{{ $purchase->description }}</td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-info btn-sm text-white">View</a>
                        <form action="{{ route('purchases.destroy', $purchase->id) }}"
                              method="POST" onsubmit="return confirm('Delete this purchase?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">No purchases found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-3">
            {{ $purchases->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll("#purchasesTable tbody tr");
    rows.forEach(row => {
        row.style.display = Array.from(row.cells).some(cell =>
            cell.textContent.toLowerCase().includes(input)
        ) ? "" : "none";
    });
}
</script>
@endpush
