<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Dashboard')</title>

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    @stack('styles')

    <style>
      /* GLOBAL */
      body { background:#f8fafc; }

      /* NAVBAR */
      .brand-navbar {
        background: linear-gradient(90deg,#0f172a 0%,#1e293b 50%,#3b82f6 100%);
        color:#fff; box-shadow:0 2px 6px rgba(0,0,0,.2);
      }
      .brand-navbar .navbar-brand { font-weight:700; letter-spacing:.5px; color:#f8fafc!important; }
      .brand-navbar .btn-outline-light { color:#f1f5f9!important; border-color:rgba(255,255,255,.3); }
      .brand-navbar .btn-outline-light:hover { background:rgba(255,255,255,.15); border-color:transparent; }
      .navbar-toggler { border:none; }
      .navbar-toggler-icon { filter: invert(1) brightness(1.3); }

      /* SIDEBAR */
      .sidebar { background:#1e293b; min-height:100vh; }
      .sidebar .nav-link {
        color:#cbd5e1; padding:.6rem 1rem; border-radius:.4rem; margin:.1rem 0; transition:.2s;
      }
      .sidebar .nav-link:hover,
      .sidebar .nav-link.active { background:#334155; color:#fff; }
      .sidebar-heading {
        font-size:.8rem; text-transform:uppercase; letter-spacing:.5px;
        padding:.75rem 1rem .25rem; color:#94a3b8;
      }
 
      /* === Sidebar active link color === */
      .sidebar .nav-link.active {
          background: linear-gradient(135deg, #2563eb, #1d4ed8);       /* your chosen color */
          color: #ffffff;
          font-weight: 600;
          box-shadow: inset 3px 0 0 #0284c7;
      }
 
    </style>
</head>
<body>
    {{-- Top Navbar --}}
    @include('layouts.navbar')

    <div class="container-fluid">
      <div class="row">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main Content --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            @yield('content')
        </main>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
