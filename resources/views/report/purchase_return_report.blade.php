@extends('layouts.app')

@section('title', 'Purchase Return Report')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
<style>

.container { margin-top:40px; }
h3 { margin-bottom:20px; }
.table thead { background:#0d6efd; color:#fff; }
.table-hover tbody tr:hover { background:#f1f1f1; }
</style>
@endpush
@section('content')
<div class="container">
    <h3>Purchase Return Report</h3>

    <!-- Filters -->
    <div class="card mb-3 p-3">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <input type="date" name="from_date" value="{{ $fromDate }}" class="form-control" placeholder="From Date">
            </div>
            <div class="col-md-3">
                <input type="date" name="to_date" value="{{ $toDate }}" class="form-control" placeholder="To Date">
            </div>
            <!-- Inside your filter form -->
<div class="col-md-3">
    <input type="week" name="week" value="{{ $week ?? '' }}" class="form-control" placeholder="Select Week">
</div>

            <div class="col-md-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search Supplier / Invoice">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('reports.purchase_returns') }}" class="btn btn-outline-danger flex-fill">Reset</a>
            </div>
        </form>
    </div>

    <!-- Grand Total -->
    <div class="mb-3">
        <strong>Total Returned Amount:</strong>
        {{rtrim(rtrim(number_format($grandTotal, 2), '0'), '.') }}
       
    </div>

    <!-- Purchase Return Table -->
    <div class="table-responsive">
        <table id="returnTable" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Return Date</th>
                    <th>Purchase Invoice</th>
                    <th>Supplier</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
            @foreach($returns as $r)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($r->return_date)->format('d M, Y') }}</td>
                    <td>{{ $r->invoice_no }}</td>
                    <td>{{ $r->supplier_name }}</td>
                    <td>{{ $r->material_name }}</td>
                    <td>{{ $r->quantity }}</td>
                    <td>{{rtrim(rtrim(number_format($r->price, 2), '0'), '.') }}</td>
                    <td> {{rtrim(rtrim(number_format($r->subtotal, 2), '0'), '.') }}</td>
                    <td>{{ $r->remarks }}</td>
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
    var table = $('#returnTable').DataTable({
        paging:true,
        ordering:true,
        order:[[0,'desc']],
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
@endpush