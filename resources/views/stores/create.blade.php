@extends('layouts.app')

@section('title', 'Add Store')

@push('styles')
<style>
    .form-label {
        font-weight: 500;
    }
    .form-control, .form-select {
        border-radius: 8px;
    }
    .btn-success {
        border-radius: 8px;
        padding: 8px 20px;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Add New Store</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('stores.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Store Name <span class="text-danger">*</span></label>
            <input type="text" name="store_name" class="form-control" required value="{{ old('store_name') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Manager</label>
            <select name="manager_id" class="form-select">
                <option value="">-- Select Manager --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('manager_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} (ID: {{ $user->id }})
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Leave empty if store has no manager.</small>
        </div>

        <button type="submit" class="btn btn-success">Add Store</button>
        <a href="{{ route('stores.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection
