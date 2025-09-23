<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sales Return</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* Customize modal look */
    .modal-header {
      /* background-color: #0d6efd; */
      color: black;
    }
    .modal-title i {
      margin-right: 8px;
    }
    .action-card {
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .action-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    .action-title {
      font-weight: 600;
      font-size: 1.1rem;
    }
    .action-sub {
      font-size: 0.9rem;
      color: #6c757d;
    }
  </style>
</head>
<body>
<nav>

        <a class="nav-link d-flex align-items-center"
           href="javascript:void(0);"
           data-bs-toggle="modal"
           data-bs-target="#salesReturnModal">
          <i class="bi bi-arrow-repeat me-2"></i>
          <span>Sales Return</span>
        </a>
</nav>
  {{-- <div class="row">
    <div class="col-12 col-sm-6 col-lg-4">
      <a class="card action-card text-decoration-none" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#salesReturnModal">
        <div class="card-body text-center">
          <i class="bi bi-arrow-repeat display-4"></i>
          <div class="mt-3">
            <p class="action-title mb-0">Sales Return</p>
            <p class="action-sub mb-0">Return sold items</p>
          </div>
        </div>
      </a>
    </div>
  </div> --}}
<div class="container py-5">
  <!-- Modal -->
  <div class="modal fade" id="salesReturnModal" tabindex="-1" aria-labelledby="salesReturnLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-lg">
        <form action="{{ route('sales_returns.search') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="salesReturnLabel">
              <i class="bi bi-search"></i> Search Sales Invoice
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            @if(session('sales_return_error'))
            <div class="alert alert-danger">{{ session('sales_return_error') }}</div>
          @endif
            <div class="mb-3">
              <label for="invoice_no" class="form-label">Invoice Number</label>
              <input type="text" id="invoice_no" name="invoice_no" class="form-control" placeholder="Enter Invoice Number" required>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="bi bi-x-circle"></i> Cancel
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-circle"></i> Search
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Auto-show modal if error -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
      @if(session('sales_return_error'))
        var salesModal = new bootstrap.Modal(document.getElementById('salesReturnModal'));
        salesModal.show();
      @endif
  });
  </script>
  
</body>
</html>
