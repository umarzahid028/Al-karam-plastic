<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title','Admin')</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --brand-start: #0a4abf;
      --brand-end:   #132b7a;
      --sidebar-bg:  #1e293b;
      --sidebar-hover: #334155;
      --sidebar-active: #475569;
    }
    body {
      background: #f6f8fb;
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* Top Navbar */
    .brand-navbar {
      background: linear-gradient(90deg, var(--brand-start), var(--brand-end));
      box-shadow: 0 2px 6px rgba(0,0,0,.1);
    }
    .brand-navbar .navbar-brand,
    .brand-navbar .nav-link,
    .brand-navbar .btn {
      color: #fff;
    }
    .brand-navbar .btn-light {
      background: rgba(255,255,255,.15);
      border: none;
      color: #fff;
    }
    .brand-navbar .btn-light:hover {
      background: rgba(255,255,255,.25);
    }

    /* Sidebar */
    .sidebar {
      min-height: 100vh;
      background: var(--sidebar-bg);
      padding-top: 1rem;
      box-shadow: inset -1px 0 0 rgba(255,255,255,.05);
    }
    .sidebar .nav-link {
      color: #cbd5e1;
      padding: .65rem 1rem;
      margin: .15rem .5rem;
      border-radius: .5rem;
      display: flex;
      align-items: center;
      font-weight: 500;
      transition: background .15s ease, color .15s ease;
    }
    .sidebar .nav-link i {
      width: 1.5rem;
      text-align: center;
      font-size: 1.1rem;
    }
    .sidebar .nav-link:hover {
      background: var(--sidebar-hover);
      color: #fff;
    }
    .sidebar .nav-link.active {
      background: var(--sidebar-active);
      color: #fff;
    }

    .content-area { padding: 1.5rem; }
  </style>

  @stack('styles')
</head>
<body>
  {{-- Top Navbar --}}
  @include('layouts.navbar')

  <div class="container-fluid">
    <div class="row">
      {{-- Left Sidebar --}}
      @include('layouts.sidebar')

      {{-- Main Content --}}
      <main class="col-md-9 ms-sm-auto col-lg-10 content-area">
        @yield('content')
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
