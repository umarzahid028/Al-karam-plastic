<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Purchase Return - Invoice {{ $purchase->invoice_no }}</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    font-family: Arial, sans-serif;
    background: #f5f7fa;
}
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
.btn-back {
    background: #17a2b8;
    color: white;
    border-radius: 6px;
    margin-bottom: 20px;
}
.btn-back:hover {
    background: #138496;
    color: white;
}
@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto;
    }
}
</style>
</head>
<body>

<div class="container">
    @php
        $baseTotal = 0;
        foreach($purchase->items as $item){
            $unitPrice = $item->unit_price ?? ($item->total_price / max($item->quantity,1));
            $baseTotal += $unitPrice * $item->quantity;
        }
    @endphp

    <!-- Back Button -->
    <button class="btn btn-back mb-3" onclick="window.history.back()">
        <i class="bi bi-arrow-left"></i> Back to Purchases
    </button>

    <!-- Purchase Info -->
    <h3>Invoice: {{ $purchase->invoice_no }}</h3>
    <p class="invoice-info">
        <strong>Supplier:</strong> {{ $purchase->supplier->name ?? $purchase->supplier_id }} |
        <strong>Date:</strong> {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }} |
        <strong>Current Total:</strong> <span id="current-total">{{ number_format($baseTotal, 2) }}</span> |
        <strong>Return Total:</strong> <span id="return-total" class="text-danger">0.00</span>
    </p>

    @if(session('success'))
        <div class="alert alert-success"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
    @endif

    <!-- Return Form -->
    <form method="POST" action="{{ route('purchase_returns.store', $purchase->id) }}">
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
                    @forelse($purchase->items as $i => $item)
                        @php
                            $unitPrice = $item->unit_price ?? ($item->total_price / max($item->quantity,1));
                            $qty = $item->quantity ?? 0;
                            $subtotal = $unitPrice * $qty;
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->rawMaterial->material_name ?? 'N/A' }}</td>
                            <td>
                                <input type="number" name="quantities[{{ $item->id }}]" 
                                       class="form-control form-control-sm return-qty"
                                       min="0" max="{{ $qty }}" value="{{ $qty }}">
                            </td>
                            <td>{{ number_format($unitPrice, 2) }}</td>
                            <td>{{ number_format($subtotal, 2) }}</td>
                            <td>
                                <input type="checkbox" class="return-item" name="items[]" value="{{ $item->id }}" data-subtotal="{{ $subtotal }}">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No items found in this purchase.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mb-3">
            <label for="remarks" class="form-label">Remarks (optional)</label>
            <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter remarks if any"></textarea>
        </div>

        <button class="btn btn-danger btn-return" type="submit">
            <i class="bi bi-arrow-counterclockwise"></i> Return Selected Items
        </button>
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
</body>
</html>
