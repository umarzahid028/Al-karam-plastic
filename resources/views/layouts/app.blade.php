<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','Dashboard')</title>

  {{-- Bootstrap & Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  @stack('styles')

  <style>
    /* GLOBAL */
    body {
      background:#f8fafc;
      font-family: 'Segoe UI', Arial, sans-serif;
    }

    /* NAVBAR */
    .brand-navbar {
      background: #1e293b; /* solid modern color */
      color:#fff;
      box-shadow:0 2px 6px rgba(0,0,0,.2);
    }
    .brand-navbar .navbar-brand {
      font-weight:700;
      letter-spacing:.5px;
      color:#f8fafc!important;
    }
    .brand-navbar .btn-outline-light {
      color:#f1f5f9!important;
      border-color:rgba(255,255,255,.3);
    }
    .brand-navbar .btn-outline-light:hover {
      background:rgba(255,255,255,.15);
      border-color:transparent;
    }
    .navbar-toggler { border:none; }
    .navbar-toggler-icon { filter: invert(1) brightness(1.3); }

    /* SIDEBAR */
    .sidebar {
      background:#1e293b;
      min-height:100vh;
      padding-top: 1rem;
      transition: all 0.3s ease;
    }
    .sidebar.collapsed {
      margin-left: -240px; /* Hide sidebar */
    }
    .sidebar .nav-link {
      color:#cbd5e1;
      padding:.6rem 1rem;
      border-radius:.4rem;
      margin:.1rem 0;
      transition:.2s;
      display: flex;
      align-items: center;
    }
    .sidebar .nav-link i {
      margin-right: 8px;
      font-size: 1.1rem;
    }
    .sidebar .nav-link:hover {
      background:#334155;
      color:#fff;
    }
    .sidebar .nav-link.active {
      background: #1e293b;
      color: #ffffff;
      font-weight: 600;
      box-shadow: inset 3px 0 0 #1e293b;
    }
    .sidebar-heading {
      font-size:.75rem;
      text-transform:uppercase;
      letter-spacing:.5px;
      padding:.75rem 1rem .25rem;
      color:#94a3b8;
    }

    /* MAIN CONTENT */
    main {
      background: #fff;
      border-radius: .75rem;
      box-shadow: 0 2px 8px rgba(0,0,0,.05);
      margin-top: 1rem;
      transition: margin-left 0.3s ease;
    }
    .main-expanded {
      margin-left: -240px; 
    }
  </style>
</head>
<body>
  {{-- Top Navbar --}}
  <nav class="navbar navbar-expand-lg navbar-dark shadow-sm brand-navbar">
    <div class="container-fluid px-3">
      <button class="btn btn-outline-light me-2" id="sidebarToggle">
        <i class="bi bi-list"></i>
      </button>

      <a class="navbar-brand d-flex align-items-center" href="{{ route('welcome') }}">
        <i class="bi bi-capsule me-2 fs-4"></i> Al-Karam Plastic
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="topNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="btn btn-outline-light btn-sm d-flex align-items-center" href="#">
              <i class="bi bi-person-circle me-1"></i> Login
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  {{-- Main Container --}}
  <div class="container-fluid">
    <div class="row">
      {{-- Sidebar --}}
      <nav class="sidebar col-md-3 col-lg-2 d-md-block" id="sidebarMenu">
        <ul class="nav nav-pills flex-column mb-auto">
          <li class="sidebar-heading">Raw Materials</li>
          <li><a class="nav-link {{ request()->routeIs('raw_materials.*')?'active':'' }}" href="{{ route('raw_materials.index') }}"><i class="bi bi-bricks"></i> Add Raw Material</a></li>
          <li><a class="nav-link {{ request()->routeIs('purchases.*')?'active':'' }}" href="{{ route('purchases.index') }}"><i class="bi bi-box-seam"></i> Purchase / Issue Raw</a></li>

          <li class="sidebar-heading">Sales</li>
          <li><a class="nav-link {{ request()->routeIs('invoice.create')?'active':'' }}" href="{{ route('invoice.create') }}"><i class="bi bi-receipt"></i> Create Invoice</a></li>
          <li><a class="nav-link" href="#"><i class="bi bi-credit-card"></i> Make Payment</a></li>
          @include('report.index')

          <li class="sidebar-heading">Management</li>
          <li><a class="nav-link {{ request()->routeIs('users.*')?'active':'' }}" href="{{ route('users.index') }}"><i class="bi bi-person-plus"></i> Add Users</a></li>
          <li><a class="nav-link {{ request()->routeIs('suppliers.*')?'active':'' }}" href="{{ route('suppliers.index') }}"><i class="bi bi-truck"></i> Add Supplier</a></li>
          <li><a class="nav-link {{ request()->routeIs('products.index')?'active':'' }}" href="{{ route('products.index') }}"><i class="bi bi-box"></i> Add Product</a></li>
          <li><a class="nav-link {{ request()->routeIs('products.update-index')?'active':'' }}" href="{{ route('products.update-index') }}"><i class="bi bi-arrow-repeat"></i> Update Product</a></li>
          <li><a class="nav-link {{ request()->routeIs('customers.*')?'active':'' }}" href="{{ route('customers.index') }}"><i class="bi bi-buildings"></i> Add/Update Customer</a></li>
          <li><a class="nav-link {{ request()->routeIs('ledger.*')?'active':'' }}" href="{{ route('ledger.index') }}"><i class="bi bi-journal-text"></i> View Ledger</a></li>
          <li><a class="nav-link {{ request()->routeIs('expenses.*')?'active':'' }}" href="{{ route('expenses.index') }}"><i class="bi bi-cash-stack"></i> Add Expense</a></li>
          <li><a class="nav-link {{ request()->routeIs('gatepass.*')?'active':'' }}" href="{{ route('gatepass.index') }}"><i class="bi bi-key"></i> Generate Pass</a></li>

          @include('purchase_returns.index')
          @include('sales_returns.index')
        </ul>
      </nav>

      {{-- Main Content --}}
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4" id="mainContent">
        @yield('content')
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    const sidebar = document.getElementById('sidebarMenu');
    const mainContent = document.getElementById('mainContent');
    const toggleBtn = document.getElementById('sidebarToggle');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('main-expanded');
    });
  </script>

  @stack('scripts')
</body>
</html>
