@extends('layouts.app')

@section('title', 'Expenses Report')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>

.container {
    max-width: 1100px;
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
.invoice-info {
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
.return-qty {
    width: 80px;
}
.totals {
    font-weight: 600;
    font-size: 1.1rem;
}
.btn-return {
    display: flex;
    align-items: center;
    gap: 6px;
    border-radius: 6px;
}

.btn-info {
    background: linear-gradient(135deg, #3b82f6, #497be6);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 18px;    /* bigger */
    font-size: 15px;       /* slightly larger */
    font-weight: 600;
    transition: all 0.2s ease;
}
.btn-info:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(37,99,235,0.25);
}

/* Secondary button (Back) */
.btn-secondary {
    background: #64748b;
    border: none;
    color: #fff;
    border-radius: 8px;
    padding: 10px 18px;
    font-size: 15px;
    font-weight: 600;
    transition: all 0.2s ease;
}
.btn-secondary:hover {
    background: #475569;
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(71,85,105,0.25);
}
@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto;
    }
}
</style>
@endpush

@section('content')
<body>

<div class="container">
    @php
        $baseTotal = $invoice->items->sum('total');
    @endphp

    <!-- Back Button -->
    <button class="btn btn-info mb-3 text-white" onclick="window.history.back()"><i class="bi bi-arrow-left"></i> Back to Invoices</button>

    <!-- Invoice Info -->
    <h3>Invoice: {{ $invoice->invoice_no }}</h3>
    <p class="invoice-info">
        <strong>Buyer:</strong> {{ $invoice->buyer->name ?? $invoice->buyer_id }} |
        <strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M, Y') }} |
        <strong>Current Total:</strong> <span id="current-total">{{ number_format($baseTotal, 2) }}</span> |
        <strong>Return Total:</strong> <span id="return-total" class="text-danger">0.00</span>
    </p>

    @if(session('success'))
        <div class="alert alert-success"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
    @endif

    <!-- Return Form -->
    <form method="POST" action="{{ route('sales_returns.store', $invoice->id) }}">
        @csrf

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Remaining Qty</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                        <th>Return?</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoice->items as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->product->product_name ?? 'N/A' }}</td>
                        <td>
                            <input type="number" name="quantities[{{ $item->id }}]" class="form-control form-control-sm return-qty" 
                            min="0" max="{{ $item->qty }}" value="{{ $item->qty }}">
                        </td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        <td>{{ number_format($item->total, 2) }}</td>
                        <td>
                            <input type="checkbox" class="return-item" name="items[]" value="{{ $item->id }}" data-subtotal="{{ $item->total }}">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No items found in this invoice.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mb-3">
            <label for="remarks" class="form-label">Remarks (optional)</label>
            <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter remarks if any"></textarea>
        </div>

        <button class="btn btn-danger btn-return" type="submit"><i class="bi bi-arrow-counterclockwise"></i> Return Selected Items</button>
    
        {{-- <button class="btn btn-secondary" onclick="window.location.href='{{ url('/') }}'">Back</button> --}}
           
      
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const currentTotalEl = document.getElementById('current-total');
    const returnTotalEl  = document.getElementById('return-total');
    const baseTotal = parseFloat('{{ $baseTotal }}');

    function recalc() {
        let returnTotal = 0;
        document.querySelectorAll('.return-item').forEach(cb => {
            if(cb.checked){
                const row = cb.closest('tr');
                const subtotal = parseFloat(cb.dataset.subtotal);
                const returnQtyInput = row.querySelector('.return-qty');
                const fullQty = parseFloat(returnQtyInput.max);
                const returnQty = parseFloat(returnQtyInput.value) || 0;
                const unitPrice = subtotal / fullQty;
                returnTotal += unitPrice * returnQty;
            }
        });

        let remaining = baseTotal - returnTotal;
        if(remaining < 0) remaining = 0;

        returnTotalEl.textContent = returnTotal.toFixed(2);
        currentTotalEl.textContent = remaining.toFixed(2);
    }

    document.querySelectorAll('.return-item').forEach(cb => cb.addEventListener('change', recalc));
    document.querySelectorAll('.return-qty').forEach(input => input.addEventListener('input', recalc));
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection

