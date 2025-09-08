<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expenses Report</title>
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
            background: #17a2b8;
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
        .amount {
            font-weight: 600;
        }
        .amount.negative {
            color: #e74a3b; /* red */
        }
        .amount.positive {
            color: #1cc88a; /* green */
        }
        .btn-back{
            background: #17a2b8;
            color: white;
        }
        tfoot {
            background: #f8f9fc;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">Expenses Report</h3>
        <button class="btn btn-add" onclick="window.location.href='{{ route('expenses.create') }}'">
            + Add Expense
        </button>
    </div>

    <!-- Search & Filter -->
    <div class="d-flex justify-content-between mb-3">
        <input type="text" id="searchInput" 
               class="form-control w-50" 
               placeholder="Search expense..." 
               onkeyup="filterTable()">

        <select id="typeFilter" class="form-select w-25" onchange="filterTable()">
            <option value="">All Types</option>
            @foreach($expenses->pluck('expense_type')->unique() as $type)
                <option value="{{ strtolower($type) }}">{{ ucfirst($type) }}</option>
            @endforeach
        </select>
    </div>

    <!-- Table -->
    <div class="table-responsive">
     <!-- Table -->
<div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
    <table class="table table-bordered table-hover align-middle" id="expensesTable">
        <thead class="table-light" style="position: sticky; top: 0; z-index: 3;">
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
                    <td class="text-end amount negative">{{ number_format($exp->amount, 2) }}</td>
                    <td>
                        <a href="{{ route('expenses.show', $exp->id) }}" class="btn btn-sm btn-back">
                            View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No expenses recorded</td>
                </tr>
            @endforelse
        </tbody>
        @if($expenses->count())
        <tfoot style="position: sticky; bottom: 0; background: #f8f9fc; z-index: 2;">
            <tr>
                <td colspan="4" class="text-end">Total</td>
                <td class="text-end amount positive">{{ number_format($total, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>
    <div class="d-flex justify-content-end mt-3">
        {{  $expenses->links('pagination::bootstrap-5') }}
    </div>
</div>

        <div class="d-flex justify-content-between align-items-right mb-3">
            <button class="btn btn-secondary" onclick="window.location.href='/'">
                Back
            </button>
    </div>
</div>
<script>
    function filterTable() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        const typeFilter = document.getElementById("typeFilter").value.toLowerCase();
        const rows = document.querySelectorAll("#expensesTable tbody tr");
    
        rows.forEach(row => {
            // Check if search input matches any cell
            const textMatch = Array.from(row.cells).some(cell =>
                cell.textContent.toLowerCase().includes(input)
            );
    
            // Type column is index 2 (Expense No = 0, Date = 1, Type = 2)
            const type = row.cells[2]?.textContent.toLowerCase();
            const typeMatch = !typeFilter || type === typeFilter;
    
            // Show/hide row
            row.style.display = (textMatch && typeMatch) ? "" : "none";
        });
    }
    </script>
    
</body>
</html>
