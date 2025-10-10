@extends('layouts.app')

@section('title', 'Customers List')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .customers-container {
        max-width: 1100px;
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    
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
    .page-header h3 {
       
       letter-spacing: .4px;
       color: #1e293b;
   }
</style>
@endpush

@section('content')
<div class="customers-container">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-person me-2"></i> Customers</h3>
        <a href="{{ route('customers.create') }}" class="btn btn-info text-white">+ Add Customer</a>
    </div>

    <input type="text"
           id="searchInput"
           class="form-control"
           placeholder="Search Customer..."
           onkeyup="filterTable()">

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover align-middle text-center" id="customersTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Opening Balance</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $c)
                <tr class="{{ $c->status === 'inactive' ? 'disabled-row' : '' }}" id="customerRow{{ $c->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $c->customer_code }}</td>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->email ?? '-' }}</td>
                    <td>{{ $c->contact_no ?? '-' }}</td>
                    <td>{{ rtrim(rtrim(number_format($c->opening_balance, 2), '0'), '.') }}</td>
                    <td>
                        <span class="badge bg-{{ $c->status === 'active' ? 'success' : 'secondary' }}" id="statusBadge{{ $c->id }}">
                            {{ ucfirst($c->status) }}
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-status
                        {{ $c->status === 'active' ? 'btn-primary' : 'btn-success' }}"
                        data-id="{{ $c->id }}">
                        {{ $c->status === 'active' ? 'Deactivate' : 'Activate' }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $customers->links('pagination::bootstrap-5') }}
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
    document.querySelectorAll("#customersTable tbody tr").forEach(row => {
        const match = Array.from(row.cells).some(cell =>
            cell.textContent.toLowerCase().includes(input)
        );
        row.style.display = match ? "" : "none";
    });
}

// Handle status toggle
document.querySelectorAll('.btn-status').forEach(btn => {
    btn.addEventListener('click', function () {
        const customerId = this.dataset.id;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const button = this;
        const badge = document.getElementById('statusBadge' + customerId);
        const row = document.getElementById('customerRow' + customerId);

        fetch(`/customers/${customerId}/update-status`, {
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
                    title: `Customer ${data.status === 'active' ? 'Activated' : 'Deactivated'}`,
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
