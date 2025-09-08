<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Business Summary</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background-color: #f8f9fa;
      font-family: Arial, sans-serif;
    }
    .summary-card {
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .summary-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    }
    .summary-card h6 {
      font-size: 0.95rem;
      color: #6c757d;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .summary-card .fs-4 { font-size: 1.75rem; }
    .icon { font-size: 1.8rem; margin-right: 10px; }
    .text-sales { color: #0d6efd; }
    .text-purchases { color: #198754; }
    .text-profit { color: #dc3545; }
    .text-info { color: #0dcaf0; }
    .section-title {
      margin-top: 50px;
      margin-bottom: 20px;
      font-weight: bold;
      font-size: 1.2rem;
      color: #333;
    }
    .table-responsive { max-height: 350px; overflow-y: auto; }
  </style>
</head>
<body>
<div class="container mt-5">
  <h3 class="mb-4 text-center">Business Summary</h3>

  <!-- Summary Cards -->
  <div class="row g-4">
    <!-- Total Sales -->
    <div class="col-md-3">
      <a href="{{ route('reports.sales_detail') }}" class="text-decoration-none">
        <div class="summary-card bg-white d-flex align-items-center p-3 shadow-sm">
          <div class="icon text-sales me-3"><i class="bi bi-currency-dollar fs-2"></i></div>
          <div>
            <h6 class="mb-1">Total Sales</h6>
            <div class="fs-4 fw-bold text-sales">{{ number_format($totalSales,2) }}</div>
          </div>
        </div>
      </a>
    </div>

    <!-- Total Purchases -->
    <div class="col-md-3">
      <a href="{{ route('reports.purchase_detail') }}" class="text-decoration-none">
        <div class="summary-card bg-white d-flex align-items-center p-3 shadow-sm">
          <div class="icon text-purchases me-3"><i class="bi bi-basket fs-2"></i></div>
          <div>
            <h6 class="mb-1">Total Purchases</h6>
            <div class="fs-4 fw-bold text-purchases">{{ number_format($totalPurchases,2) }}</div>
          </div>
        </div>
      </a>
    </div>

    <!-- Profit / Loss -->
    <div class="col-md-3">
      <div class="summary-card bg-white d-flex align-items-center p-3 shadow-sm">
        <div class="icon text-profit me-3"><i class="bi bi-graph-up fs-2"></i></div>
        <div>
          <h6 class="mb-1">Profit / Loss</h6>
          <div class="fs-4 fw-bold text-profit">{{ number_format($profitLoss,2) }}</div>
        </div>
      </div>
    </div>

    <!-- Total Stocks -->
    <div class="col-md-3">
      <a href="{{route('reports.stock')}}" class="text-decoration-none">
        <div class="summary-card bg-white d-flex align-items-center p-3 shadow-sm">
          <div class="icon text-info me-3"><i class="bi bi-box-seam fs-2"></i></div>
          <div>
            <h6 class="mb-1">Total Stocks</h6>
            {{-- <div class="fs-4 fw-bold text-info">{{ number_format($totalStocks,0) }}</div> --}}
          </div>
        </div>
      </a>
    </div>
  </div>

  <!-- Charts Section -->
  <div class="section-title">Sales vs Purchases (Last 12 Months)</div>
  <canvas id="summaryChart" height="100"></canvas>

  <!-- Recent Transactions -->
  <div class="section-title">Recent Transactions</div>
  <div class="row">
    <div class="col-md-6">
      <h6>Latest Sales</h6>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-sm">
          <thead>
            <tr>
              <th>Invoice #</th>
              <th>Customer</th>
              <th class="text-end">Amount</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentSales as $sale)
            <tr>
              <td>{{ $sale->invoice_no }}</td>
              <td>{{ $sale->customer_name }}</td>
              <td class="text-end">{{ number_format($sale->total_amount,2) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="col-md-6">
      <h6>Latest Purchases</h6>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-sm">
          <thead>
            <tr>
              <th>Invoice #</th>
              <th>Supplier</th>
              <th class="text-end">Amount</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentPurchases as $purchase)
            <tr>
              <td>{{ $purchase->invoice_no }}</td>
              <td>{{ $purchase->supplier_name }}</td>
              <td class="text-end">{{ number_format($purchase->total_amount,2) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
const ctx = document.getElementById('summaryChart').getContext('2d');
const summaryChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [
            {
                label: 'Sales',
                data: {!! json_encode($chartSales) !!},
                backgroundColor: 'rgba(13, 110, 253, 0.7)'
            },
            {
                label: 'Purchases',
                data: {!! json_encode($chartPurchases) !!},
                backgroundColor: 'rgba(25, 135, 84, 0.7)'
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
</body>
</html>
