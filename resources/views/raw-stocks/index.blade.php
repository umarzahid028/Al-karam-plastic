@extends('layouts.app')

@section('title', 'Stocks List')

@push('styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush
@section('content')
<div class="container">
    <h3>Raw Product Stock</h3>

    <div class="mb-3 row">
        <div class="col-md-4">
            <input type="text" id="search" class="form-control" placeholder="Search product...">
        </div>
        <div class="col-md-3">
            <select id="searchType" class="form-select">
                <option value="id">Product ID</option>
                <option value="name">Name</option>
                <option value="group">Group</option>
            </select>
        </div>
    </div>

    <table class="table table-bordered" id="stockTable">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Product Code</th>
                <th>Name</th>
                <th>Group</th>
                <th>Current Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
            <tr>
                <td>{{ $index+1 }}</td>
                <td>{{ $product->product_code }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->product_group }}</td>
                <td>
                    @php
                    $logs = collect($product->logs ?? []);
                    $totalIn = $logs->where('trans_type','IN')->sum('qty');
                    $totalOut = $logs->where('trans_type','OUT')->sum('qty');
                    $currentStock = $totalIn - $totalOut;
                    @endphp
                    
                    {{ $currentStock }}
                </td>
                <td>
                    <a href="{{ url('/raw-stocks/'.$product->id.'/history') }}" 
                       class="btn btn-sm btn-primary">
                       History
                    </a>
                </td>
                
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal for History -->
    <div class="modal fade" id="historyModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Stock History</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" id="historyContent"></div>
        </div>
      </div>
    </div>

</div>
@endsection
@push('scripts')

<script>
document.getElementById('search').addEventListener('keyup', function() {
    let q = this.value;
    let type = document.getElementById('searchType').value;

    fetch(`/raw-stocks/search?q=${q}&type=${type}`)
        .then(res => res.json())
        .then(data => {
            let tbody = '';
            data.forEach((p,i) => {
                tbody += `<tr>
                    <td>${i+1}</td>
                    <td>${p.product_code}</td>
                    <td>${p.product_name}</td>
                    <td>${p.product_group}</td>
                    <td>${p.current_stock ?? 0}</td>
                    <td><button class="btn btn-sm btn-primary viewHistory" data-id="${p.id}">History</button></td>
                </tr>`;
            });
            document.querySelector('#stockTable tbody').innerHTML = tbody;
        });
});

// View history
document.addEventListener('click', function(e){
    if(e.target.classList.contains('viewHistory')){
        let id = e.target.dataset.id;
        fetch(`/raw-stocks/${id}/history`)
        .then(res => res.json())
        .then(data => {
            let html = `<h6>${data.product.product_name} (${data.product.product_code})</h6>
                        <p>Current Stock: ${data.current_stock}</p>
                        <table class="table table-sm">
                        <thead><tr>
                        <th>Date</th><th>Type</th><th>Qty</th><th>Price</th><th>Total</th><th>Remarks</th>
                        </tr></thead><tbody>`;
            
            if (data.logs && data.logs.length > 0) {
                data.logs.forEach(log => {
                    html += `<tr>
                        <td>${log.trans_date}</td>
                        <td>${log.trans_type}</td>
                        <td>${log.qty}</td>
                        <td>${log.price}</td>
                        <td>${log.total_amount}</td>
                        <td>${log.remarks}</td>
                    </tr>`;
                });
            } else {
                html += `<tr><td colspan="6" class="text-center">No history found</td></tr>`;
            }

            html += '</tbody></table>';
            document.getElementById('historyContent').innerHTML = html;
            new bootstrap.Modal(document.getElementById('historyModal')).show();
        });
    }
});

</script>

@endpush