@extends('layouts.app')

@section('title', 'Add Expense')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #f4f6f9;
}
.container {
    max-width: 900px;
    margin: 40px auto;
}
.card {
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}
.card-header {
    color: black;
    font-weight: 600;
    font-size: 1.2rem;
    border-radius: 12px 12px 0 0;
    padding: 15px 20px;
}
.form-label {
    font-weight: 500;
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Add Expense</div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="expense_category" class="form-select" required>
                            <option value="">Select</option>
                            <option value="Utilities">Utilities</option>
                            <option value="Rent">Rent</option>
                            <option value="Salaries">Salaries</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Travel">Travel</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Expense #</label>
                        <input type="text" name="expense_no" class="form-control" value="{{ $prefix ?? '' }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Expense Type</label>
                        <input type="text" name="expense_type" class="form-control" placeholder="e.g. Electricity" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Vendor / Paid To</label>
                        <input type="text" name="vendor" class="form-control" placeholder="Vendor/Person">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Credit Card">Credit Card</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Amount (PKR)</label>
                        <input type="number" name="amount" step="0.01" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Date</label>
                        <input type="date" name="expense_date" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Reference No</label>
                        <input type="text" name="reference_no" class="form-control" placeholder="Invoice/Ref No">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Attachment</label>
                        <input type="file" name="attachment" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Approved By</label>
                        <input type="text" name="approved_by" class="form-control" placeholder="Manager Name" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Salesperson</label>
                        <input type="text" name="salesperson" class="form-control" placeholder="Salesperson Name">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" placeholder="Notes">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
