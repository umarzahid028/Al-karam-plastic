{{-- resources/views/reports/total_sales.blade.php --}}
@extends('layouts.app')

@section('title', 'Total Sales Report')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
<style>
body { background:#f8f9fa; font-family:Arial,sans-serif; }
.container { margin-top:40px; }
h3 { margin-bottom:20px; }
.table thead { background:#0d6efd; color:#fff; }
.table-hover tbody tr:hover { background:#f1f1f1; }
</style>

@endpush
@section('content')
<div class="container">
    <h3>Total Sales Report</h3>

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
                <input type="text" id="searchInput" class="form-control" placeholder="Search Buyer / Invoice">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('reports.total_sales') }}" class="btn btn-outline-danger flex-fill">Reset</a>
            </div>
        </form>
    </div>

    <!-- Grand Total -->
    <div class="mb-3">
        <strong>Total Sales Amount:</strong>
        {{rtrim(rtrim(number_format($grandTotal,2), '0'), '.') }}
       
    </div>

    <!-- Sales Table -->
    <div class="table-responsive">
        <table id="salesTable" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice #</th>
                    <th>Buyer</th>
                    <th>Total Qty</th>
                    <th>Line Total</th>
                </tr>
            </thead>
            <tbody>
            @foreach($records as $r)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($r->invoice_date)->format('d M, Y') }}</td>
                    <td>{{ $r->invoice_no }}</td>
                    <td>{{ $r->buyer_name }}</td>
                    <td>{{rtrim(rtrim(number_format($r->total_qty, 2), '0'), '.') }} </td>
                    <td> {{rtrim(rtrim(number_format($r->total_line, 2), '0'), '.') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
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
    var table = $('#salesTable').DataTable({
        paging:true,
        ordering:true,
        order:[[0,'desc']],
        pageLength:25,
        responsive:true,
        dom:'Bfrtip',
        buttons:['copy','csv','excel','pdf','print']
    });

    // Live Search
    $('#searchInput').on('keyup', function(){
        table.search(this.value).draw();
    });
});
</script>
@endpush
