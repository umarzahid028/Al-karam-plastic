@extends('layouts.app')

@section('title','User List')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    /* Container styling */
    .user-container {
        max-width: 1100px;
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .page-header h3 {
        font-weight: 700;
        letter-spacing: .4px;
        color: #1e293b;
    }
    /* Table hover effect */
    .table-hover tbody tr:hover { background-color: #f1f1f1; }

    /* Disabled row styling */
    .disabled-row {
        opacity: 0.5;
    }

    .btn-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .btn-info:hover,
    .btn-info:focus {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    }
    #searchInput {
        margin-bottom: 15px;
        max-width: 320px;
    }
    .table thead th {
        background: #f1f5f9;
        color: #334155;
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
    }
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Search input */
    #searchInput { 
        margin-bottom: 15px; 
        max-width: 300px;
    }

    @media (max-width: 768px) {
        .table-responsive { overflow-x: auto; }
    }
    .page-header h3 {
       
       letter-spacing: .4px;
       color: #1e293b;
   }
</style>
@endpush

@section('content')
<div class="user-container">
    <div class=" d-flex justify-content-between align-items-center mb-3">
        <h3 class="page-header">
            <i class="bi bi-people me-2"></i> User List
        </h3>
        
        <div class="d-flex gap-2">
            <a href="{{ route('users.create') }}" class="btn btn-primary text-white">+ Add User</a>
            <a href="{{ url('/') }}" class="btn btn-secondary text-white">Back</a>
        </div>
    </div>

    <!-- Search input -->
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search User..." onkeyup="filterTable()">

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle" id="usersTable">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
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
                        <button type="button" class="btn btn-sm toggleStatusBtn 
                            {{ $user->status === 'inactive' ? 'btn-success' : 'btn-primary' }}"
                            data-id="{{ $user->id }}">
                            {{ $user->status === 'inactive' ? 'Activate' : 'Deactivate' }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Search filter
    function filterTable() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        const rows = document.querySelectorAll("#usersTable tbody tr");
        rows.forEach(row => {
            row.style.display = Array.from(row.cells).some(cell =>
                cell.textContent.toLowerCase().includes(input)
            ) ? "" : "none";
        });
    }

    // Toggle user status
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
                body: JSON.stringify({}),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    let row = document.getElementById('userRow' + id);
                    let badge = document.getElementById('statusBadge' + id);

                    if (data.status === 'inactive') {
                        badge.className = 'badge bg-secondary';
                        badge.textContent = 'Inactive';
                        row.classList.add('disabled-row');
                        btn.textContent = 'Activate';
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-success');
                    } else {
                        badge.className = 'badge bg-success';
                        badge.textContent = 'Active';
                        row.classList.remove('disabled-row');
                        btn.textContent = 'Deactivate';
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-primary');
                    }

                    Swal.fire({
                        icon: 'success',
                        title: `User ${data.status === 'active' ? 'Activated' : 'Deactivated'}`,
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
