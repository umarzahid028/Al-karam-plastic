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
.report-buttons {
  text-align: center;          /* center the anchor’s inline-block */
}
.report-buttons a {
  width: auto;                 /* or a fixed px/em width */
  min-width: 200px;            /* optional */
  max-width: 300px;            /* optional */
  margin-bottom: 0.5rem;
  display: inline-block;
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
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reportModalLabel"><i class="bi bi-bar-chart"></i> Choose a Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">

        <!-- Search bar -->
        <div class="position-relative modal-search mb-3">
            <i class="bi bi-search"></i>
            <input type="text" id="reportSearch" class="form-control" placeholder="Search reports…">
        </div>

        <!-- Report buttons -->
       <!-- remove the old width:100% CSS -->
<div id="reportList" class="report-buttons row g-2 justify-content-center">
    <div class="col-12 col-sm-6">
        <a href="{{ route('reports.total_sales') }}" class="btn btn-primary w-100 d-flex justify-content-between">
            <span>Sales Report</span> <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="col-12 col-sm-6">
        <a href="{{ route('reports.returns_sales_report') }}" class="btn btn-primary w-100 d-flex justify-content-between">
            <span>Sales Return Report</span> <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="col-12 col-sm-6">
        <a href="{{ route('reports.total_purchases') }}" class="btn btn-primary w-100 d-flex justify-content-between">
            <span>Purchase Report</span> <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="col-12 col-sm-6">
        <a href="{{ route('reports.purchase_returns') }}" class="btn btn-primary w-100 d-flex justify-content-between">
            <span>Purchase Return Report</span> <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="col-12 col-sm-6">
        <a href="{{ route('reports.sales_summary') }}" class="btn btn-primary w-100 d-flex justify-content-between">
            <span>Sales Summary</span> <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="col-12 col-sm-6">
        <a href="{{ route('reports.raw_supplier_purchase_summary') }}" class="btn btn-primary w-100 d-flex justify-content-between">
            <span>Sales Purchase</span> <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</div>


      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input   = document.getElementById('reportSearch');
        const list    = document.getElementById('reportList');
        const links   = Array.from(list.querySelectorAll('a'));
    
        // Create a “not found” element (hidden by default)
        const notFound = document.createElement('div');
        notFound.textContent = 'No report found';
        notFound.className   = 'text-muted text-center mt-2';
        notFound.style.display = 'none';
        list.after(notFound);
    
        input.addEventListener('input', function () {
            const filter = this.value.toLowerCase();
            let matchCount = 0;
    
            // Separate matching and non-matching links
            const matches    = [];
            const nonMatches = [];
    
            links.forEach(link => {
                const label = link.querySelector('span').textContent.toLowerCase();
                if (label.includes(filter)) {
                    matchCount++;
                    matches.push(link);
                    link.style.display = '';    // show
                } else {
                    nonMatches.push(link);
                    link.style.display = 'none';// hide
                }
            });
    
            // Show/hide the “not found” message
            notFound.style.display = matchCount === 0 ? '' : 'none';
    
            // Re-append matching links first so they appear on top
            matches.forEach(link => list.appendChild(link));
            nonMatches.forEach(link => list.appendChild(link));
        });
    });
    </script>
    
    
</body>
</html>
