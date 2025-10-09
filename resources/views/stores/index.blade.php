@extends('layouts.app')
@section('title', 'User List')
@push('styles')
@endpush
@section('content')
<a href="{{ route('stores.create') }}" class="btn btn-primary mb-3">Add Store</a>

<table class="table table-bordered">
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
            <td>{{ $store->status }}</td>
            <td>
                <a href="{{ route('stores.edit', $store) }}" class="btn btn-sm btn-warning">Edit Status</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection