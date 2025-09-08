<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Purchase Detail Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Supplier Purchases Detail</h3>

  <form method="get" class="row g-2 mb-3">
    <div class="col-auto">
      <input type="date" name="from" value="{{ $from ?? '' }}" class="form-control">
    </div>
    <div class="col-auto">
      <input type="date" name="to" value="{{ $to ?? '' }}" class="form-control">
    </div>
    <div class="col-auto">
      <button class="btn btn-primary">Filter</button>
    </div>
    <div class="col-auto">
      <a href="{{ route('reports.purchase_detail') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="table-light">
        <tr>
          <th>Supplier</th>
          <th>Invoice #</th>
          <th>Date</th>
          <th>Product</th>
          <th class="text-end">Qty</th>
          <th class="text-end">Price</th>
          <th class="text-end">Line Total</th>
        </tr>
      </thead>
      <tbody>
        @forelse($records as $r)
          <tr>
            <td>{{ $r->supplier_name }}</td>
            <td>{{ $r->invoice_no }}</td>
            <td>{{ $r->invoice_date }}</td>
            <td>{{ $r->product }}</td>
            <td class="text-end">{{ (float)$r->quantity }}</td>
            <td class="text-end">{{ number_format($r->unit_price,2) }}</td>
            <td class="text-end">{{ number_format($r->line_total,2) }}</td>
            
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted">No records found</td></tr>
        @endforelse
      </tbody>
      @if($records->count())
      <tfoot>
        <tr>
          <th colspan="6" class="text-end">Total Purchases</th>
          @isset($total)
            <th class="text-end">{{ number_format($total,2) }}</th>
          @else
            <th class="text-end">0.00</th>
          @endisset
        </tr>
      </tfoot>
      @endif
      
    </table>
  </div>
</div>
</body>
</html>
