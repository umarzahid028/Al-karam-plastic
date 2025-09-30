@extends('layouts.app')

@section('title', 'Total Sales Report')

@push('styles')
<style>

.container { margin-top:40px; }
h3 { margin-bottom:20px; }
.table thead { background:#0d6efd; color:#fff; }
.table-hover tbody tr:hover { background:#f1f1f1; }
.summary-box strong { display:block; font-size:1.1rem; }
</style>
@endpush
@section('content')

<div class="container">
    <h3>Ledger Report</h3>

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
                <input type="text" id="searchInput" class="form-control" placeholder="Search Description / Account">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('reports.ledger') }}" class="btn btn-outline-danger flex-fill">Reset</a>
            </div>
           
        </form>
    </div>

    <!-- Ledger Summary -->
    <div class="row mb-3 summary-box text-center">
        <div class="col-md-3"><strong>Total Debit:</strong> 
            {{rtrim(rtrim(number_format($ledgers->sum('debit'), 2), '0'), '.') }}</div>
        <div class="col-md-3"><strong>Total Credit:</strong> 
           
            {{rtrim(rtrim(number_format($ledgers->sum('credit'), 2), '0'), '.') }}
        </div>
        <div class="col-md-3"><strong>Balance:</strong> 
            {{-- {{ number_format($ledgers->sum('debit') - $ledgers->sum('credit'), 2) }} --}}
             {{rtrim(rtrim(number_format($ledgers->sum('debit') - $ledgers->sum('credit'), 2), '0'), '.') }}
        </div>
        <div class="col-md-3"><strong>Transactions:</strong> {{ $ledgers->count() }}</div>
    </div>

    <!-- Ledger Table -->
    <div class="table-responsive">
        <table id="ledgerTable" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Account</th>
                    <th>Description</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                @php $runningBalance = 0; @endphp
                @foreach($ledgers as $entry)
                    @php $runningBalance += $entry->debit - $entry->credit; @endphp
                    <tr>
                        <td>{{ $entry->invoice_date }}</td>
                        <td>{{ $entry->party_id }}</td> 
                        <td>{{ $entry->description }}</td>
                      <td>{{rtrim(rtrim(number_format($entry->debit,2), '0'), '.') }}</td>
                      <td>{{rtrim(rtrim(number_format($entry->credit,2), '0'), '.') }}</td>
                      <td>{{rtrim(rtrim(number_format($runningBalance,2), '0'), '.') }}</td>
                       
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
    var table = $('#ledgerTable').DataTable({
        paging:true,
        ordering:true,
        order:[[0,'asc']],
        pageLength:10,
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