@extends('layouts.app')

@section('title', 'Expense Details')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    font-family: Arial, sans-serif;
    background: #f5f7fa;
}
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
    margin-bottom: 20px;
}
.detail-label {
    font-weight: 600;
    color: #495057;
}
.detail-value {
    color: #212529;
}
.btn-back {
    background: #17a2b8;
    color: white;
    border-radius: 6px;
}
.btn-back:hover {
    background: #138496;
    color: white;
}
.amount-box {
    font-size: 1.2rem;
    font-weight: bold;
    color: #dc3545;
}
@media print {
    body { background: white !important; }
    .btn { display: none !important; }
    .container { box-shadow: none !important; margin: 0; padding: 0; max-width: 100%; }
}
</style>
@endpush

@section('content')
<div class="container">

    <!-- Back & Print Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-back" onclick="window.history.back()">← Back to Expenses</button>
        <button class="btn btn-outline-dark" onclick="window.print()">Print</button>
    </div>

    <!-- Expense Info -->
    <h3>Expense Details</h3>

    <div class="row mb-3">
        <div class="col-md-6"><span class="detail-label">Date:</span> <span class="detail-value">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M, Y') }}</span></div>
        <div class="col-md-6"><span class="detail-label">Category:</span> <span class="detail-value">{{ $expense->expense_category ?? 'N/A' }}</span></div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6"><span class="detail-label">Type:</span> <span class="detail-value">{{ $expense->expense_type }}</span></div>
        <div class="col-md-6"><span class="detail-label">Vendor / Paid To:</span> <span class="detail-value">{{ $expense->vendor ?? 'N/A' }}</span></div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6"><span class="detail-label">Payment Method:</span> <span class="detail-value">{{ $expense->payment_method ?? 'N/A' }}</span></div>
        <div class="col-md-6"><span class="detail-label">Reference No:</span> <span class="detail-value">{{ $expense->reference_no ?? 'N/A' }}</span></div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6"><span class="detail-label">Approved By:</span> <span class="detail-value">{{ $expense->approved_by ?? 'N/A' }}</span></div>
        <div class="col-md-6"><span class="detail-label">Salesperson:</span> <span class="detail-value">{{ $expense->salesperson ?? 'N/A' }}</span></div>
    </div>

    <div class="mb-3">
        <span class="detail-label">Description:</span>
        <p class="detail-value mb-0">{{ $expense->description ?? 'N/A' }}</p>
    </div>

    <div class="mb-3">
        <span class="detail-label">Amount:</span>
        <div class="amount-box">PKR {{ number_format($expense->amount, 2) }}</div>
    </div>

    @if($expense->attachment)
        <div class="mb-3">
            <span class="detail-label">Attachment:</span>
            <a href="{{ asset('storage/' . $expense->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">📎 View File</a>
        </div>
    @endif

</div>
@endsection
