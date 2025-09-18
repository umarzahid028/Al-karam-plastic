<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sales Invoices</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background:#f5f7fa;
    font-family: "Segoe UI", Arial, sans-serif;
}
.container {
    background:#fff;
    border-radius:12px;
    padding:25px;
    max-width:1100px;
    margin:40px auto;
    box-shadow:0 6px 20px rgba(0,0,0,0.08);
}
.page-title {
   
    color:#6c757d
    padding:15px 20px;
    border-radius:8px;
    margin-bottom:25px;
}
.table-hover tbody tr:hover {
    background:#f1f3f5;
}
.btn-add {
    background:#17a2b8;
    color:#fff;
    border:none;
}
.btn-add:hover {
    background:#138496;
}
.btn-back {
    background:#6c757d;
    color:#fff;
    border:none;
}
.btn-back:hover {
    background:#5a6268;
}
.search-box { max-width: 280px; }
</style>
</head>
<body>
<div class="container">

    <div class="d-flex justify-content-between align-items-center page-title">
        <h3 class="mb-0">Sales Invoices</h3>
        <a href="{{route('invoice.create')}}" class="btn btn-add">
            + Create Invoice
        </a>
    </div>

    <!-- Optional Search -->
    <form method="GET" action="{{ route('invoices.index') }}" class="mb-3 d-flex justify-content-end">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control search-box me-2"
               placeholder="Search by buyer or invoice no">
        <button class="btn btn-primary">Search</button>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Invoice No</th>
                    <th>Buyer</th>
                    <th>Salesperson</th>
                    <th>Date</th>
                    <th>Total Amount</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr>
                    <td>{{ $invoices->firstItem() + $loop->index }}</td>
                    <td>{{ $invoice->invoice_no }}</td>
                    <td>{{ $invoice->buyer->company_name ?? '-' }}</td>
                    <td>{{ $invoice->salesperson->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</td>
                    <td>{{ number_format($invoice->total_amount, 2) }}</td>
                    <td>{{ $invoice->remarks ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No invoices found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <button class="btn btn-back" onclick="window.location.href='{{ url('/') }}'">Back</button>
        {{ $invoices->links('pagination::bootstrap-5') }}
    </div>

</div>
</body>
</html>
