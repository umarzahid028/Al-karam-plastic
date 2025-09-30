@extends('layouts.app')

@section('title', 'Total Sales Report')

@push('styles')
<style>
body { background: #f8f9fa; font-family: Arial, sans-serif; }
.container { margin-top: 40px; }
h3 { margin-bottom: 25px; }

.card { border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
.card input { border-radius: 8px; }

.table thead { background: #0d6efd; color: #fff; }
.table-hover tbody tr:hover { background: #e9ecef; }
.table td, .table th { vertical-align: middle; }

.summary-box {
    background: #fff;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}
.summary-box strong { display: block; font-size: 1.1rem; }
</style>
@endpush
@section('content')

<div class="container">
    <h3><i class="bi bi-cash-stack me-2"></i>Payments Report</h3>

    <!-- Filters Card -->
    <div class="card mb-3 p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" name="from_date" class="form-control" placeholder="From Date">
            </div>
            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" name="to_date" class="form-control" placeholder="To Date">
            </div>
            <div class="col-md-3">
                <label class="form-label">Week</label>
                <input type="week" name="week" class="form-control" placeholder="Select Week">
            </div>
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Search Party / Description">
            </div>
            <div class="col-12 col-md-6 mt-2 d-flex gap-2">
                <button class="btn btn-primary flex-fill"><i class="bi bi-funnel-fill me-1"></i> Filter</button>
                <button type="reset" class="btn btn-outline-danger flex-fill"><i class="bi bi-arrow-clockwise me-1"></i> Reset</button>
            </div>
        </form>
    </div>

    <!-- Grand Total Summary -->
    <div class="row summary-box text-center">
        <div class="col-md-4"><strong>Total Paid:</strong> <span id="totalPaid">0.00</span></div>
        <div class="col-md-4"><strong>Transactions:</strong> <span id="totalTxns">0</span></div>
        <div class="col-md-4"><strong>Average Payment:</strong> <span id="avgPayment">0.00</span></div>
    </div>

    <!-- Payments Table -->
    <div class="table-responsive">
        <table id="paymentsTable" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Party</th>
                    <th>Description</th>
                    <th>Paid Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $p)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($p->invoice_date)->format('d M, Y') }}</td>
                        <td>{{ $p->party_name }}</td> <!-- join party name if needed -->
                        <td>{{ $p->description }}</td>
                        <td>{{ number_format($p->credit, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
                
        </table>
    </div>
</div>
@endsection

@push('scripts')
<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(function(){
    // Initialize DataTable
    var table = $('#paymentsTable').DataTable({
        paging: true,
        ordering: true,
        order: [[0,'desc']],
        pageLength: 25,
        responsive: true,
        dom: 'Bfrtip',
        buttons: ['copy','csv','excel','pdf','print'],
        drawCallback: function(){
            updateSummary();
        }
    });

    // Live search
    $('#searchInput').on('keyup', function(){
        table.search(this.value).draw();
    });

    // Update summary totals
    function updateSummary(){
        let total = 0;
        table.column(3, {search:'applied'} ).data().each(function(value){
            total += parseFloat(value.replace(/,/g,''));
        });
        $('#totalPaid').text(total.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}));
        $('#totalTxns').text(table.rows({search:'applied'}).count());
        $('#avgPayment').text((total / table.rows({search:'applied'}).count() || 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2}));
    }

    // Initialize summary on load
    updateSummary();
});
</script>
@endpush