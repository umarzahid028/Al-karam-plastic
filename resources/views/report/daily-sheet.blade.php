@extends('layouts.app')

@section('title', 'Daily Sheet')

@push('styles')
{{-- <title>Daily Sheet – {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</title> --}}

{{-- Bootstrap & DataTables CSS --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

{{-- Icons & Font --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

<style>
:root {
    --primary-color: #0d6efd;
    --primary-gradient: linear-gradient(135deg, #0d6efd 0%, #5b9eff 100%);
    --background-color: #f9fafb;
    --dark-bg: #1e1e1e;
    --dark-text: #f1f1f1;
}
body {
    background: var(--background-color);
   
    color: #333;
    transition: background .3s, color .3s;
}
body.dark-mode {
    background: var(--dark-bg);
    color: var(--dark-text);
}
.page-header {
   
    color: black;
    padding: 1.5rem 2rem;
    border-radius: .75rem;
    margin-bottom: 2rem;
}
.card { border: none; border-radius: .75rem; }
.kpi-card {
    transition: transform .2s, box-shadow .2s;
}
.kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 15px rgba(0,0,0,.1);
}
.kpi-card h6 {
    color:#6c757d; text-transform: uppercase; letter-spacing: .5px;
}
.kpi-card h4 {
    font-weight: 700;
}
.table thead { background: var(--primary-color); color:#fff; }
.table tbody tr:hover { background-color: rgba(13,110,253,.05); }
.dark-mode .table thead { background:#333; color:#fff; }
.dark-mode .table tbody tr:hover { background:rgba(255,255,255,.05); }
</style>
@endpush
@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-center">
        <h3 class="mb-3 mb-md-0">Daily Sheet – {{ \Carbon\Carbon::parse($date)->format('d M, Y') }}</h3>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <input type="date" name="date" value="{{ $date }}" class="form-control">
                <button class="btn btn-light">Show</button>
            </form>
          
        </div>
    </div>

    {{-- KPI Summary --}}
    <section class="row g-3 mb-5 col-lg-10 mx-auto">
        <div class="col-md-3">
            <div class="card shadow-sm text-center kpi-card">
                <div class="card-body">
                    <i class="bi bi-currency-dollar fs-3 text-success mb-2"></i>
                    <h6>Sales</h6>
                    <h4 class="text-success">{{ number_format($sales->gross ?? 0,2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center kpi-card">
                <div class="card-body">
                    <i class="bi bi-cart-check fs-3 text-primary mb-2"></i>
                    <h6>Purchases</h6>
                    <h4 class="text-primary">{{ number_format($purchases->gross ?? 0,2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center kpi-card">
                <div class="card-body">
                    <i class="bi bi-wallet2 fs-3 text-danger mb-2"></i>
                    <h6>Expenses</h6>
                    <h4 class="text-danger">{{ number_format($expenses ?? 0,2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center kpi-card">
                <div class="card-body">
                    <i class="bi bi-graph-up fs-3 text-dark mb-2"></i>
                    <h6>Net</h6>
                    <h4 class="text-dark">
                        {{ number_format(($sales->gross ?? 0) - ($purchases->gross ?? 0) - ($expenses ?? 0),2) }}
                    </h4>
                </div>
            </div>
        </div>
    </section>

    {{-- Sales Table --}}
    <section class="col-lg-10 mx-auto mb-5">
        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0">Sales Invoices</h5></div>
            <div class="card-body p-0">
                <table id="salesTable" class="table table-bordered table-hover table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th><th>Invoice #</th><th>Buyer</th>
                            <th class="text-end">Amount</th><th class="text-end">Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesList as $i => $s)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $s->invoice_no }}</td>
                            <td>{{ $s->buyer_name }}</td>
                            <td class="text-end">{{ number_format($s->total_amount,2) }}</td>
                            <td class="text-end">{{ number_format($s->paid_amount,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Purchases --}}
    <section class="col-lg-10 mx-auto mb-5">
        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0">Purchases</h5></div>
            <div class="card-body p-0">
                <table id="purchaseTable" class="table table-bordered table-hover table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th><th>Invoice #</th><th>Supplier</th>
                            <th class="text-end">Amount</th><th class="text-end">Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseList as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $p->invoice_no }}</td>
                            <td>{{ $p->supplier_name }}</td>
                            <td class="text-end">{{ number_format($p->total_amount,2) }}</td>
                            <td class="text-end">{{ number_format($p->paid_amount,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Expenses --}}
    <section class="col-lg-10 mx-auto mb-5">
        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0">Expenses</h5></div>
            <div class="card-body p-0">
                <table id="expenseTable" class="table table-bordered table-hover table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th><th>Category</th><th>Vendor</th>
                            <th>Payment Method</th><th>Description</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenseList as $i => $e)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $e->expense_category }}</td>
                            <td>{{ $e->vendor ?? '-' }}</td>
                            <td>{{ $e->payment_method ?? '-' }}</td>
                            <td>{{ $e->description ?? '-' }}</td>
                            <td class="text-end">{{ number_format($e->amount,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Stock --}}
    <section class="col-lg-10 mx-auto mb-5">
        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0">Stock Movement</h5></div>
            <div class="card-body">
                <p class="mb-1">Quantity In:  <strong>{{ number_format($stock->qty_in ?? 0,2) }}</strong></p>
                <p class="mb-0">Quantity Out: <strong>{{ number_format($stock->qty_out ?? 0,2) }}</strong></p>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
{{-- JS --}}
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
    function makeTable(id){
        return $(id).DataTable({
            responsive: true,
            pageLength: 25,
            fixedHeader: true,
            stateSave: true,
            dom: 'Bfrtip',
            buttons: [
                'copy','csv','excel','pdf','print',
                { extend: 'colvis', text: 'Columns' }
            ]
        });
    }
    makeTable('#salesTable');
    makeTable('#purchaseTable');
    makeTable('#expenseTable');

    // Dark mode toggle
    $('#themeToggle').on('click', function(){
        $('body').toggleClass('dark-mode');
    });
});
</script>
@endpush