<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }
        .container {
            max-width: 700px;
            margin: 50px auto;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .card h3 {
            margin-bottom: 25px;
            color: #333;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
        }
        .btn-primary {
            background: #0d6efd;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }
        .btn-primary:hover {
            background: #0b5ed7;
        }
        .btn-secondary {
            border-radius: 6px;
            padding: 10px 25px;
        }
        label {
            font-weight: 500;
            color: #555;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h3>Add New User</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                </div>
                <div class="col-md-6">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
                <div class="col-md-6">
                    <label>Contact No</label>
                    <input type="text" name="contact_no" class="form-control" placeholder="03XXXXXXXXX">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Salary</label>
                    <input type="number" name="salary" class="form-control" step="0.01" placeholder="Enter salary">
                </div>
                <div class="col-md-6">
                    <label>Role</label>
                    <select name="role" class="form-select" required>
                        <option value="">Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="supervisor">Supervisor</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select" required>
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save User</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='/users'">
                    Back
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
