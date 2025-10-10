@extends('layouts.app')

@section('title','Stores List')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .store-container {
        max-width: 1100px;
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
   
    .btn-status {
        font-weight:600;
        border-radius:8px;
        transition: transform 0.15s ease;
    }
    .btn-status:hover { transform: translateY(-2px); }
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
    .table-hover tbody tr:hover { background-color: #f8fafc; }
    .disabled-row { opacity: 0.5; }
    .page-header h3 {
       
       letter-spacing: .4px;
       color: #1e293b;
   }
</style>
@endpush

@section('content')
<div class="store-container">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h3 class="page-header">
            <i class="bi bi-shop me-2"></i> Stores
        </h3>
        <a href="{{ route('stores.create') }}" class="btn btn-primary">+ Add Store</a>
    </div>

    <input type="text" id="searchInput" class="form-control" placeholder="Search store..." onkeyup="filterTable()">

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover align-middle text-center" id="storesTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Manager ID</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stores as $index => $store)
                <tr class="{{ $store->status === 'inactive' ? 'disabled-row' : '' }}" id="storeRow{{ $store->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $store->store_name }}</td>
                    <td>{{ $store->address }}</td>
                    <td>{{ $store->phone_number }}</td>
                    <td>{{ $store->manager_id }}</td>
                    <td>
                        <span class="badge bg-{{ $store->status == 'active' ? 'success' : 'secondary' }}" id="statusBadge{{ $store->id }}">
                            {{ ucfirst($store->status) }}
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-status
                        {{ $store->status === 'inactive' ? 'btn-success' : 'btn-primary' }}"
                        data-id="{{ $store->id }}">
                        {{ $store->status === 'inactive' ? 'Activate' : 'Deactivate' }}
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
function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    document.querySelectorAll("#storesTable tbody tr").forEach(row => {
        const match = Array.from(row.cells).some(cell =>
            cell.textContent.toLowerCase().includes(input)
        );
        row.style.display = match ? "" : "none";
    });
}

document.querySelectorAll('.btn-status').forEach(btn => {
    btn.addEventListener('click', function () {
        let id = this.dataset.id;
        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/stores/${id}/toggle-status`, {
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
                let row = document.getElementById('storeRow' + id);
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
                    title: `Store ${data.status === 'active' ? 'Activated' : 'Deactivated'}`,
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
