<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Daily Sheet – {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<style>
body        { background:#f8f9fa; font-family:Arial,sans-serif; }
.container  { margin-top:40px; }
h3          { margin-bottom:20px; }
.table thead{ background:#0d6efd; color:#fff; }
.summary-box strong { display:block; font-size:1.1rem; }
.card-section{ margin-bottom:20px; }
</style>
</head>
<body>
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Daily Sheet – {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h3>
        <form method="GET" class="d-flex gap-2">
            <input type="date" name="date" value="{{ $date }}" class="form-control">
            <button class="btn btn-primary">Show</button>
        </form>
    </div>

    <div class="row mb-3 summary-box text-center">
        <div class="col-md-3"><strong>Sales</strong> {{ number_format($sales->gross ?? 0,2) }}</div>
        <div class="col-md-3"><strong>Purchases</strong> {{ number_format($purchases->gross ?? 0,2) }}</div>
        <div class="col-md-3"><strong>Expenses</strong> {{ number_format($expenses ?? 0,2) }}</div>
        <div class="col-md-3"><strong>Net</strong>
            {{ number_format(($sales->gross ?? 0) - ($purchases->gross ?? 0) - ($expenses ?? 0),2) }}
        </div>
    </div>

    {{-- Sales --}}
    <div class="card-section">
        <h5 class="mb-3">Sales Invoices</h5>
        <table id="salesTable" class="table table-bordered table-hover table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Invoice #</th>
                    <th>Buyer</th>
                    <th class="text-end">Amount</th>
                    <th class="text-end">Paid</th>
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

    {{-- Purchases --}}
    <div class="card-section">
        <h5 class="mb-3">Purchases</h5>
        <table id="purchaseTable" class="table table-bordered table-hover table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Invoice #</th>
                    <th>Supplier</th>
                    <th class="text-end">Amount</th>
                    <th class="text-end">Paid</th>
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

    {{-- Expenses --}}
    <div class="card-section">
        <h5 class="mb-3">Expenses</h5>
        <table id="expenseTable" class="table table-bordered table-hover table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category</th>
                    <th>Vendor</th>
                    <th>Payment Method</th>
                    <th>Description</th>
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

    {{-- Stock --}}
    <div class="card-section">
        <h5 class="mb-3">Stock Movement</h5>
        <p>Quantity In:  <strong>{{ number_format($stock->qty_in ?? 0,2) }}</strong></p>
        <p>Quantity Out: <strong>{{ number_format($stock->qty_out ?? 0,2) }}</strong></p>
    </div>
</div>

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
            paging:true,
            ordering:true,
            pageLength:25,
            responsive:true,
            dom:'Bfrtip',
            buttons:[
                { extend:'copy',  text:'Copy' },
                { extend:'csv',   text:'CSV' },
                { extend:'excel', text:'Excel' },
                { extend:'pdf',   text:'PDF' },
                { extend:'print', text:'Print' }
            ]
        });
    }
    makeTable('#salesTable');
    makeTable('#purchaseTable');
    makeTable('#expenseTable');
});
</script>
</body>
</html>
