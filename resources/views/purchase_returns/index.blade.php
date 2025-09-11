<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Purchase Return</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<style>
  body {
    font-family: Arial, sans-serif;
    background: #f5f7fa;
  }



  /* Card button */
  .action-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    cursor: pointer;
  }
  .action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  }

  .action-title {
    font-weight: 600;
    font-size: 1.2rem;
  }

  .action-sub {
    font-size: 0.9rem;
    color: #6c757d;
  }

  /* Modal customizations */
  .modal-header {
   
    color: #fff;
  }
  .btn-close-white {
    filter: invert(1);
  }
  .modal-content {
    border-radius: 12px;
    overflow: hidden;
  }

  /* Lighter modal backdrop */
  .modal-backdrop.show {
    opacity: 0.4 !important; /* default is 0.5, you can adjust */
    background-color: #000;
  }
</style>
</head>
<body>

    <div class="col-12 col-sm-6 col-lg-4"> <a class="card action-card text-decoration-none" href="#" data-bs-toggle="modal" data-bs-target="#purchaseReturnModal"> <div class="card-body"> <i class="bi bi-arrow-counterclockwise"></i> <div> <p class="action-title">Purchase Return</p> <p class="action-sub">Return purchased items</p> </div> </div> </a> </div>

  <!-- Purchase Return Card -->

    
  
  <!-- Purchase Return Modal -->
  <div class="modal fade" id="purchaseReturnModal" tabindex="-1" aria-labelledby="purchaseReturnLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-lg">
        <form action="{{ route('purchase_returns.search.get') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="purchaseReturnLabel">
              <i class="bi bi-search"></i> Search Purchase Invoice
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
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

<!-- Auto open modal if there is an error -->
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
