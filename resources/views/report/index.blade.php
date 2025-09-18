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
    background:#f8f9fa;
}
.section-hidden { display: none; }

.modal-search input {
    border-radius:20px;
    padding-left:2.5rem;
}
.modal-search .bi-search {
    position:absolute;
    left:1rem;
    top:50%;
    transform:translateY(-50%);
    color:#6c757d;
}

.report-buttons a {
   
    display:block;
}
</style>
</head>
<body>

<div class="col-12 col-sm-6 col-lg-4">
    <a class="card action-card text-decoration-none"
       href="#"
       data-bs-toggle="modal"
       data-bs-target="#reportModal">
        <div class="card-body">
          <i class="bi bi-bar-chart-line"></i>
          <div>
            <p class="action-title">View Reports</p>
            <p class="action-sub">Sales & stock analytics</p>
          </div>
        </div>
    </a>
</div>

<!-- Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-bar-chart"></i> Choose a Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- MAIN MENU -->
        <div id="mainMenu" class="report-buttons text-center">
          <div class="row g-2">
            <div class="col-8 col-md-6">
              <a href="#" class="btn btn-outline-primary w-100" data-section="salesSection">
                <i class="bi bi-cart"></i> Sales/Pur Report
              </a>
            </div>
            <div class="col-8 col-md-6">
              <a href="#" class="btn btn-outline-success w-100" data-section="stockSection">
                <i class="bi bi-box-seam"></i> Stock Report
              </a>
            </div>
            <div class="col-8 col-md-6">
              <a href="#" class="btn btn-outline-success w-100" data-section="salemansection">
                <i class="bi bi-person"></i> Saleman Report
              </a>
            </div>
            <div class="col-8 col-md-6">
              <a href="#" class="btn btn-outline-success w-100" data-section="accsreportsection">
                <i class="bi bi-journal-text"></i> ACCS Report
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
              <input type="text" class="form-control section-search" placeholder="Search reports…">
            </div>
          
            <div class="report-buttons">
              <div class="row g-2"> 
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.total_sales') }}" class="btn btn-primary w-50 text-start">
                    <span>Sales Report</span> <i class="bi bi-arrow-right float-end"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.returns_sales_report') }}" class="btn btn-primary w-50 text-start">
                    <span>Sales Return Report</span> <i class="bi bi-arrow-right float-end"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.total_purchases') }}" class="btn btn-primary w-50 text-start">
                    <span>Purchase Report</span> <i class="bi bi-arrow-right float-end"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.purchase_returns') }}" class="btn btn-primary w-50 text-start">
                    <span>Purchase Return</span> <i class="bi bi-arrow-right float-end"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.sales_summary') }}" class="btn btn-primary w-50 text-start">
                    <span>Sales Summary</span> <i class="bi bi-arrow-right float-end"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.raw_supplier_purchase_summary') }}" class="btn btn-primary w-50 text-start">
                    <span>Sales Purchase</span> <i class="bi bi-arrow-right float-end"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.raw_material_item_report') }}" class="btn btn-primary w-50 text-start">
                    <span>Item Report</span> <i class="bi bi-arrow-right float-end"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{ route('report.orders_summary') }}" class="btn btn-primary w-50 text-start">
                    <span>Order Summary</span> <i class="bi bi-arrow-right float-end"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="#" class="btn btn-primary w-50 text-start">
                    <span>City Wise Sale</span> <i class="bi bi-arrow-right float-end"></i>
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
            <div   class="report-buttons row g-2 justify-content-center">
              <div class="col-12 col-md-6">
                <a href="{{ route('reports.stock') }}" class="btn btn-success d-flex justify-content-between">
                  <span>Stock Report</span><i class="bi bi-arrow-right"></i>
                </a>
              </div>
              <div class="col-12 col-md-6">
                <a href="{{route('reports.sale_stock_report')}}" class="btn btn-success d-flex justify-content-between">
                  <span>Sale Stock Report</span><i class="bi bi-arrow-right"></i>
                </a>
              </div>
              <div class="col-12 col-md-6">
                <a href="#" class="btn btn-success d-flex justify-content-between">
                  <span>Physical Stock Report</span><i class="bi bi-arrow-right"></i>
                </a>
              </div>
              <div class="col-12 col-md-6">
                <a href="#" class="btn btn-success d-flex justify-content-between">
                  <span>Stock Summary</span><i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

        {{-- sale man section --}}
        <div id="salemansection" class="section-hidden">
            <button class="btn btn-link mb-3" data-section="mainMenu"><i class="bi bi-arrow-left"></i> Back</button>
  
            <div class="position-relative modal-search mb-3">
              <i class="bi bi-search"></i>
              <input type="text" class="form-control section-search" placeholder="Search sale man…">
            </div>
  
            <div class="report-buttons">
              <div   class="report-buttons row g-2 justify-content-center">
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.sale_sheet') }}" class="btn btn-success d-flex justify-content-between">
                    <span>Sale Sheet</span><i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                {{-- <div class="col-12 col-md-6">
                  <a href="#" class="btn btn-success d-flex justify-content-between">
                    <span>Sale Stock Report</span><i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="#" class="btn btn-success d-flex justify-content-between">
                    <span>Physical Stock Report</span><i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="#" class="btn btn-success d-flex justify-content-between">
                    <span>Stock Summary</span><i class="bi bi-arrow-right"></i>
                  </a>
                </div> --}}
              </div>
            </div>
          </div>
          <div id="accsreportsection" class="section-hidden">
            <button class="btn btn-link mb-3" data-section="mainMenu"><i class="bi bi-arrow-left"></i> Back</button>
  
            <div class="position-relative modal-search mb-3">
              <i class="bi bi-search"></i>
              <input type="text" class="form-control section-search" placeholder="Search stock reports…">
            </div>
  
            <div class="report-buttons">
              <div   class="report-buttons row g-2 justify-content-center">
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.ledger') }}" class="btn btn-success d-flex justify-content-between">
                    <span>Ledger</span><i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="{{ route('reports.payments') }}" class="btn btn-success d-flex justify-content-between">
                    <span>Payments</span><i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="#" class="btn btn-success d-flex justify-content-between">
                    <span>Daily Sheet</span><i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                {{-- <div class="col-12 col-md-6">
                  <a href="#" class="btn btn-success d-flex justify-content-between">
                    <span>Stock Summary</span><i class="bi bi-arrow-right"></i>
                  </a>
                </div> --}}
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
