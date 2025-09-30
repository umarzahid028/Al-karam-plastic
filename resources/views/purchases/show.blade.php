@extends('layouts.app')

@section('title', 'Purchase Detail')

@push('styles')
<style>

.container {
    max-width: 1000px;
    margin: 50px auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
h3 {
    color: #333;
    margin-bottom: 10px;
}
.purchase-info {
    font-size: 1rem;
    color: #555;
    margin-bottom: 20px;
}
.table thead {
    background-color: #6366f1;
    color: white;
}
.table-hover tbody tr:hover {
    background-color: #f1f1f1;
}

       /* Primary dashboard-style button */
       .btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: none;
    color: #fff;
    font-weight: 600;
    padding: 12px 28px;       /* bigger button */
    border-radius: 8px;
    font-size: 15px;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.btn-primary:hover,
.btn-primary:focus {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(29, 78, 216, 0.35);
}
@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto;
    }
}
</style>
@endpush
@section('content')
<div class="container">

    <!-- Back Button -->
    <button class="btn btn-primary mb-3" onclick="window.history.back()">← Back to Purchases</button>

    <!-- Purchase Info -->
    <h3>Purchase: {{ $purchase->purchase_code }}</h3>
    <p class="purchase-info">
        <strong>Supplier:</strong> {{ $purchase->supplier->name ?? $purchase->supplier_id }} |
        <strong>Date:</strong> {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }} |
        <strong>Total Amount:</strong> {{ number_format($purchase->total_amount, 2) }} |
        <strong>Status:</strong> {{ ucfirst($purchase->status) }}
    </p>

    <!-- Items Table -->
    <h4 class="mt-4 mb-3">Purchase Items</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->material->material_name ?? 'N/A' }}</td>

                    <td>{{ $item->quantity }}</td>
                    <td>{{ rtrim(rtrim(number_format($item->total_price, 2, '.', ''), '0'), '.') }}</td>
<td>{{ rtrim(rtrim(number_format($item->quantity * $item->total_price, 2, '.', ''), '0'), '.') }}</td>

                    {{-- <td>{{ $item->description ?? '-' }}</td> --}}
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No items found for this purchase.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
