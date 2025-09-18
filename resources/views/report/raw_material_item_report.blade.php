{{-- resources/views/report/raw_material_item_report.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Raw Material Item Report</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<style>
body         { background:#f8f9fa; font-family:Arial,sans-serif; }
.container   { margin-top:40px; }
h3           { margin-bottom:20px; }
.table thead { background:#0d6efd; color:#fff; }
.table-hover tbody tr:hover { background:#f1f1f1; }
.summary-box strong { display:block; font-size:1.1rem; }
</style>
</head>
<body>
<div class="container">
    <h3>Raw Material Item Report</h3>

    <!-- Filters -->
<!-- Filters -->
<div class="card mb-3 p-3">
    <form method="GET" class="row g-2">
        {{-- From Date --}}
        <div class="col-md-3">
            <input type="date" name="from_date" value="{{ $from }}" class="form-control">
        </div>

        {{-- To Date --}}
        <div class="col-md-3">
            <input type="date" name="to_date" value="{{ $to }}" class="form-control">
        </div>

        {{-- ✅ Week Picker – new field --}}
        <div class="col-md-3">
            <input type="week"
                   name="week"
                   value="{{ request('week') }}"
                   class="form-control"
                   placeholder="Select Week">
        </div>

        {{-- Search + Buttons --}}
        <div class="col-md-3 d-flex gap-2">
            <input type="text"
                   id="searchInput"
                   class="form-control flex-fill"
                   placeholder="Search Code / Material">
            <button class="btn btn-primary flex-fill">Filter</button>
            <a href="{{ route('reports.raw_material_item_report') }}"
               class="btn btn-outline-danger flex-fill">Reset</a>
        </div>
    </form>
</div>

    <!-- Optional totals summary -->
    <div class="row mb-3 summary-box text-center">
        <div class="col-md-4"><strong>Total Opening:</strong>{{rtrim(rtrim(number_format($report->sum('opening_stock'),2), '0'), '.') }}</div>
        <div class="col-md-4"><strong>Total Issued:</strong>{{rtrim(rtrim(number_format($report->sum('total_issued'),2), '0'), '.') }}</div>
        <div class="col-md-4"><strong>Total Closing:</strong>{{rtrim(rtrim(number_format($report->sum('closing_stock'),2), '0'), '.') }}</div>
    </div>

    <!-- DataTable -->
    <div class="table-responsive">
        <table id="itemTable" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Material</th>
                    <th>Unit</th>
                    <th>Opening</th>
                    <th>Issued Qty</th>
                    <th>Closing</th>
                    <th>Issue Date</th>
                    <th>Store Name</th>
                    <th>Issued By</th>
                    <th>Approved By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report as $row)
                <tr>
                    <td>{{ $row->material_code }}</td>
                    <td>{{ $row->material_name }}</td>
                    <td>{{ $row->unit }}</td>
                    <td>{{rtrim(rtrim(number_format($row->opening_stock, 2), '0'), '.') }}</td>
                    <td>{{rtrim(rtrim(number_format($row->total_issued, 2), '0'), '.') }}</td>
                    <td>{{rtrim(rtrim(number_format($row->closing_stock, 2), '0'), '.') }}</td>
                    <td>{{ $row->last_issue_date }}</td>
                    <td>{{ $row->store_name }}</td>
                    <td>{{ $row->issued_by }}</td>
                    <td>{{ $row->approved_by }}</td>
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
    var table = $('#itemTable').DataTable({
        paging:true,
        ordering:true,
        order:[[1,'asc']],       // sort by Material name
        pageLength:25,
        responsive:true,
        dom:'Bfrtip',
        buttons:['copy','csv','excel','pdf','print']
    });

    $('#searchInput').on('keyup', function(){
        table.search(this.value).draw();
    });
});
</script>
</body>
</html>
