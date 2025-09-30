<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Ledger Entry</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
 
    .container {
      max-width: 750px;
      margin: 50px auto;
    }
    .card {
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      padding: 35px;
    }
    .card h3 {
      margin-bottom: 25px;
      color: #333;
      font-weight: 600;
    }
    .form-control:focus, .form-select:focus, textarea:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
    }
    label {
      font-weight: 500;
      color: #444;
    }
    textarea {
      min-height: 44px;
      resize: vertical;
    }
    .btn-info {
      background: #0dcaf0;
      border: none;
      padding: 10px 25px;
      border-radius: 6px;
      font-weight: 500;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }
    .btn-info:hover {
      background: #0bb5d4;
    }
    .btn-secondary {
      border-radius: 6px;
      font-weight: 500;
    }
    .d-flex.gap-2 > .btn {
      flex: 1;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="card">
    <h3>Add Ledger Entry</h3>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="m-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('ledger.store') }}">
      @csrf

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Party Type</label>
          <select name="party_type" class="form-select" required>
            <option value="">-- Select --</option>
            <option value="customer">Customer</option>
            <option value="supplier">Supplier</option>
            <option value="user">User</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Party Code</label>
          <input type="text" name="party_id" class="form-control" placeholder="e.g. SP-003 or CUST-001" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Reference Type</label>
          <select name="ref_type" class="form-select" required>
            <option value="">-- Select --</option>
            <option value="sale">Sale</option>
            <option value="purchase">Purchase</option>
            <option value="invoice">Invoice</option>
            <option value="payment">Payment</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Invoice No</label>
          <input type="text" name="invoice_no" class="form-control" placeholder="Invoice Number">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Invoice Date</label>
          <input type="date" name="invoice_date" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="1" placeholder="Transaction details"></textarea>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-6">
          <label class="form-label">Debit</label>
          <input type="number" step="0.01" name="debit" class="form-control" value="0">
        </div>
        <div class="col-md-6">
          <label class="form-label">Credit</label>
          <input type="number" step="0.01" name="credit" class="form-control" value="0">
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-info">Save Entry</button>
        <a href="{{ route('ledger.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
