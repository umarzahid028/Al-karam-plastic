@extends('layouts.app')

@section('title', 'Generate Gate Pass')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #f4f6f9;
}
.container {
    max-width: 800px;
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
.select2-container .select2-selection--single {
    height: 42px !important;
    line-height: 42px !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    padding-left: 10px;
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Generate Gate Pass</div>
        <div class="card-body">
            <form action="{{ route('gatepass.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    {{-- Invoice Select --}}
                    <div class="col-md-6">
                        <label for="invoice_id" class="form-label">Select Invoice</label>
                        <select name="invoice_id" id="invoice_id" class="form-select" required>
                            <option value="">-- Select Invoice --</option>
                            @foreach($invoices as $invoice)
                                <option value="{{ $invoice->id }}">Invoice #{{ $invoice->invoice_no }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- User Select --}}
                    <div class="col-md-6">
                        <label for="user_id" class="form-label">Select User</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary px-4">Generate Pass</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary px-4">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#invoice_id').select2({ placeholder: "-- Select Invoice --", allowClear: true });
    $('#user_id').select2({ placeholder: "-- Select User --", allowClear: true });
});
</script>
@endpush
