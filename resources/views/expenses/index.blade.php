@extends('layouts.app')

@section('title', 'Expenses Report')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background:#f5f7fa;
    
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

#searchInput {
    max-width: 300px;
}
.table-hover tbody tr:hover {
    background:#f9f9f9;
}
.amount {
    font-weight: 600;
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
.amount.negative { color: #e74a3b; }
.amount.positive { color: #1cc88a; }
thead th, tfoot td {
    position: sticky;
    z-index: 3;
}
thead th { top: 0; background: #f8f9fa; }
tfoot { bottom: 0; background: #f8f9fc; font-weight: bold; font-size: 16px; }
.page-header h3 {
       
       letter-spacing: .4px;
       color: #1e293b;
   }
</style>
@endpush

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0 page-header">
            <i class="bi bi-file-earmark-bar-graph me-2"></i> Expenses Report
        </h3>
        
        <a href="{{ route('expenses.create') }}" class="btn btn-info text-white">+ Add Expense</a>
    </div>

    <!-- Search & Filter -->
    <div class="d-flex justify-content-between mb-3">
        <input type="text" id="searchInput" class="form-control w-50" placeholder="Search expense..." onkeyup="filterTable()">

        <select id="typeFilter" class="form-select w-25" onchange="filterTable()">
            <option value="">All Types</option>
            @foreach($expenses->pluck('expense_type')->unique() as $type)
                <option value="{{ strtolower($type) }}">{{ ucfirst($type) }}</option>
            @endforeach
        </select>
    </div>

    <!-- Table -->
    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-bordered table-hover align-middle" id="expensesTable">
            <thead class="table-light">
                <tr>
                    <th style="width: 12%">Expense No</th>
                    <th style="width: 12%">Date</th>
                    <th style="width: 15%">Type</th>
                    <th>Description</th>
                    <th class="text-end" style="width: 15%">Amount (PKR)</th>
                    <th style="width: 10%">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $exp)
                <tr>
                    <td>{{ $exp->expense_no }}</td>
                    <td>{{ \Carbon\Carbon::parse($exp->expense_date)->format('d M, Y') }}</td>
                    <td>{{ $exp->expense_type }}</td>
                    <td>{{ $exp->description }}</td>
                    <td class="text-end amount negative">
                        {{ rtrim(rtrim(number_format($exp->amount, 2), '0'), '.') }}
                    </td>
                    <td>
                        <a href="{{ route('expenses.show', $exp->id) }}" class="btn btn-info text-white">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No expenses recorded</td>
                </tr>
                @endforelse
            </tbody>

            @if($expenses->count())
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end">Total</td>
                    <td class="text-end amount positive">
                        {{ rtrim(rtrim(number_format($total, 2), '0'), '.') }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>

        <div class="d-flex justify-content-end mt-3">
            {{ $expenses->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <div class="d-flex justify-content-start mt-3">
        <a href="/" class="btn btn-secondary">Back</a>
    </div>
</div>

<script>
function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const typeFilter = document.getElementById("typeFilter").value.toLowerCase();
    const rows = document.querySelectorAll("#expensesTable tbody tr");

    rows.forEach(row => {
        const textMatch = Array.from(row.cells).some(cell =>
            cell.textContent.toLowerCase().includes(input)
        );
        const type = row.cells[2]?.textContent.toLowerCase();
        const typeMatch = !typeFilter || type === typeFilter;

        row.style.display = (textMatch && typeMatch) ? "" : "none";
    });
}
</script>
@endsection
