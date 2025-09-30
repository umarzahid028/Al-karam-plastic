@extends('layouts.app')

@section('title', 'Total Sales Report')

@push('styles')
<style>

.container { margin-top:40px; }
h3 { margin-bottom:20px; }
.table thead { background:#0d6efd; color:#fff; }
.table-hover tbody tr:hover { background:#f1f1f1; }
.summary-box strong { display:block; font-size:1.1rem; }
.low-stock { background:#fff3cd !important; }   /* highlight low stock */
.logo img { height:50px; }
</style>
@endpush
@section('content')

<div class="container">

    {{-- ===== Header / Logo ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-4 logo">
        <div>
            <h3 class="mb-0"> Stock Summary @if(!empty($from) && !empty($to)) ({{ $from }} – {{ $to }}) @endif </h3>
        </div>
       
    </div>

    {{-- ===== Filters ===== --}}
    <div class="card mb-3 p-3">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <input type="date" name="from_date" value="{{ $from ?? '' }}" class="form-control">
            </div>
            <div class="col-md-3">
                <input type="date" name="to_date" value="{{ $to ?? '' }}" class="form-control">
            </div>
            <div class="col-md-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search product / code">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('reports.stock-summary') }}" class="btn btn-outline-danger flex-fill">Reset</a>
            </div>
        </form>
    </div>

    {{-- ===== Totals ===== --}}
    <div class="row mb-3 summary-box text-center">
        <div class="col-md-3"><strong>Opening:</strong>  {{ rtrim(rtrim(number_format($totals['opening'],2),'0'),'.') }}</div>
        <div class="col-md-3"><strong>Purchased:</strong>   {{ rtrim(rtrim(number_format($totals['purchased'],2),'0'),'.') }}</div>
        <div class="col-md-3"><strong>Sold:</strong>  {{ rtrim(rtrim(number_format($totals['sold'],2),'0'),'.') }}</div>
        <div class="col-md-3"><strong>Closing:</strong>  {{ rtrim(rtrim(number_format($totals['closing'],2),'0'),'.') }}</div>
    </div>

    {{-- ===== Stock Table ===== --}}
    <div class="table-responsive">
        <table id="stockTable" class="table table-bordered table-hover table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Product</th>
                    <th>Unit</th>
                    <th class="text-end">Opening</th>
                    <th class="text-end">Purchased</th>
                    <th class="text-end">Sold</th>
                    <th class="text-end">Closing</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $i => $p)
                    @php
                        $opening   = $p->opening_qty ?? 0;
                        $purchased = $p->purchased_qty ?? 0;
                        $sold      = $p->sold_qty ?? 0;
                        $closing   = ($opening + $purchased) - $sold;
                    @endphp
                    <tr class="{{ $closing < 10 ? 'low-stock' : '' }}">
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $p->product_code }}</td>
                        <td>{{ $p->product_name }}</td>
                        <td>{{ $p->unit }}</td>
                        <td class="text-end">
                            {{ rtrim(rtrim(number_format($opening,2),'0'),'.') }}
                         </td>
                        <td class="text-end">
                            {{ rtrim(rtrim(number_format($purchased,2),'0'),'.') }}
                          </td>
                        <td class="text-end">
                            {{ rtrim(rtrim(number_format($sold,2),'0'),'.') }}</td>
                        <td class="text-end fw-bold">
                            {{ rtrim(rtrim(number_format($closing,2),'0'),'.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
{{-- ===== Scripts ===== --}}
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
    const table = $('#stockTable').DataTable({
        paging:true,
        ordering:true,
        order:[[1,'asc']],      // sort by product code
        pageLength:25,
        responsive:true,
        dom:'Bfrtip',
        buttons:[
            { extend:'copy', text:'Copy' },
            { extend:'csv',  text:'CSV' },
            { extend:'excel',text:'Excel' },
            { extend:'pdf',  text:'PDF' },
            { extend:'print',text:'Print' }
        ]
    });

    $('#searchInput').on('keyup', function(){
        table.search(this.value).draw();
    });
});
</script>
@endpush
