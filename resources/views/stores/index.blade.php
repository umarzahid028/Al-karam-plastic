
@extends('layouts.app')

@section('title', 'Stores List')

@push('styles')
<style>
    .form-select {
        width: 120px;
        display: inline-block;
    }
    .btn-update {
        padding: 4px 12px;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Stores List</h2>

    <a href="{{ route('stores.create') }}" class="btn btn-primary mb-3">Add Store</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Manager ID</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stores as $store)
            <tr>
                <td>{{ $store->store_name }}</td>
                <td>{{ $store->address }}</td>
                <td>{{ $store->phone_number }}</td>
                <td>{{ $store->manager_id }}</td>
                <td>
                    <form action="{{ route('stores.update', $store) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select">
                            <option value="active" {{ $store->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $store->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary btn-update mt-1">Update</button>
                    </form>
                </td>
                <td>
                    <!-- Optionally, add other actions here -->
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
