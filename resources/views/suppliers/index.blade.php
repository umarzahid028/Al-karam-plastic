@extends('layouts.app')

@section('title','Suppliers List')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .suppliers-container {
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
    .btn-status {
        font-weight: 600;
        border-radius: 8px;
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
</style>
@endpush

@section('content')
<div class="suppliers-container">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-people me-2"></i> Suppliers</h3>
        <a href="{{ route('suppliers.create') }}" class="btn btn-info text-white">+ Add Supplier</a>
    </div>

    <input type="text"
           id="searchInput"
           class="form-control"
           placeholder="Search Supplier..."
           onkeyup="filterTable()">

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover align-middle text-center" id="suppliersTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Company</th>
                    <th>Contact Name</th>
                    <th>Email</th>
                    <th>Contact No</th>
                    <th>Opening Balance</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $s)
                <tr class="{{ $s->status === 'inactive' ? 'disabled-row' : '' }}" id="supplierRow{{ $s->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $s->supplier_code }}</td>
                    <td>{{ $s->company_name }}</td>
                    <td>{{ $s->name }}</td>
                    <td>{{ $s->email ?? '-' }}</td>
                    <td>{{ $s->contact_no ?? '-' }}</td>
                    <td>{{ rtrim(rtrim(number_format($s->opening_balance, 2), '0'), '.') }}</td>
                    <td>
                        <span class="badge bg-{{ $s->status === 'active' ? 'success' : 'secondary' }}" id="statusBadge{{ $s->id }}">
                            {{ ucfirst($s->status) }}
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-status
                        {{ $s->status === 'active' ? 'btn-primary' : 'btn-success' }}"
                        data-id="{{ $s->id }}">
                        {{ $s->status === 'active' ? 'Deactivate' : 'Activate' }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $suppliers->links('pagination::bootstrap-5') }}
    </div>

    <div class="d-flex justify-content-start mt-3">
        <a href="{{ url('/') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    document.querySelectorAll("#suppliersTable tbody tr").forEach(row => {
        const match = Array.from(row.cells).some(cell =>
            cell.textContent.toLowerCase().includes(input)
        );
        row.style.display = match ? "" : "none";
    });
}

// Status toggle button
document.querySelectorAll('.btn-status').forEach(btn => {
    btn.addEventListener('click', function () {
        const supplierId = this.dataset.id;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const button = this;
        const badge = document.getElementById('statusBadge' + supplierId);
        const row = document.getElementById('supplierRow' + supplierId);

        fetch(`/suppliers/${supplierId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                badge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                badge.className = 'badge bg-' + (data.status === 'active' ? 'success' : 'secondary');
                row.classList.toggle('disabled-row', data.status !== 'active');

                button.textContent = data.status === 'active' ? 'Deactivate' : 'Activate';
                button.classList.toggle('btn-primary', data.status === 'active');
                button.classList.toggle('btn-success', data.status !== 'active');

                Swal.fire({
                    icon: 'success',
                    title: `Supplier ${data.status === 'active' ? 'Activated' : 'Deactivated'}`,
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please refresh the page.'
            });
        });
    });
});
</script>
@endpush
