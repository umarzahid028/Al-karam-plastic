<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Purchases List</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: Arial; background:#f5f7fa; }
.container { max-width: 1000px; margin: 50px auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 6px 25px rgba(0,0,0,0.15);}
.table-hover tbody tr:hover { background-color: #f1f1f1; }
.btn-info { background:#17a2b8; color:white; border-radius:6px; box-shadow: 0 2px 5px rgba(0,0,0,0.15);}
.btn-info:hover { background:#138496; color:white; }
#searchInput { margin-bottom: 15px; max-width: 300px; }
@media (max-width: 768px) {
    .table-responsive { overflow-x: auto; }
}
</style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Purchases</h3>
        <div class="d-flex gap-2">
            <button class="btn btn-info" onclick="window.location.href='{{ route('purchases.create') }}'">
                + Add Purchase
            </button>
            <button class="btn btn-secondary" onclick="window.location.href='/'">
                Back
            </button>
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
                @forelse($purchases as $index => $purchase)
                <tr>
                    <td>{{ $purchases->firstItem() + $loop->index }}</td>
                    <td>{{ $purchase->purchase_code }}</td>
                    <td>{{ $purchase->supplier_id }}</td> {{-- Replace with supplier name if relationship exists --}}
                    <td>{{ $purchase->purchase_date }}</td>
                    <td>{{ number_format($purchase->total_amount, 2) }}</td>
                    <td>{{ ucfirst($purchase->status) }}</td>
                    <td>{{ $purchase->description }}</td>
                    <td>
                        <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-info btn-sm">View</a>

                        <form action="#" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this purchase?')">Delete</button>
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
            {{  $purchases->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script>
// Search/filter function
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
</body>
</html>
