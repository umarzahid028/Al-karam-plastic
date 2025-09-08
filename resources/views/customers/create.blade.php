<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Create Customer</title>
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
</head>
<body>
<div class="container">
  <div class="card">
    <h3>Create New Customer</h3>
    <form method="POST" action="{{ route('customers.store') }}">
      @csrf
      <div class="row mb-3">
        <div class="col-md-6">
          <label>Customer Code</label>
          <input type="text" name="customer_code" class="form-control" placeholder="CUS-001" required>
        </div>
        <div class="col-md-6">
          <label>Full Name</label>
          <input type="text" name="name" class="form-control" placeholder="Customer Name" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label>Contact No</label>
          <input type="tel" name="contact_no" class="form-control" placeholder="+92-300-1234567" required>
        </div>
        <div class="col-md-6">
          <label>Email</label>
          <input type="email" name="email" class="form-control" placeholder="name@example.com">
        </div>
      </div>

      <div class="mb-3">
        <label>Address</label>
        <textarea name="address" class="form-control" placeholder="Enter customer address"></textarea>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label>Opening Balance</label>
          <input type="number" name="opening_balance" class="form-control" value="0">
        </div>
        <div class="col-md-6">
          <label>Status</label>
          <select name="status" class="form-select">
            <option value="active" selected>Active</option>
            <option value="inactive">Inactive</option>
            <option value="onhold">On Hold</option>
          </select>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Save Customer</button>
        <button type="button" class="btn btn-secondary" onclick="window.location.href='/customers'">Back</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
