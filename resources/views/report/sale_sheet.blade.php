<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sale Sheet Report</title>

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
    <h3>Sale Sheet Report</h3>

    <!-- Filters -->
    <div class="card mb-3 p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" name="from_date" value="{{ $fromDate?->format('Y-m-d') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" name="to_date" value="{{ $toDate?->format('Y-m-d') }}" class="form-control">
            </div>
            <div class="col-md-3 mt-2">
                <input type="text" id="searchInput" class="form-control" placeholder="Search Invoice / Buyer / Product">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('reports.sale_sheet') }}" class="btn btn-outline-danger flex-fill">Reset</a>
            </div>

        </form>
    </div>

    <!-- Totals Summary -->
    <div class="row mb-3 summary-box text-center">
        <div class="col-md-3"><strong>Total Invoices:</strong> {{ $sales->count() }}</div>
        <div class="col-md-3"><strong>Total Quantity:</strong> {{ $sales->sum('qty') }}</div>
        <div class="col-md-3"><strong>Total Amount:</strong> 
            {{ rtrim(rtrim(number_format($sales->sum(fn($s) => $s->qty * $s->rate), 2),'0'),'.') }}
           </div>
        <div class="col-md-3"><strong>Average Rate:</strong> 
            {{ rtrim(rtrim(number_format($sales->avg('rate'), 2),'0'),'.') }}
          </div>
    </div>

    <!-- Sale Sheet Table -->
    <div class="table-responsive">
        <table id="saleSheetTable" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Buyer</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Rate</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $s)
                <tr>
                    <td>{{ $s->invoice_no }}</td>
                    <td>{{ $s->invoice_date }}</td>
                    <td>{{ $s->buyer_name }}</td>
                    <td>{{ $s->product_name }}</td>
                    <td>{{ $s->qty }}</td>
                    <td>
                        {{ rtrim(rtrim(number_format($s->rate,2),'0'),'.') }}
                       </td>
                    <td>
                        {{ rtrim(rtrim(number_format($s->qty * $s->rate, 2),'0'),'.') }}
                        </td>
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
    var table = $('#saleSheetTable').DataTable({
        paging: true,
        ordering: true,
        order: [[1,'desc']],
        pageLength: 25,
        responsive: true,
        dom: 'Bfrtip',
        buttons: ['copy','csv','excel','pdf','print']
    });

    $('#searchInput').on('keyup', function(){
        table.search(this.value).draw();
    });
});
</script>
</body>
</html>
