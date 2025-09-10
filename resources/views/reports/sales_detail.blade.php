<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Sales Detail Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fc;
      font-family: 'Segoe UI', sans-serif;
    }
    h3 {
      text-align: center;
      margin-bottom: 10px;
      font-weight: 600;
      color: #343a40;
    }
    .page-subtitle {
      text-align: center;
      margin-bottom: 25px;
      color: #6c757d;
      font-size: 0.95rem;
    }
    .filter-card {
      background: #fff;
      border-radius: .75rem;
      padding: 15px;
      margin-bottom: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,.05);
    }
    .filter-row input {
      min-width: 160px;
      border-radius: .5rem;
    }
    .filter-row .btn {
      border-radius: .5rem;
    }
    .table-container {
      background: #fff;
      border-radius: .75rem;
      padding: 1rem;
      box-shadow: 0 2px 8px rgba(0,0,0,.08);
    }
    .table {
      margin-bottom: 0;
    }
    .table thead {
      background: #0d6efd;
      color: #fff;
    }
    .table-hover tbody tr:hover {
      background-color: #f1f5ff;
    }
    .highlight {
      background-color: #fff9c4 !important;
      transition: background 0.3s ease;
    }
    tfoot {
      background: #f8f9fa;
      font-weight: 600;
    }
  </style>
</head>
<body>
<div class="container py-4">
  <h3>Customer Sales Detail</h3>
  <p class="page-subtitle">Filter sales records by customer, city, and date range</p>

  <!-- Filter Form -->
  <div class="filter-card">
    <div class="row g-2 filter-row">
      <div class="col-md-3">
        <input type="text" id="customerFilter" class="form-control" placeholder="Customer Name">
      </div>
      <div class="col-md-3">
        <input type="text" id="cityFilter" class="form-control" placeholder="City">
      </div>
      <div class="col-md-2">
        <input type="date" id="fromDate" class="form-control">
      </div>
      <div class="col-md-2">
        <input type="date" id="toDate" class="form-control">
      </div>
      <div class="col-md-2 d-grid">
        <button id="resetBtn" class="btn btn-outline-danger">Reset</button>
      </div>
    </div>
  </div>

  <!-- Table -->
  <div class="table-container">
    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle" id="salesTable">
        <thead>
          <tr>
            <th>Customer</th>
            <th>City</th>
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
              <td>{{ $r->customer_name }}</td>
              <td>{{ $r->city }}</td>
              <td>{{ $r->invoice_no }}</td>
              <td>{{ $r->invoice_date }}</td>
              <td>{{ $r->product }}</td>
              <td class="text-end">{{ (float)$r->qty }}</td>
              <td class="text-end">
                {{rtrim(rtrim(number_format($r->price,2), '0'), '.') }}

              </td>
              <td class="text-end">
                {{rtrim(rtrim(number_format($r->line_total,2), '0'), '.') }}

              </td>
            </tr>
          @empty
            <tr><td colspan="8" class="text-center text-muted">No records found</td></tr>
          @endforelse
        </tbody>
        @if($records->count())
        <tfoot>
          <tr>
            <td colspan="7" class="text-end">Total Sales</td>
            <td class="text-end">
              {{rtrim(rtrim(number_format($total,2), '0'), '.') }}

            </td>
          </tr>
        </tfoot>
        @endif
      </table>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const fromDate = document.getElementById("fromDate");
  const toDate = document.getElementById("toDate");
  const cityFilter = document.getElementById("cityFilter");
  const customerFilter = document.getElementById("customerFilter");
  const resetBtn = document.getElementById("resetBtn");
  const rows = document.querySelectorAll("#salesTable tbody tr");

  function filterTable() {
    const from = fromDate.value ? new Date(fromDate.value) : null;
    const to = toDate.value ? new Date(toDate.value) : null;
    const city = cityFilter.value.toLowerCase();
    const customer = customerFilter.value.toLowerCase();

    rows.forEach(row => {
      const cells = row.cells;
      if (cells.length < 8) return; // Skip empty rows

      const rowDate = new Date(cells[3].innerText);
      const cityText = cells[1].innerText.toLowerCase();
      const customerText = cells[0].innerText.toLowerCase();

      const matchesDate = (!from || rowDate >= from) && (!to || rowDate <= to);
      const matchesCity = !city || cityText.includes(city);
      const matchesCustomer = !customer || customerText.includes(customer);

      const show = matchesDate && matchesCity && matchesCustomer;
      row.style.display = show ? "" : "none";
      row.classList.toggle("highlight", show && (city || customer || from || to));
    });
  }

  fromDate.addEventListener("change", filterTable);
  toDate.addEventListener("change", filterTable);
  cityFilter.addEventListener("input", filterTable);
  customerFilter.addEventListener("input", filterTable);

  resetBtn.addEventListener("click", e => {
    e.preventDefault();
    fromDate.value = "";
    toDate.value = "";
    cityFilter.value = "";
    customerFilter.value = "";
    rows.forEach(r => { r.style.display = ""; r.classList.remove("highlight"); });
  });
});
</script>
</body>
</html>
