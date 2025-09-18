<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customers List</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
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
.btn-info { 
    background:#17a2b8; 
    color:white; 
    border-radius:6px; 
}
.btn-info:hover { 
    background:#138496; 
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
    z-index: 2; 
}
.status-select {
    min-width: 110px;  
}
</style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">Customers</h3>
        <div class="d-flex gap-2">
            <button class="btn btn-info" onclick="window.location.href='{{ route('customers.create') }}'">
                + Add Customer
            </button>
            <button class="btn btn-info" onclick="window.location.href='{{ route('customers.customer_invoice') }}'">
                + Create Customer Invoice
            </button>
        </div>
        
    </div>


    <!-- Search & Filter -->
    <div class="d-flex justify-content-between mb-3">
        <input type="text" id="searchInput" 
               class="form-control w-50" 
               placeholder="Search Customer..." 
               onkeyup="filterTable()">
        
        <select id="statusFilter" 
                class="form-select w-25" 
                onchange="filterTable()">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="onhold">On Hold</option>
        </select>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle" id="customersTable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Customer Code</th>
                    <th>Name</th>
                    <th>Contact No</th>
                    <th>Email</th>
                    <th>Opening Balance</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $c)
                    <tr>
                        <td>{{$customers ->firstItem() + $loop->index }}</td>
                        <td>{{ $c->customer_code }}</td>
                        <td>{{ $c->name }}</td>
                        <td>{{ $c->contact_no }}</td>
                        <td>{{ $c->email ?? '-' }}</td>
                        <td>{{rtrim(rtrim(number_format($c->opening_balance, 2), '0'), '.') }} </td>
                             <td><select class="form-select form-select-sm status-select" data-id="{{ $c->id }}">
                                <option value="active"   {{ $c->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $c->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="onhold"   {{ $c->status == 'onhold' ? 'selected' : '' }}>On Hold</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <a href="/customers/{{ $c->id }}" class="btn btn-sm btn-info">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end mt-3">
            {{ $customers ->links('pagination::bootstrap-5') }}
        </div>
        <div class="d-flex justify-content-between mt-3">
            <button class="btn btn-secondary" onclick="window.location.href='/'">
                Back
            </button>
        </div>
    </div>
</div>

<script>
function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const statusFilter = document.getElementById("statusFilter").value.toLowerCase();
    const rows = document.querySelectorAll("#customersTable tbody tr");

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
        const customerId = this.getAttribute('data-id');
        const newStatus = this.value;

        fetch(`/customers/${customerId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(res => {
            if (!res.ok) throw new Error("HTTP error " + res.status);
            return res.json();
        })
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
</body>
</html>
