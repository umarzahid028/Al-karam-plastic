@extends('layouts.app')
@section('title','Dashboard')

@push('styles')
<style>
body{
  background:#f6f8fa;
  font-family:'Inter',sans-serif;
}

.ledger-card{
  border:none;
  border-radius:1rem;
  background:#fff;
  box-shadow:0 4px 20px rgba(0,0,0,.08);
  transition:.25s ease;
  padding:2rem;
  min-height:220px;
  display:flex;
  justify-content:space-between;
  align-items:center;
}
.ledger-card:hover{
  transform:translateY(-4px);
  box-shadow:0 8px 28px rgba(0,0,0,.12);
}
.ledger-card h6{
  color:#6b7280;
  letter-spacing:.4px;
  margin-bottom:.5rem;
}
.ledger-card h3{
  font-size:2.3rem;
  font-weight:700;
  margin:0;
  color:#111827;
}
.ledger-icon{
  width:70px;height:70px;
  border-radius:50%;
  display:flex;
  justify-content:center;
  align-items:center;
  font-size:2rem;
  color:#fff;
}
.ledger-primary .ledger-icon{ background:#6366f1; }
.ledger-success .ledger-icon{ background:#10b981; }
.ledger-warning .ledger-icon{ background:#f59e0b; }

.card-header{
  background:#fff;
  font-weight:600;
  border-bottom:1px solid #e5e7eb;
}
</style>
@endpush

@section('content')
<div class="container-xl py-4">

  {{-- Page Heading --}}
 {{-- Page Heading --}}
<div class="mb-4">
  <h4 class="fw-bold" style="letter-spacing:.5px;">
    Admin Dashboard
  </h4>
  <p class="text-muted">Overview of sales, purchases and current stock</p>
</div>


  <div class="row g-4 mb-5">
    <div class="col-md-4">
      <div class="ledger-card ledger-primary">
        <div>
          <h6>Total Sales</h6>
          <h3>₨ {{ number_format($totalSales) }}</h3>
        </div>
        <div class="ledger-icon"><i class="bi bi-bar-chart-line"></i></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="ledger-card ledger-success">
        <div>
          <h6>Total Purchases</h6>
          <h3>₨ {{ number_format($totalPurchases) }}</h3>
        </div>
        <div class="ledger-icon"><i class="bi bi-cart-check"></i></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="ledger-card ledger-warning">
        <div>
          <h6>Stock Available</h6>
          <h3>{{ number_format($totalStock) }} Units</h3>
        </div>
        <div class="ledger-icon"><i class="bi bi-box"></i></div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header">Monthly Sales & Purchases</div>
        <div class="card-body">
          <canvas id="salesPurchaseChart" height="120"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card shadow-sm">
        <div class="card-header">Stock Distribution</div>
        <div class="card-body">
          <canvas id="stockChart" height="250"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-lg-6">
  <div class="card shadow-sm h-100">
    <div class="card-header fw-bold">🚨 Low Stock Alerts</div>
    <div class="card-body">
      @forelse($lowStockProducts as $p)
        <div class="mb-4">
          <div class="d-flex justify-content-between">
            <span class="fw-semibold">{{ $p->product_name }}</span>
            <span class="badge bg-danger">{{ $p->stock }} left</span>
          </div>
          <div class="progress mt-2" style="height:6px;">
            <div class="progress-bar bg-danger" 
                 role="progressbar" 
                 style="width: {{ $p->stock > 20 ? 100 : max(5, $p->stock*5) }}%;" 
                 aria-valuenow="{{ $p->stock }}" 
                 aria-valuemin="0" 
                 aria-valuemax="20">
            </div>
          </div>
        </div>
      @empty
        <p class="text-muted text-center mb-0">✅ All products are sufficiently stocked</p>
      @endforelse
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const months      = @json($months);
  const sales       = @json($sales);
  const purchases   = @json($purchases);
  const stockLabels = @json($stockLabels);
  const stockData   = @json($stockData);

  const ctxSales = document.getElementById('salesPurchaseChart').getContext('2d');
  new Chart(ctxSales,{
    type:'line',
    data:{
      labels:months,
      datasets:[
        { label:'Sales', data:sales, borderColor:'#6366f1', backgroundColor:'rgba(99,102,241,.2)', fill:true, tension:.4 },
        { label:'Purchases', data:purchases, borderColor:'#10b981', backgroundColor:'rgba(16,185,129,.2)', fill:true, tension:.4 }
      ]
    },
    options:{
      responsive:true,
      plugins:{ legend:{ position:'bottom' } },
      scales:{
        y:{ beginAtZero:true, grid:{ color:'rgba(0,0,0,.05)' } },
        x:{ grid:{ color:'rgba(0,0,0,.05)' } }
      }
    }
  });

  const ctxStock = document.getElementById('stockChart').getContext('2d');
  new Chart(ctxStock,{
    type:'doughnut',
    data:{
      labels:stockLabels,
      datasets:[{
        data:stockData,
        backgroundColor:['#6366f1','#10b981','#f59e0b','#fb7185','#38bdf8','#84cc16'],
        borderColor:'#fff',
        borderWidth:2
      }]
    },
    options:{ cutout:'65%', plugins:{ legend:{ position:'bottom' } } }
  });
});
</script>
@endpush
