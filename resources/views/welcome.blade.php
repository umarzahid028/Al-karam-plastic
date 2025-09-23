@extends('layouts.app')
@section('title','Ledger Dashboard')

@push('styles')
<style>
    body {
        background: #f3f4f6;
        font-family: 'Inter', sans-serif;
    }

    /* --- Ledger Card --- */
    .ledger-card {
        position: relative;
        border: none;
        border-radius: 1.25rem;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(8px);
        box-shadow: 0 6px 20px rgba(0,0,0,.08);
        transition: all .35s ease;
        overflow: hidden;
    }
    .ledger-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,.12);
    }

    /* Moving top gradient bar */
    .ledger-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; height: 6px;
        background-size: 300% 100%;
        border-top-left-radius: 1.25rem;
        border-top-right-radius: 1.25rem;
        animation: barSlide 6s linear infinite;
    }
    @keyframes barSlide {
        0%   { background-position: 0% 0%; }
        100% { background-position: 300% 0%; }
    }

    /* Theme Colors */
    .ledger-primary::before {
        background: linear-gradient(90deg,#4f46e5,#6366f1,#818cf8);
    }
    .ledger-success::before {
        background: linear-gradient(90deg,#059669,#10b981,#34d399);
    }
    .ledger-warning::before {
        background: linear-gradient(90deg,#f59e0b,#fbbf24,#f97316);
    }

    /* Content Typography */
    .ledger-card h6 {
        font-size: .9rem;
        letter-spacing: .3px;
        color: #6b7280;
        margin-bottom: .35rem;
    }
    .ledger-card h3 {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    /* Icon */
    .ledger-card .icon {
        font-size: 2.8rem;
        padding: .5rem .6rem;
        border-radius: 1rem;
        background: rgba(255,255,255,0.35);
        color: #374151;
        transition: background .3s ease;
    }
    .ledger-card:hover .icon {
        background: rgba(255,255,255,0.55);
    }

    /* Chart Containers */
    .chart-container .card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 6px 16px rgba(0,0,0,.06);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Ledger Summary --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card ledger-card ledger-primary">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Ledger Sales</h6>
                        <h3>₨ {{ number_format($totalSales) }}</h3>
                    </div>
                    <i class="bi bi-bar-chart-line icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card ledger-card ledger-success">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Ledger Purchases</h6>
                        <h3>₨ {{ number_format($totalPurchases) }}</h3>
                    </div>
                    <i class="bi bi-cart-check icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card ledger-card ledger-warning">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Stock Available</h6>
                        <h3>{{ number_format($totalStock) }} Units</h3>
                    </div>
                    <i class="bi bi-box icon"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="row g-4">
        <div class="col-lg-8 chart-container">
            <div class="card">
                <div class="card-header fw-semibold">Monthly Sales & Purchases</div>
                <div class="card-body">
                    <canvas id="salesPurchaseChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 chart-container">
            <div class="card">
                <div class="card-header fw-semibold">Stock Distribution</div>
                <div class="card-body">
                    <canvas id="stockChart" height="250"></canvas>
                </div>
            </div>
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

    // Line Chart
    const ctxSales = document.getElementById('salesPurchaseChart').getContext('2d');
    const gradientSales = ctxSales.createLinearGradient(0,0,0,300);
    gradientSales.addColorStop(0,'#6366f1');
    gradientSales.addColorStop(1,'#a5b4fc');

    const gradientPurchase = ctxSales.createLinearGradient(0,0,0,300);
    gradientPurchase.addColorStop(0,'#10b981');
    gradientPurchase.addColorStop(1,'#6ee7b7');

    new Chart(ctxSales,{
        type:'line',
        data:{
            labels: months,
            datasets:[
                {
                    label: 'Sales',
                    data: sales,
                    borderColor: '#6366f1',
                    backgroundColor: gradientSales,
                    fill: true,
                    tension: .35,
                    pointRadius: 4,
                    pointBackgroundColor: '#6366f1'
                },
                {
                    label: 'Purchases',
                    data: purchases,
                    borderColor: '#10b981',
                    backgroundColor: gradientPurchase,
                    fill: true,
                    tension: .35,
                    pointRadius: 4,
                    pointBackgroundColor: '#10b981'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { color:'#374151' } },
                tooltip: { backgroundColor: '#1f2937', titleColor:'#fff', bodyColor:'#e5e7eb' }
            },
            scales: {
                y: { beginAtZero:true, ticks:{ color:'#4b5563' }, grid:{ color:'rgba(0,0,0,0.05)' } },
                x: { ticks:{ color:'#4b5563' }, grid:{ color:'rgba(0,0,0,0.05)' } }
            }
        }
    });

    // Doughnut Chart
    const ctxStock = document.getElementById('stockChart').getContext('2d');
    new Chart(ctxStock,{
        type:'doughnut',
        data:{
            labels: stockLabels,
            datasets:[{
                data: stockData,
                backgroundColor: ['#6366f1','#10b981','#f59e0b','#fb7185','#38bdf8','#84cc16'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options:{
            cutout: '65%',
            plugins: {
                legend: { position: 'bottom', labels: { color:'#374151' } },
                tooltip: { backgroundColor: '#1f2937', titleColor:'#fff', bodyColor:'#e5e7eb' }
            }
        }
    });
});
</script>
@endpush
