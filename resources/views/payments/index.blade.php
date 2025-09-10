<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<div class="container">
    <h3>Payments</h3>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
      <div class="col-md-6">
        <h5>Receive Payment (Customer)</h5>
        <form action="{{ route('payments.customer.store') }}" method="POST">
          @csrf
          <div class="mb-2">
            <label>Buyer</label>
            <select name="buyer_id" id="buyer" class="form-select" required>
              <option value="">Select</option>
              @foreach($buyers as $b)
                <option value="{{ $b->id }}" data-contact="{{ $b->contact_no ?? '' }}">{{ $b->company_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-2">
            <label>Invoice (optional)</label>
            <input type="number" name="invoice_id" class="form-control" placeholder="sales_invoices.id (optional)">
          </div>
          <div class="mb-2">
            <label>Payment Date</label>
            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
          </div>
          <div class="mb-2">
            <label>Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Method</label>
            <select name="method" class="form-select">
              <option value="cash">Cash</option>
              <option value="bank">Bank</option>
              <option value="credit">Credit</option>
            </select>
          </div>
          <div class="mb-2">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
          </div>
          <button class="btn btn-success">Save Payment</button>
        </form>
      </div>

      <div class="col-md-6">
        <h5>Make Payment (Supplier)</h5>
        <form action="{{ route('payments.supplier.store') }}" method="POST">
          @csrf
          <div class="mb-2">
            <label>Supplier</label>
            <select name="supplier_id" class="form-select" required>
              <option value="">Select</option>
              @foreach($suppliers as $s)
                <option value="{{ $s->id }}">{{ $s->company_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-2">
            <label>Invoice (optional)</label>
            <input type="number" name="invoice_id" class="form-control" placeholder="purchases.id (optional)">
          </div>
          <div class="mb-2">
            <label>Payment Date</label>
            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
          </div>
          <div class="mb-2">
            <label>Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Method</label>
            <select name="method" class="form-select">
              <option value="cash">Cash</option>
              <option value="bank">Bank</option>
              <option value="credit">Credit</option>
            </select>
          </div>
          <div class="mb-2">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
          </div>
          <button class="btn btn-primary">Save Payment</button>
        </form>
      </div>
    </div>

    <hr>

    <h5>Recent Payments</h5>
    <table class="table table-sm">
      <thead><tr><th>#</th><th>Type</th><th>Party</th><th>Amount</th><th>Date</th><th>Method</th></tr></thead>
      <tbody>
        @foreach($payments as $pay)
          <tr>
            <td>{{ $pay->id }}</td>
            <td>{{ $pay->type }}</td>
            <td>
                @if($pay->type == 'customer') {{ $pay->customer->company_name ?? '-' }} 
                @else {{ $pay->supplier->company_name ?? '-' }} @endif
            </td>
            <td>{{ number_format($pay->amount,2) }}</td>
            <td>{{ $pay->payment_date->format('Y-m-d') }}</td>
            <td>{{ $pay->method }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

</div>


</body>
</html>