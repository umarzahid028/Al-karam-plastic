@extends('layouts.app')

@section('title', 'User List')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .disabled-row {
        opacity: 0.5;
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">User List</h2>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact No</th>
                <th>Salary</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="{{ $user->status === 'inactive' ? 'disabled-row' : '' }}" id="userRow{{ $user->id }}">
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->contact_no ?? '-' }}</td>
                    <td>{{ $user->salary ?? '-' }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'secondary' }}" id="statusBadge{{ $user->id }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary toggleStatusBtn"
                        data-id="{{ $user->id }}"
                        {{ $user->status === 'inactive' ? 'disabled' : '' }}>
                        Deactivate
                    </button>
                    
                    
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.toggleStatusBtn').forEach(btn => {
    btn.addEventListener('click', function () {
        let id = this.dataset.id;
        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/users/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: JSON.stringify({}), // required even if empty for POST
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                let row = document.getElementById('userRow' + id);
                let badge = document.getElementById('statusBadge' + id);

                badge.className = 'badge bg-secondary';
                badge.textContent = 'Inactive';
                row.classList.add('disabled-row');
                btn.disabled = true;

                Swal.fire({
                    icon: 'success',
                    title: 'User Deactivated',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Not Allowed',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please refresh and try again.',
            });
        });
    });
});
</script>
@endpush
