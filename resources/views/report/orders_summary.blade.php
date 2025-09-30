@extends('layouts.app')

@section('title', 'Total Sales Report')

@push('styles')
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
@endpush
@section('content')

<div class="container">
    <h3>Orders Summary Report</h3>

    <!-- Filters -->
    <div class="card mb-3 p-3">
        <form method="GET" class="row g-2 align-items-end">
            {{-- From Date --}}
            <div class="col-md-3">
                <label class="form-label">From</label>
                <input type="date" name="from_date" value="{{ $from }}" class="form-control">
            </div>

            {{-- To Date --}}
            <div class="col-md-3">
                <label class="form-label">To</label>
                <input type="date" name="to_date" value="{{ $to }}" class="form-control">
            </div>

            {{-- Week Picker --}}
            <div class="col-md-3">
                <label class="form-label">Week</label>
                <input type="week"
                       name="week"
                       value="{{ request('week') }}"
                       class="form-control">
            </div>

            {{-- Action Buttons --}}
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('report.orders_summary') }}"
                   class="btn btn-outline-danger flex-fill">Reset</a>
            </div>

            {{-- Global Search --}}
            <div class="col-6 mt-2">
                <input type="text"
                       id="searchInput"
                       class="form-control"
                       placeholder="Search Supplier / Status / Code / Material">
            </div>
        </form>
    </div>

    <!-- Totals Summary -->
    <div class="row mb-3 summary-box text-center">
        <div class="col-md-3"><strong>Total Orders:</strong> {{ $totals->total_orders }}</div>
        <div class="col-md-3 text-warning"><strong>Pending:</strong> {{ $totals->pending_orders }}</div>
        <div class="col-md-3 text-success"><strong>Completed:</strong> {{ $totals->completed_orders }}</div>
        <div class="col-md-3"><strong>Grand Total:</strong>{{rtrim(rtrim(number_format($totals->grand_total, 2), '0'), '.') }}</div>
    </div>

    <!-- DataTable -->
    <div class="table-responsive">
        <table id="orderTable" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Supplier</th>
                    <th>Total Orders</th>
                    <th>Pending</th>
                    <th>Completed</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bySupplier as $row)
                <tr>
                    <td>{{ $row->supplier }}</td>
                    <td>{{ $row->total_orders }}</td>
                    <td>{{ $row->pending_orders }}</td>
                    <td>{{ $row->completed_orders }}</td>
                 <td>{{rtrim(rtrim(number_format($row->total_amount, 2), '0'), '.') }}</td>
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
    var table = $('#orderTable').DataTable({
        paging:true,
        ordering:true,
        order:[[0,'asc']],
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
@endpush