@extends('layouts.app')

@section('title', 'User List')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { 
    font-family: Arial, sans-serif; 
    background:#f5f7fa;
 }
.container {
     max-width: 1100px; 
     margin: 50px auto; 
     background: white; 
     padding: 25px; 
     border-radius: 12px; 
     box-shadow: 0 6px 25px rgba(0,0,0,0.1);
    }
.table-hover tbody tr:hover {
     background-color: #f9f9f9;
     }
/* Primary button (Add User, View) */
.btn-info {
    background: linear-gradient(135deg, #3b82f6, #497be6);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 18px;    /* bigger */
    font-size: 15px;       /* slightly larger */
    font-weight: 600;
    transition: all 0.2s ease;
}
.btn-info:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(37,99,235,0.25);
}

/* Secondary button (Back) */
.btn-secondary {
    background: #64748b;
    border: none;
    color: #fff;
    border-radius: 8px;
    padding: 10px 18px;
    font-size: 15px;
    font-weight: 600;
    transition: all 0.2s ease;
}
.btn-secondary:hover {
    background: #475569;
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(71,85,105,0.25);
}

.badge { 
    font-size: 0.85rem; 
    padding: 6px 10px; 
}
#searchInput { 
    max-width: 300px; 
}
thead th { 
    position: sticky; 
    top: 0; 
    background: #f8f9fa; 
    z-index: 2; }
.status-select {
    min-width: 110px;  
}
</style>
@endpush
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">Users</h3>
        <button class="btn btn-info text-white" onclick="window.location.href='/users/create'">
            + Add User
        </button>
    </div>

    <!-- Search & Filter -->
    <div class="d-flex justify-content-between mb-3">
        <input type="text" id="searchInput" 
               class="form-control w-50" 
               placeholder="Search User..." 
               onkeyup="filterTable()">
        
        <select id="statusFilter" 
                class="form-select w-25" 
                onchange="filterTable()">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle" id="usersTable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact No</th>
                    <th>Salary</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                    <tr>
                        <td>{{ $users->firstItem() + $loop->index }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->contact_no ?? '-' }}</td>
                        <td>
                            {{rtrim(rtrim(number_format($u->salary,2), '0'), '.') }}
                           </td>
                        <td>{{ ucfirst($u->role) }}</td>
                        <td>
                            <select class="form-select form-select-sm status-select" data-id="{{ $u->id }}">
                                <option value="active"   {{ $u->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $u->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <a href="/users/{{ $u->id }}" class="text-white btn btn-info">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end mt-3">
            {{  $users->links('pagination::bootstrap-5') }}
        </div>
        <div class="d-flex justify-content-between mt-3">
            <button class="btn btn-secondary" onclick="window.location.href='/'">
                Back
            </button>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const statusFilter = document.getElementById("statusFilter").value.toLowerCase();
    const rows = document.querySelectorAll("#usersTable tbody tr");

    rows.forEach(row => {
        const textMatch = Array.from(row.cells).some(cell =>
            cell.textContent.toLowerCase().includes(input)
        );
        const status = row.querySelector(".status-select")?.value.toLowerCase();
        const statusMatch = !statusFilter || status === statusFilter;
        row.style.display = (textMatch && statusMatch) ? "" : "none";
    });
}

document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function() {
        const userId = this.getAttribute('data-id');
        const newStatus = this.value;

        fetch(`/users/${userId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                console.log("Status updated to " + data.status);
            } else {
                alert("Update failed");
            }
        })
        .catch(err => console.error("Error:", err));
    });
});
</script>
@endpush
