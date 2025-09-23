<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Report Selector</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
body { 
    background:#f1f3f6;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.section-hidden { display: none; }

/* Modal Styling */
.modal-content {
    border-radius: 12px;
    box-shadow: 0 12px 28px rgba(0,0,0,0.15);
    padding: 1.5rem;
}
.modal-header h5 {
    font-weight: 600;
    color: #1e293b;
}
.modal-header .btn-close {
    border-radius: 50%;
    background: #f1f5f9;
}

/* Search Input */
.modal-search {
    position: relative;
}
.modal-search input {
    border-radius: 50px;
    padding-left: 3rem;
    padding-right: 1rem;
    border: 1px solid #ced4da;
    transition: all 0.2s ease;
}
.modal-search input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59,130,246,.25);
}
.modal-search .bi-search {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

/* Buttons */
.report-buttons a.btn {
    border-radius: 10px;
    padding: 0.6rem 1rem;
    font-weight: 500;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}

/* Primary reports - blue */
.report-buttons a.btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: none;
    color: #fff;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}
.report-buttons a.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
}

/* Stock / Sales / Accounting buttons - green */
.report-buttons a.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
    color: #fff;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
}
.report-buttons a.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(5, 150, 105, 0.35);
}

/* Main menu buttons - outline */
.report-buttons a.btn-outline-primary,
.report-buttons a.btn-outline-success {
    border-radius: 10px;
    font-weight: 500;
    padding: 0.7rem 1rem;
    transition: all 0.2s ease;
}
.report-buttons a.btn-outline-primary:hover {
    background: #3b82f6;
    color: #fff;
}
.report-buttons a.btn-outline-success:hover {
  background: #3b82f6;
    color: #fff;
}

/* Back button */
.btn-link {
    color: #1e293b;
    font-weight: 500;
}
.btn-link:hover {
    color: #2563eb;
    text-decoration: none;
}

/* Responsive */
@media (max-width: 576px){
    .report-buttons a.btn {
        font-size: 0.9rem;
        padding: 0.5rem 0.8rem;
    }
}
</style>
</head>
<body>

<nav class="">
    <a href="#" class="nav-link "
       data-bs-toggle="modal" data-bs-target="#reportModal">
      <i class="bi bi-bar-chart-line me-2 fs-5"></i>
      <span class="fw-semibold">View Reports</span>
    </a>
</nav>

