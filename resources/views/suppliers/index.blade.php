@extends('layouts.app')

@section('title', 'Suppliers List')

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
/* Primary dashboard-style button */
.btn-info {
    background: linear-gradient(135deg, #3b82f6, #497be6);
    border: none;
    color: #fff;
    font-weight: 600;
    padding: 10px 20px;    /* bigger */
    border-radius: 8px;
    font-size: 15px;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.btn-info:hover,
.btn-info:focus {
    background: linear-gradient(135deg, #2563eb 0%, #3560d7 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(29, 78, 216, 0.35);
}

/* Secondary button (Back) */
.btn-secondary {
    background: #64748b;
    border: none;
    color: #fff;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 15px;
    transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.2s ease;
}
.btn-secondary:hover {
    background: #475569;
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(71, 85, 105, 0.3);
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
</head>
@endpush

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">Suppliers</h3>
        <button class="btn btn-info text-white" onclick="window.location.href='/suppliers/create'">
            + Add Supplier
        </button>
        
    </div>

    <!-- Search & Filter -->
    <div class="d-flex justify-content-between mb-3">
        <input type="text" id="searchInput" 
               class="form-control w-50" 
               placeholder="Search Supplier..." 
               onkeyup="filterTable()">
        
        <select id="statusFilter" 
                class="form-select w-25" 
                onchange="filterTable()">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="on hold">On Hold</option>
        </select>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle" id="suppliersTable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Company</th>
                    <th>Contact Name</th>
                    <th>Email</th>
                    <th>Contact No</th>
                    <th>Status</th>
                    <th>Opening Balance</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $s)
                    <tr>
                        <td>{{  $suppliers->firstItem() + $loop->index }}</td>
                        <td>{{ $s->supplier_code }}</td>
                        <td>{{ $s->company_name }}</td>
                        <td>{{ $s->name }}</td>
                        <td>{{ $s->email ?? '-' }}</td>
                        <td>{{ $s->contact_no ?? '-' }}</td>
                        <td>
                            <select class="form-select status-select form-select-sm status-select" data-id="{{ $s->id }}">
                                <option value="active"   {{ $s->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $s->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="on hold"  {{ $s->status == 'on hold' ? 'selected' : '' }}>On Hold</option>
                            </select>
                        </td>
                        <td>
                            {{rtrim(rtrim(number_format($s->opening_balance, 2), '0'), '.') }}
                        </td>
                        <td class="text-center text-white">
                            <a href="/suppliers/{{ $s->id }}" class="btn btn-sm btn-info text-white">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
        <div class="d-flex justify-content-end mt-3">
            {{ $suppliers->links('pagination::bootstrap-5') }}
        </div>
        <div class="d-flex justify-content-between align-items-right mb-3">
            
            <button class="btn btn-secondary" onclick="window.location.href='/'">
                Back
            </button>
            </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const statusFilter = document.getElementById("statusFilter").value.toLowerCase();
    const rows = document.querySelectorAll("#suppliersTable tbody tr");

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
        const supplierId = this.getAttribute('data-id');
        const newStatus = this.value;

        fetch(`/suppliers/${supplierId}/update-status`, {
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
                alert("Update failed ");
            }
        })
        .catch(err => console.error("Error:", err));
    });
});


</script>
@endpush