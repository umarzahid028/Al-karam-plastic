<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title> Admin Dashboard</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    :root {
      --brand-start: #0a4abf;
      --brand-end: #132b7a;
      --card-shadow: 0 8px 20px rgba(16, 24, 40, 0.08);
    }
    body { background: #f6f8fb; }

    /* Brand Topbar */
    .brand-navbar {
      background: linear-gradient(90deg, var(--brand-start), var(--brand-end));
    }
    .brand-navbar .navbar-brand {
      font-weight: 700;
      letter-spacing: .3px;
      color: #fff;
    }
    .brand-navbar .nav-link,
    .brand-navbar .navbar-text { color: rgba(255,255,255,.9); }
    .brand-navbar .nav-link:hover { color: #fff; }

    /* Page header */
    .page-header { padding: 18px 0; }
    .page-header h1 { font-size: 1.4rem; margin: 0; color: #111827; }

    /* Action grid cards */
    .action-card {
      border: 0;
      border-radius: 1rem;
      box-shadow: var(--card-shadow);
      transition: transform .12s ease, box-shadow .12s ease;
      background: #fff;
    }
    .action-card:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(16,24,40,.12); }
    .action-card .card-body { display: flex; align-items: center; gap: 14px; padding: 18px; }
    .action-card .bi {
      font-size: 28px;
      padding: 14px;
      border-radius: 12px;
      background: #eff4ff;
    }
    .action-title { font-weight: 600; margin: 0; color: #0f172a; }
    .action-sub { margin: 2px 0 0 0; color: #64748b; font-size: .9rem; }

    .container-narrow { max-width: 1150px; }

    @media (max-width: 575.98px) {
      .action-card .card-body { padding: 16px; }
    }
  </style>
</head>
<body>
  <!-- Top Navigation -->
  <nav class="navbar navbar-expand-lg brand-navbar">
    <div class="container container-narrow">
      <a class="navbar-brand d-flex align-items-center" href="{{route('welcome')}}">
        <i class="bi bi-capsule me-2"></i>
        Admin Dashboard
      </a>
      {{-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button> --}}

      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin-left: 200px;">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Products</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Add New Product</a></li>
              <li><a class="dropdown-item" href="#">Update Product</a></li>
              <li><a class="dropdown-item" href="#">All Products</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Sales</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Create Invoice</a></li>
              <li><a class="dropdown-item" href="#">Invoices</a></li>
              <li><a class="dropdown-item" href="#">Payments</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Stock</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Add Raw Material</a></li>
              <li><a class="dropdown-item" href="#">Purchase/Issue Raw Material</a></li>
              <li><a class="dropdown-item" href="#">View Stock</a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link" href="#">Reports</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Payments</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Make Payment</a></li>
              <li><a class="dropdown-item" href="#">Payment Ledger</a></li>
            </ul>
          </li>
        </ul>

        <div class="d-flex align-items-center  gap-3">
          {{-- <span class="navbar-text d-none d-md-inline">Guest</span> --}}
          <a class="btn btn-light btn-sm d-flex align-items-center" href="#">
            <i class="bi bi-person-circle me-1"></i>
            Login
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Page Heading -->
  <div class="container container-narrow">
    <div class="page-header d-flex justify-content-between align-items-center">
      <h1 class="mb-0">Admin Dashboard</h1>
    </div>

    <!-- Action Grid -->
    <div class="row g-3 g-md-4">
      <!-- Row 1 -->
      <div class="col-12 col-sm-6 col-lg-4">
        <a class="card action-card text-decoration-none" href="{{route('raw_materials.index')}}">
          <div class="card-body">
            <i class="bi bi-bricks"></i>
            <div>
              <p class="action-title">Add Raw Material</p>
              <p class="action-sub">Create items for stock</p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-12 col-sm-6 col-lg-4">
        <a class="card action-card text-decoration-none" href="{{route('purchases.index')}}">
          <div class="card-body">
            <i class="bi bi-box-seam"></i>
            <div>
              <p class="action-title">Purchase/Issue Raw Material</p>
              <p class="action-sub">Record incoming & usage</p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-12 col-sm-6 col-lg-4">
        <a class="card action-card text-decoration-none" href="{{route('invoice.create')}}">
          <div class="card-body">
            <i class="bi bi-receipt"></i>
            <div>
              <p class="action-title">Create Invoice</p>
              <p class="action-sub">Generate customer invoices</p>
            </div>
          </div>
        </a>
      </div>

      <!-- Row 2 -->
      <div class="col-12 col-sm-6 col-lg-4">
        <a class="card action-card text-decoration-none" href="#">
          <div class="card-body">
            <i class="bi bi-credit-card"></i>
            <div>
              <p class="action-title">Make Payment</p>
              <p class="action-sub">Log customer payments</p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-12 col-sm-6 col-lg-4">
        <a class="card action-card text-decoration-none" href="{{route('reports.summary')}}">
          <div class="card-body">
            <i class="bi bi-bar-chart-line"></i>
            <div>
              <p class="action-title">View Reports</p>
              <p class="action-sub">Sales & stock analytics</p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-12 col-sm-6 col-lg-4">
        <a class="card action-card text-decoration-none" href="{{route('users.index')}}">
          <div class="card-body">
            <i class="bi bi-person-plus"></i>
            <div>
              <p class="action-title">Add Users</p>
              <p class="action-sub">Create system users</p>
            </div>
          </div>
        </a>
      </div>

      <!-- Row 3 -->
      <div class="col-12 col-sm-6 col-lg-4">
        <a class="card action-card text-decoration-none" href="#">
          <div class="card-body">
            <i class="bi bi-people"></i>
            <div>
              <p class="action-title">Manage Users</p>
              <p class="action-sub">Roles & permissions</p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-12 col-sm-6 col-lg-4">
        <a class="card action-card text-decoration-none" href="{{route('suppliers.index')}}">
          <div class="card-body">
            <i class="bi bi-truck"></i>
            <div>
              <p class="action-title">Add New Supplier</p>
              <p class="action-sub">Create vendor profiles</p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-12 col-sm-6 col-lg-4">
        <a class="card action-card text-decoration-none" href="{{route('products.index')}}">
          <div class="card-body">
            <i class="bi bi-box"></i>
            <div>
              <p class="action-title">Add New Product</p>
              <p class="action-sub">Add catalog items</p>
            </div>
          </div>
        </a>
      </div>

      <!-- Row 4 -->
      <div class="col-12 col-sm-6 col-lg-4">
        <a class="card action-card text-decoration-none" href="{{route('products.update-index')}}">
          <div class="card-body">
            <i class="bi bi-arrow-repeat"></i>
            <div>
              <p class="action-title">Update Product</p>
              <p class="action-sub">Edit existing items</p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-12 col-sm-6 col-lg-4">
        <a class="card action-card text-decoration-none" href="{{route('customers.index')}}">
          <div class="card-body">
            <i class="bi bi-buildings"></i>
            <div>
              <p class="action-title">Add/Update Customer</p>
              <p class="action-sub">Manage customers</p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-12 col-sm-6 col-lg-4">
        <a class="card action-card text-decoration-none" href="{{route('ledger.index')}}">
          <div class="card-body">
            <i class="bi bi-journal-text"></i>
            <div>
              <p class="action-title">View Ledger</p>
              <p class="action-sub">Receivables & balances</p>
            </div>
          </div>
        </a>
      </div>
      <!-- Row 5 -->
<div class="row g-3 g-md-4 mt-2">
  <div class="col-12 col-sm-6 col-lg-4">
    <a class="card action-card text-decoration-none" href="{{route('expenses.index')}}">
      <div class="card-body">
        <i class="bi bi-cash-stack"></i>
        <div>
          <p class="action-title">Add Expense</p>
          <p class="action-sub">Record company expenses</p>
        </div>
      </div>
    </a>
  </div>

  <div class="col-12 col-sm-6 col-lg-4">
    <a class="card action-card text-decoration-none" href="{{route('gatepass.index')}}">
      <div class="card-body">
        <i class="bi bi-key"></i>
        <div>
          <p class="action-title">Generate Pass</p>
          <p class="action-sub">Create suppliers/customer passes</p>
        </div>
      </div>
    </a>
  </div>


<!-- Dashboard Cards -->


  <!-- Purchase Return Card -->
  <div class="col-12 col-sm-6 col-lg-4">
    <a class="card action-card text-decoration-none" href="#" data-bs-toggle="modal" data-bs-target="#purchaseReturnModal">
      <div class="card-body">
        <i class="bi bi-arrow-counterclockwise"></i>
        <div>
          <p class="action-title">Purchase Return</p>
          <p class="action-sub">Return purchased items</p>
        </div>
      </div>
    </a>
  </div>
  <div class="row g-3">
  <!-- Sales Return Card -->
  <div class="col-12 col-sm-6 col-lg-4">
    <a class="card action-card text-decoration-none" href="#" data-bs-toggle="modal" data-bs-target="#salesReturnModal">
      <div class="card-body">
        <i class="bi bi-arrow-repeat"></i>
        <div>
          <p class="action-title">Sales Return</p>
          <p class="action-sub">Return sold items</p>
        </div>
      </div>
    </a>
  </div>

</div>
</div>
<!-- Purchase Return Modal -->
<div class="modal fade" id="purchaseReturnModal" tabindex="-1" aria-labelledby="purchaseReturnLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('purchase_returns.search.get') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="purchaseReturnLabel">Search Purchase Invoice</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
          @endif
          <input type="text" name="invoice_no" class="form-control" placeholder="Enter Invoice No" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Your Sales Return Modal -->
<div class="modal fade" id="salesReturnModal" tabindex="-1" aria-labelledby="salesReturnLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('sales_returns.search') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="salesReturnLabel">Search Sales Invoice</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
          @endif
          <input type="text" name="invoice_no" class="form-control" placeholder="Enter Invoice Number" required>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </form>
    </div>
  </div>
</div>

    <div class="py-4"></div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Auto open modal if there is an error -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('error'))
    var salesModal = new bootstrap.Modal(document.getElementById('salesReturnModal'));
    salesModal.show();
    @endif
});
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
      @if(session('error'))
      var purchaseModal = new bootstrap.Modal(document.getElementById('purchaseReturnModal'));
      purchaseModal.show();
      @endif
  });
  </script>
</body>
</html>