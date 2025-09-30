@extends('layouts.app')

@section('title', 'Ledger Entries')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #f5f7fa;
    
}
.container {
    max-width: 1200px;
    margin: 40px auto;
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}
thead th {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 1;
}
.table-hover tbody tr:hover {
    background: #f9f9f9;
}
.text-end {
    text-align: right;
}
.table-bordered td, .table-bordered th {
    vertical-align: middle;
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">Ledger Entries</h3>
    </div>

    <!-- Filters -->
    <div class="d-flex gap-2 mb-3 flex-wrap">
        <input type="text" id="searchInput" class="form-control w-50" placeholder="Search by description, invoice no, etc..." onkeyup="filterTable()">
        <select id="partyFilter" class="form-select w-25" onchange="filterTable()">
            <option value="">All Party Types</option>
            <option value="customer">Customer</option>
            <option value="supplier">Supplier</option>
            <option value="user">User</option>
        </select>
        <input type="date" id="dateFilter" class="form-control w-25" onchange="filterTable()">
    </div>

    <!-- Ledger Table -->
    <div class="table-responsive" style="max-height: 550px; overflow-y: auto;">
        <table class="table table-bordered table-hover align-middle" id="ledgerTable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Party ID</th>
                    <th>Party Type</th>
                    <th>Reference Type</th>
                    <th>Invoice No</th>
                    <th>Invoice Date</th>
                    <th>Description</th>
                    <th class="text-end">Debit</th>
                    <th class="text-end">Credit</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entries as $e)
                    <tr>
                        <td>{{ $e->id }}</td>
                        <td>{{ $e->party_id }}</td>
                        <td>{{ ucfirst($e->party_type) }}</td>
                        <td>{{ ucfirst($e->ref_type) }}</td>
                        <td>{{ $e->invoice_no ?? '-' }}</td>
                        <td>{{ $e->invoice_date }}</td>
                        <td>{{ $e->description ?? '-' }}</td>
                        <td class="text-end">{{ rtrim(rtrim(number_format($e->debit, 2), '0'), '.') }}</td>
                        <td class="text-end">{{ rtrim(rtrim(number_format($e->credit, 2), '0'), '.') }}</td>
                        <td>{{ $e->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-3">
        {{ $entries->links('pagination::bootstrap-5') }}
    </div>

    <!-- Profit & Loss Summary -->
    <div class="mb-4 mt-4">
        <h4>Profit & Loss Summary</h4>
        <table class="table table-bordered w-50">
            <tr>
                <th>Total Sales</th>
                <td>{{ rtrim(rtrim(number_format($sales, 2), '0'), '.') }}</td>
            </tr>
            <tr>
                <th>Total Purchases</th>
                <td>{{ rtrim(rtrim(number_format($purchases, 2), '0'), '.') }}</td>
            </tr>
            <tr>
                <th>Total Expenses</th>
                <td>{{ rtrim(rtrim(number_format($expenses, 2), '0'), '.') }}</td>
            </tr>
            <tr>
                <th>Net Profit / Loss</th>
                <td>
                    @if($profitOrLoss >= 0)
                        <span class="text-success">Profit: {{ rtrim(rtrim(number_format($profitOrLoss, 2), '0'), '.') }}</span>
                    @else
                        <span class="text-danger">Loss: {{ rtrim(rtrim(number_format($profitOrLoss, 2), '0'), '.') }}</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="mt-3">
        <a href="/" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterTable() {
    const search = document.getElementById("searchInput").value.toLowerCase();
    const partyType = document.getElementById("partyFilter").value.toLowerCase();
    const date = document.getElementById("dateFilter").value;

    const rows = document.querySelectorAll("#ledgerTable tbody tr");

    rows.forEach(row => {
        const textMatch = Array.from(row.cells).some(cell => cell.textContent.toLowerCase().includes(search));
        const partyMatch = !partyType || row.cells[2].textContent.toLowerCase() === partyType;
        const dateMatch = !date || row.cells[5].textContent === date;

        row.style.display = (textMatch && partyMatch && dateMatch) ? "" : "none";
    });
}
</script>
@endpush
