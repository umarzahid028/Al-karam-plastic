@extends('layouts.app')

@section('title', 'Add User')

@push('styles')
    <style>
        
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

       /* Primary dashboard-style button */
.btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: none;
    color: #fff;
    font-weight: 600;
    padding: 12px 28px;       /* bigger button */
    border-radius: 8px;
    font-size: 15px;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.btn-primary:hover,
.btn-primary:focus {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(29, 78, 216, 0.35);
}

.btn-primary:active {
    transform: translateY(0);
    box-shadow: 0 3px 8px rgba(29, 78, 216, 0.3);
}

/* Secondary button (Back) */
.btn-secondary {
    background: #64748b;  /* slate gray */
    border: none;
    color: #fff;
    font-weight: 600;
    padding: 12px 28px;
    border-radius: 8px;
    font-size: 15px;
    transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.2s ease;
}

.btn-secondary:hover {
    background: #475569;
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(71, 85, 105, 0.3);
}

        label {
            font-weight: 500;
            color: #555;
        }
    </style>
    @endpush
    @section('content')
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
@endsection