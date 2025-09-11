<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Return</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">

    <h3>Invoice: {{ $purchase->invoice_no }}</h3>

    @php
        $baseTotal = 0;
        foreach($purchase->items as $item){
            $unitPrice = $item->unit_price ?? ($item->total_price / max($item->quantity,1));
            $baseTotal += $unitPrice * $item->quantity;
        }
    @endphp

    <p>
        Supplier: {{ $purchase->supplier->name ?? $purchase->supplier_id }} |
        Date: {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }} |
        Current Total: <span id="current-total">{{ number_format($baseTotal, 2) }}</span>
    </p>

    <p>Return Total: <span id="return-total">0.00</span></p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('purchase_returns.store', $purchase->id) }}">
        @csrf
        <table class="table table-bordered mt-4">
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
            @foreach($purchase->items as $i => $item)
                @php
                    $unitPrice = $item->unit_price ?? ($item->total_price / max($item->quantity,1));
                    $qty = $item->quantity ?? 0;
                    $subtotal = $unitPrice * $qty;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->rawMaterial->material_name ?? 'N/A' }}</td>
                    <td>
                        <input type="number" name="quantities[{{ $item->id }}]" class="form-control form-control-sm return-qty"
                               min="0" max="{{ $qty }}" value="{{ $qty }}" style="width:70px; display:inline-block;">
                    </td>
                    <td>{{ number_format($unitPrice, 2) }}</td>
                    <td>{{ number_format($subtotal, 2) }}</td>
                    <td class="text-center">
                        <input type="checkbox" class="return-item" name="items[]" value="{{ $item->id }}" data-subtotal="{{ $subtotal }}">
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="mb-3">
            <label>Remarks (optional)</label>
            <textarea name="remarks" class="form-control"></textarea>
        </div>

        <button class="btn btn-danger">Return Selected Items</button>
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

                // Read quantity from input
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
</body>
</html>
