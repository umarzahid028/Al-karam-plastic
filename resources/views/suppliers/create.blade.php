@extends('layouts.app')

@section('title', 'Create Supplier')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; padding: 20px; }
.container { max-width: 700px; margin: 50px auto; }
.card { background: #fff; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); padding: 30px; }
.card h3 { margin-bottom: 25px; color: #333; }
.form-control:focus, .form-select:focus, textarea:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25); }
label { font-weight: 500; color: #555; }
textarea { min-height: 44px; resize: vertical; }
.btn-primary { background: #0d6efd; border: none; padding: 10px 25px; border-radius: 6px; box-shadow: 0 3px 6px rgba(0,0,0,0.1); }
.btn-primary:hover { background: #0b5ed7; }
.btn-secondary { border-radius: 6px; }
.d-flex.gap-2 > .btn { flex: 1; }
</style>
@endpush

@section('content')
<div class="container">
  <div class="card">
    <h3>Create New Supplier</h3>

    <form id="supplierForm">
      <div class="row mb-3">
        <div class="col-md-6">
          <label>Supplier Code</label>
          <input type="text" class="form-control" id="supplier_code" placeholder="SUP-001" required>
        </div>
        <div class="col-md-6">
          <label>Company Name</label>
          <input type="text" class="form-control" id="company_name" placeholder="Company Name" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label>Contact Name</label>
          <input type="text" class="form-control" id="name" placeholder="Person Name" required>
        </div>
        <div class="col-md-6">
          <label>Contact No</label>
          <input type="tel" class="form-control" id="contact_no" placeholder="+92-300-1234567" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label>Email</label>
          <input type="email" class="form-control" id="email" placeholder="name@example.com">
        </div>
        <div class="col-md-6">
          <label>Opening Balance</label>
          <input type="number" class="form-control" id="opening_balance" value="0">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label>Status</label>
          <select class="form-select" id="status">
            <option value="active" selected>Active</option>
            <option value="inactive">Inactive</option>
            <option value="onhold">On Hold</option>
          </select>
        </div>
        <div class="col-md-6">
          <label>City</label>
          <input type="text" class="form-control" id="city" placeholder="Karachi" required>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Save Supplier</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById("supplierForm").addEventListener("submit", function(e){
  e.preventDefault();

  const data = {
    supplier_code: document.getElementById("supplier_code").value,
    company_name: document.getElementById("company_name").value,
    name: document.getElementById("name").value,
    city: document.getElementById("city").value,
    email: document.getElementById("email").value,
    contact_no: document.getElementById("contact_no").value,
    opening_balance: document.getElementById("opening_balance").value,
    status: document.getElementById("status").value
  };

  fetch("{{ route('suppliers.api.store') }}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(data)
  })
  .then(async res => {
    const text = await res.text(); 
    try { return JSON.parse(text); } 
    catch (e) { throw new Error("Not JSON: " + text); }
  })
  .then(resp => {
    if(resp.success){
      alert(resp.message);
      window.location.href = "{{ route('suppliers.index') }}";
    } else {
      alert("Error: " + resp.message);
    }
  })
  .catch(err => alert("Request failed: " + err));
});
</script>
@endpush