<!-- Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white"><i class="bi bi-bar-chart"></i> Choose a Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <!-- MAIN MENU -->
        <div id="mainMenu" class="report-buttons text-center mb-3">
          <div class="row g-3 justify-content-center">
            <div class="col-10 col-md-6">
              <a href="#" class="btn btn-outline-primary w-100" data-section="salesSection">
                <i class="bi bi-cart me-2"></i> Sales/Purchase
              </a>
            </div>
            <div class="col-10 col-md-6">
              <a href="#" class="btn btn-outline-primary w-100" data-section="stockSection">
                <i class="bi bi-box-seam me-2"></i> Stock
              </a>
            </div>
            <div class="col-10 col-md-6">
              <a href="#" class="btn btn-outline-primary w-100" data-section="salemansection">
                <i class="bi bi-person me-2"></i> Saleman
              </a>
            </div>
            <div class="col-10 col-md-6">
              <a href="#" class="btn btn-outline-primary w-100" data-section="accsreportsection">
                <i class="bi bi-journal-text me-2"></i> Accounting
              </a>
            </div>
          </div>
        </div>

        <!-- SALES / PURCHASE SECTION -->
        <div id="salesSection" class="section-hidden">
            <button class="btn btn-link mb-3" data-section="mainMenu">
              <i class="bi bi-arrow-left"></i> Back
            </button>
            <div class="position-relative modal-search mb-3">
              <i class="bi bi-search"></i>
              <input type="text" class="form-control section-search" placeholder="Search sales reports…">
            </div>
            <div class="report-buttons">
              <div class="row g-3 justify-content-center">
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.total_sales') }}" class="btn btn-primary d-flex justify-content-between w-100">
                    Sales Report <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.returns_sales_report') }}" class="btn btn-primary d-flex justify-content-between w-100">
                    Sales Return <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.total_purchases') }}" class="btn btn-primary d-flex justify-content-between w-100">
                    Purchase Report <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.purchase_returns') }}" class="btn btn-primary d-flex justify-content-between w-100">
                    Purchase Return <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.sales_summary') }}" class="btn btn-primary d-flex justify-content-between w-100">
                    Sales Summary <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.raw_supplier_purchase_summary') }}" class="btn btn-primary d-flex justify-content-between w-100">
                    Supplier Purchase Summary <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.raw_material_item_report') }}" class="btn btn-primary d-flex justify-content-between w-100">
                    Item Report <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{ route('report.orders_summary') }}" class="btn btn-primary d-flex justify-content-between w-100">
                    Order Summary <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
        </div>

        <!-- STOCK SECTION -->
        <div id="stockSection" class="section-hidden">
            <button class="btn btn-link mb-3" data-section="mainMenu"><i class="bi bi-arrow-left"></i> Back</button>
            <div class="position-relative modal-search mb-3">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control section-search" placeholder="Search stock reports…">
            </div>
            <div class="report-buttons">
              <div class="row g-3 justify-content-center">
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.stock') }}" class="btn btn-success d-flex justify-content-between w-100">
                    Stock Report <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{route('reports.sale_stock_report')}}" class="btn btn-success d-flex justify-content-between w-100">
                    Sale Stock Report <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{route('reports.stock-summary')}}" class="btn btn-success d-flex justify-content-between w-100">
                    Stock Summary <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
        </div>

        <!-- SALESMAN SECTION -->
        <div id="salemansection" class="section-hidden">
            <button class="btn btn-link mb-3" data-section="mainMenu"><i class="bi bi-arrow-left"></i> Back</button>
            <div class="position-relative modal-search mb-3">
              <i class="bi bi-search"></i>
              <input type="text" class="form-control section-search" placeholder="Search salesman reports…">
            </div>
            <div class="report-buttons">
              <div class="row g-3 justify-content-center">
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.sale_sheet') }}" class="btn btn-success d-flex justify-content-between w-100">
                    Sale Sheet <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
        </div>

        <!-- ACCOUNTING SECTION -->
        <div id="accsreportsection" class="section-hidden">
            <button class="btn btn-link mb-3" data-section="mainMenu"><i class="bi bi-arrow-left"></i> Back</button>
            <div class="position-relative modal-search mb-3">
              <i class="bi bi-search"></i>
              <input type="text" class="form-control section-search" placeholder="Search accounting reports…">
            </div>
            <div class="report-buttons">
              <div class="row g-3 justify-content-center">
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.ledger') }}" class="btn btn-success d-flex justify-content-between w-100">
                    Ledger <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.payments') }}" class="btn btn-success d-flex justify-content-between w-100">
                    Payments <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{route('reports.daily-sheet')}}" class="btn btn-success d-flex justify-content-between w-100">
                    Daily Sheet <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
        </div>

      </div><!-- /modal-body -->
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const sections = ['mainMenu','salesSection','stockSection','salemansection','accsreportsection'];

  function showSection(id){
    sections.forEach(sec => {
      document.getElementById(sec).classList.toggle('section-hidden', sec !== id);
    });
  }

  // Switch sections when menu buttons are clicked
  document.querySelectorAll('[data-section]').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      showSection(btn.dataset.section);
    });
  });

  // Search filter inside each section
  document.querySelectorAll('.section-search').forEach(input => {
    input.addEventListener('input', function(){
      const links = this.closest('div').nextElementSibling.querySelectorAll('a.btn');
      const term  = this.value.toLowerCase();
      links.forEach(link=>{
        link.style.display = link.textContent.toLowerCase().includes(term) ? '' : 'none';
      });
    });
  });

  // Always start with Main Menu whenever modal opens
  const reportModal = document.getElementById('reportModal');
  reportModal.addEventListener('show.bs.modal', () => {
    showSection('mainMenu');
  });
});
</script>

</body>
</html>
