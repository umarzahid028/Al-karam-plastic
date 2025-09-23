
@extends('layouts.app')

@section('title', 'Create Customer Invoices')
@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; font-family: Arial, sans-serif; }
    .container { max-width: 1100px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
    h3 { font-weight: bold; }
    .form-label { font-weight: 500; }
    .table th, .table td { vertical-align: middle; }
    .btn-sm { padding: 3px 8px; }
    .grand-total { font-size: 1.2rem; font-weight: bold; }
  </style>
@endpush
@section('content')
<div class="container">
  <h3 class="mb-4 text-primary">Create Customer Invoice</h3>

  <!-- Error Messages -->
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('customer_invoices.store') }}">
    @csrf

    <!-- Customer Info -->
    <div class="row mb-4">
      <div class="col-md-4">
        <label class="form-label">Customer</label>
        <select name="buyer_id" class="form-select" required>
          <option value="">-- Select Customer --</option>
          @foreach($customers as $c)
            <option value="{{ $c->id }}" {{ old('buyer_id') == $c->id ? 'selected' : '' }}>
              {{ $c->name }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Invoice No</label>
        <input type="text" name="invoice_no" class="form-control" value="{{ old('invoice_no', $invoice_no ?? '') }}" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Invoice Date</label>
        <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
      </div>
      <div class="col-md-2">
        <label class="form-label">Remarks</label>
        <input type="text" name="remarks" class="form-control" value="{{ old('remarks') }}" placeholder="Optional">
      </div>
    </div>

    <!-- Invoice Items -->
    <h5 class="mb-3">Invoice Items</h5>
    <table class="table table-bordered" id="itemsTable">
      <thead class="table-light">
        <tr>
          <th>Product</th>
          <th width="120">Qty</th>
          <th width="120">Price</th>
          <th width="120">Total</th>
          <th width="50">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <select name="items[0][product_id]" class="form-select" required>
              <option value="">-- Select Product --</option>
              @foreach($products as $p)
                <option value="{{ $p->id }}">{{ $p->material_name }}</option>
              @endforeach
            </select>
          </td>
          <td><input type="number" name="items[0][qty]" class="form-control qty" step="0.01" value="1" required></td>
          <td><input type="number" name="items[0][price]" class="form-control price" step="0.01" required></td>
          <td><input type="number" name="items[0][total]" class="form-control total" readonly></td>
          <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">✖</button></td>
        </tr>
      </tbody>
    </table>
    <button type="button" class="btn btn-outline-primary mb-3" onclick="addRow()">+ Add Item</button>

    <!-- Totals -->
    <div class="mb-4 text-end">
      <label class="form-label grand-total">Grand Total:</label>
      <input type="text" id="grandTotal" name="total_amount" class="form-control d-inline-block w-auto text-end fw-bold" readonly>
    </div>

    <!-- Submit -->
    <div class="d-flex justify-content-between">
      <a href="{{ route('customers.index') }}" class="btn btn-secondary">⬅ Back</a>
      <button type="submit" class="btn btn-success">Save Invoice</button>
    </div>
  </form>
</div>
@endsection()
@push('scripts')
<script>
let rowIndex = 1;

function addRow() {
  let table = document.querySelector("#itemsTable tbody");
  let newRow = document.createElement("tr");

  newRow.innerHTML = `
    <td>
      <select name="items[${rowIndex}][product_id]" class="form-select" required>
        <option value="">-- Select Product --</option>
        @foreach($products as $p)
          <option value="{{ $p->id }}">{{ $p->material_name }}</option>
        @endforeach
      </select>
    </td>
    <td><input type="number" name="items[${rowIndex}][qty]" class="form-control qty" step="0.01" value="1" required></td>
    <td><input type="number" name="items[${rowIndex}][price]" class="form-control price" step="0.01" required></td>
    <td><input type="number" name="items[${rowIndex}][total]" class="form-control total" readonly></td>
    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">✖</button></td>
  `;
  table.appendChild(newRow);
  rowIndex++;
  attachListeners();
}

function removeRow(btn) {
  btn.closest("tr").remove();
  calculateTotals();
}

function attachListeners() {
  document.querySelectorAll(".qty, .price").forEach(input => {
    input.addEventListener("input", calculateTotals);
  });
}

function calculateTotals() {
  let grandTotal = 0;
  document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
    let qty = parseFloat(row.querySelector(".qty").value) || 0;
    let price = parseFloat(row.querySelector(".price").value) || 0;
    let total = qty * price;
    row.querySelector(".total").value = total.toFixed(2);
    grandTotal += total;
  });
  document.getElementById("grandTotal").value = grandTotal.toFixed(2);
}

attachListeners();
</script>
@endpush
