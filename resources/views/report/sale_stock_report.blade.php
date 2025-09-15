<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sale Stock Report</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<style>
body { background:#f8f9fa; font-family:Arial,sans-serif; }
.container { margin-top:40px; }
h3 { margin-bottom:20px; }
.table thead { background:#0d6efd; color:#fff; }
.table-hover tbody tr:hover { background:#f1f1f1; }
.summary-box strong { display:block; font-size:1.1rem; }
</style>
</head>
<body>
<div class="container">
    <h3>Sale Stock Report</h3>

    <!-- Filters -->
    <div class="card mb-3 p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('reports.sale_stock_report') }}" class="btn btn-outline-danger flex-fill">Reset</a>
            </div>
            <div class="col-md-3 mt-2">
                <input type="text" id="searchInput" class="form-control" placeholder="Search Material">
            </div>
        </form>
    </div>

    <!-- Totals Summary -->
    <div class="row mb-3 summary-box text-center">
        <div class="col-md-3"><strong>Total Materials Sold:</strong> {{ count($stocks) }}</div>
        <div class="col-md-3 text-success"><strong>Total Sold:</strong> {{ number_format($stocks->sum('total_out'),2) }}</div>
        <div class="col-md-3"><strong>Total Stock Value:</strong> {{ number_format($stocks->sum(fn($s)=>$s->stock_value),2) }}</div>
    </div>

    <!-- Sale Stock Table -->
    <div class="table-responsive">
        <table id="saleStockTable" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Material Name</th>
                    <th>Total Purchased</th>
                    <th>Sold</th>
                    <th>Current Stock</th>
                    <th>Purchase Price</th>
                    <th>Stock Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $stock)
                <tr>
                    <td>{{ $stock->material_name }}</td>
                    <td>{{ number_format($stock->total_in,2) }}</td>
                    <td>{{ number_format($stock->total_out,2) }}</td>
                    <td>{{ number_format($stock->current_stock,2) }}</td>
                    <td>{{ number_format($stock->purchase_price,2) }}</td>
                    <td>{{ number_format($stock->stock_value,2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(function(){
    var table = $('#saleStockTable').DataTable({
        paging:true,
        ordering:true,
        order:[[0,'asc']],
        pageLength:25,
        responsive:true,
        dom:'Bfrtip',
        buttons:['copy','csv','excel','pdf','print']
    });

    // live search
    $('#searchInput').on('keyup', function(){
        table.search(this.value).draw();
    });
});
</script>
</body>
</html>
