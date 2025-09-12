<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sales Returns Report</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
body { background: #f8f9fa; font-family: Arial,sans-serif; }
.container { margin-top: 40px; }
h3 { margin-bottom: 20px; }
.table thead { background: #dc3545; color: white; }
.table-hover tbody tr:hover { background: #f1f1f1; }
.high-return { background-color: #fff3cd !important; }
</style>
</head>
<body>
<div class="container">
<h3>Sales Returns Report</h3>

<!-- Filters -->
<div class="card mb-3 p-3">
<form method="GET" class="row g-2">
    <div class="col-md-3">
        <input type="date" name="from_date" value="{{ $fromDate }}" class="form-control" placeholder="From Date">
    </div>
    <div class="col-md-3">
        <input type="date" name="to_date" value="{{ $toDate }}" class="form-control" placeholder="To Date">
    </div>
    <div class="col-md-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search Buyer / Product / Invoice">
    </div>
    <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-primary flex-fill">Filter</button>
        <a href="{{ route('reports.returns_sales_report') }}" class="btn btn-outline-danger flex-fill">Reset</a>
    </div>
</form>
</div>

<!-- Total Returns -->
<div class="mb-3">
    <strong>Total Returned Amount:</strong> {{ number_format($totalReturns, 2) }}
</div>

<!-- Returns Table -->
<div class="table-responsive">
<table id="returnsTable" class="table table-bordered table-hover table-striped">
<thead>
<tr>
    <th>Date</th>
    <th>Invoice #</th>
    <th>Buyer</th>
    <th>Product</th>
    <th>Returned Qty</th>
    <th>Amount</th>
    <th>Remarks</th>
</tr>
</thead>
<tbody>
@foreach($returns as $r)
<tr class="{{ $r->return_amount > 50000 ? 'high-return' : '' }}">
    <td>{{ \Carbon\Carbon::parse($r->return_date)->format('d M, Y') }}</td>
    <td>{{ $r->invoice_no }}</td>
    <td>{{ $r->buyer_name }}</td>
    <td>{{ $r->product_name }}</td>
    <td>{{ $r->returned_qty }}</td>
    <td>{{ number_format($r->return_amount, 2) }}</td>
    <td>{{ $r->remarks }}</td>
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
<script>
$(document).ready(function() {
    var table = $('#returnsTable').DataTable({
        paging: true,
        ordering: true,
        order: [[0, 'desc']],
        responsive: true,
        pageLength: 25,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });

    // Live Search
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
</body>
</html>
