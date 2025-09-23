@extends('layouts.app')

@section('title', 'Gate Passes')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background:#f5f7fa;
    font-family: Arial, sans-serif;
}
.container {
    max-width: 1100px;
    margin: 50px auto;
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.1);
}
h3 {
    font-weight: 600;
    color: #2c3e50;
}
.btn-add {
    background: #17a2b8;
    color:white;
    border-radius:6px;
    padding: 8px 18px;
}
.btn-add:hover {
    background: #138496;
}
#searchInput {
    max-width: 300px;
}
thead th {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 2;
}
.table-hover tbody tr:hover {
    background:#f9f9f9;
}
.badge-duplicate {
    background: #17a2b8;
    color: white;
    font-size: 0.65rem;
    margin-left: 5px;
}
.btn-back {
    background: #17a2b8;
    color: white;
}
.btn-back:hover {
    background: #138496;
    color: white;
}
tfoot {
    background: #f8f9fc;
    font-weight: bold;
    font-size: 16px;
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">Gate Passes</h3>
        <button class="btn btn-add" onclick="window.location.href='{{ route('gatepass.create') }}'">
            + Generate Pass
        </button>
    </div>

    <!-- Search -->
    <div class="d-flex justify-content-start mb-3">
        <input type="text" id="searchInput" class="form-control w-50" placeholder="Search Gate Pass..." onkeyup="filterTable()">
    </div>

    <!-- Table -->
    <div class="table-responsive" style="max-height: 550px; overflow-y: auto;">
        <table class="table table-bordered table-hover align-middle" id="passesTable">
            <thead class="table-light">
                <tr>
                    <th style="width:10%">#</th>
                    <th style="width:12%">Pass No</th>
                    <th style="width:12%">Invoice No</th>
                    <th>User</th>
                    <th>Total Items</th>
                    <th style="width:12%">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gatePasses as $pass)
                    <tr>
                        <td>{{ $gatePasses->firstItem() + $loop->index }}</td>
                        <td>{{ $pass->gate_pass_no }}
                            @if($pass->status === 'DUPLICATE PASS')
                                <span class="badge badge-duplicate">DUPLICATE</span>
                            @endif
                        </td>
                        <td>{{ $pass->invoice->invoice_no ?? $pass->invoice_id }}</td>
                        <td>{{ $pass->user->name ?? '-' }}</td>
                        <td>{{ $pass->qty }}</td>
                        <td>
                            <a href="{{ route('gatepass.show', $pass->id) }}" class="btn btn-sm btn-back">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No gate passes generated</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <button class="btn btn-secondary" onclick="window.location.href='/'">Back</button>
            <div>
                {{ $gatePasses->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll("#passesTable tbody tr");
    rows.forEach(row => {
        const match = Array.from(row.cells).some(cell =>
            cell.textContent.toLowerCase().includes(input)
        );
        row.style.display = match ? "" : "none";
    });
}
</script>
@endpush
