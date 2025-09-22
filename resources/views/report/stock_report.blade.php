<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Stock Report</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap & DataTables CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<style>
body {
    background:#f8f9fa;
    font-family:Arial, sans-serif;
}
.container {
    margin-top:40px;
}
h3 {
    margin-bottom:20px;
    font-weight:600;
}
.table thead {
    background:#0d6efd;
    color:#fff;
}
.table-hover tbody tr:hover {
    background:#f1f1f1;
}
.summary-box strong {
    display:block;
    font-size:1.1rem;
}
.summary-box .col-md-3 {
    padding:10px 0;
}
/* DataTables custom alignment */
#stockTable_wrapper .dt-buttons {
    float:left;
    margin-bottom:10px;
}
#stockTable_wrapper .dataTables_info {
    text-align:left;
}
</style>
</head>
<body>
<div class="container">
    <h3>Stock Report</h3>

    <!-- Filters -->
    <div class="card mb-4 p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label d-block">Search Material</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Type to search...">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('reports.stock') }}" class="btn btn-outline-danger flex-fill">Reset</a>
            </div>
        </form>
    </div>

    <!-- Totals Summary -->
    <div class="row mb-4 summary-box text-center">
        <div class="col-md-3"><strong>Total Materials:</strong> {{ $stocks->count() }}</div>
        <div class="col-md-3 text-success"><strong>Total In:</strong>
            {{ rtrim(rtrim(number_format($stocks->sum('total_in'),2),'0'),'.') }}
        </div>
        <div class="col-md-3 text-danger"><strong>Total Out:</strong>
            {{ rtrim(rtrim(number_format($stocks->sum('total_out'),2),'0'),'.') }}
        </div>
        <div class="col-md-3"><strong>Total Stock Value:</strong>
            {{ rtrim(rtrim(number_format($stocks->sum(fn($s)=>($s->total_in-$s->total_out)*$s->avg_price),2),'0'),'.') }}
        </div>
    </div>

    <!-- Stock Table -->
    <div class="table-responsive">
        <table id="stockTable" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Material Name</th>
                    <th>Total In</th>
                    <th>Total Out</th>
                    <th>Current Stock</th>
                    <th>Purchase Price</th>
                    <th>Total Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $stock)
                <tr>
                    <td>{{ $stock->material_name }}</td>
                    <td>{{ rtrim(rtrim(number_format($stock->total_in,2),'0'),'.') }}</td>
                    <td>{{ rtrim(rtrim(number_format($stock->total_out,2),'0'),'.') }}</td>
                    <td>{{ rtrim(rtrim(number_format($stock->total_in - $stock->total_out,2),'0'),'.') }}</td>
                    <td>{{ rtrim(rtrim(number_format($stock->avg_price,2),'0'),'.') }}</td>
                    <td>{{ rtrim(rtrim(number_format(($stock->total_in - $stock->total_out) * $stock->avg_price,2),'0'),'.') }}</td>
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
$(function () {
    const table = $('#stockTable').DataTable({
        paging: true,
        ordering: true,
        order: [[0, 'asc']],
        pageLength: 25,
        responsive: true,
        // Custom DOM layout for clean alignment
        dom: "<'row mb-2'<'col-sm-6'B><'col-sm-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            { extend: 'copy',  title: 'Stock Report' },
            { extend: 'csv',   title: 'Stock Report' },
            { extend: 'excel', title: 'Stock Report' },
            { extend: 'pdf',   title: 'Stock Report', orientation: 'landscape', pageSize: 'A4' },
            { extend: 'print', title: 'Stock Report' },
            'colvis'
        ],
        language: {
            search: "Quick Search:",
            lengthMenu: "Show _MENU_ rows",
            info: "Showing _START_ to _END_ of _TOTAL_ materials"
        }
    });

    // External search input
    $('#searchInput').on('keyup', function () {
        table.search(this.value).draw();
    });
});
</script>
</body>
</html>
