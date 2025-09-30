@extends('layouts.app')

@section('title', 'Raw Supplier Purchase Summary')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<style>
body { background:#f8f9fa;  }
.container { margin-top:40px; }
h3 { margin-bottom:20px; }
.table thead { background:#0d6efd; color:#fff; }
.table-hover tbody tr:hover { background:#f1f1f1; }
.summary-box strong { display:block; font-size:1.1rem; }
.items-list { font-size:0.9rem; color:#555; }
</style>
@endpush
@section('content')

<div class="container">
    <h3>Raw Supplier Purchase Summary</h3>

    <!-- Filters -->
    <div class="card mb-3 p-3">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <input type="date" name="from_date" value="{{ $from }}" class="form-control">
            </div>
            <div class="col-md-3">
                <input type="date" name="to_date" value="{{ $to }}" class="form-control">
            </div>
            <div class="col-md-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search Supplier / Invoice">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('reports.raw_supplier_purchase_summary') }}" class="btn btn-outline-danger flex-fill">Reset</a>
            </div>
        </form>
    </div>

    <!-- Totals -->
    <div class="row mb-3 summary-box text-center">
        <div class="col-md-6"><strong>Gross Amount:</strong> {{rtrim(rtrim(number_format($grossTotal,2), '0'), '.') }}</div>
        <div class="col-md-6"><strong>Paid Amount:</strong>{{rtrim(rtrim(number_format($paidTotal,2), '0'), '.') }} </div>
    </div>

    <!-- Purchases Table -->
    <div class="table-responsive">
        <table id="purchaseTable" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice #</th>
                    <th>Supplier</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Total Amount</th>
                    <th>Paid Amount</th>
                    <th>Items</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->purchase_date }}</td>
                    <td>{{ $purchase->invoice_no }}</td>
                    <td>{{ $purchase->supplier_name }}</td>
                    <td>{{ $purchase->payment_method }}</td>
                    <td>{{ $purchase->status }}</td>
                    <td>{{rtrim(rtrim(number_format($purchase->total_amount,2), '0'), '.') }}</td>
                    <td>{{rtrim(rtrim(number_format($purchase->paid_amount,2), '0'), '.') }}</td>
                    <td>
                        @if(isset($purchaseItems[$purchase->id]))
                            <ul class="items-list">
                            @foreach($purchaseItems[$purchase->id] as $item)
                            <li>
                                {{ $item->material_name }} ({{ $item->quantity }} x
                                {{ rtrim(rtrim(number_format($item->unit_price, 2), '0'), '.') }}
                                = {{ rtrim(rtrim(number_format($item->quantity * $item->unit_price, 2), '0'), '.') }})
                              </li>
                              
                            @endforeach
                            </ul>
                        @else
                            -
                        @endif
                    </td>
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
    var table = $('#purchaseTable').DataTable({
        paging:true,
        ordering:true,
        order:[[0,'desc']],
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
