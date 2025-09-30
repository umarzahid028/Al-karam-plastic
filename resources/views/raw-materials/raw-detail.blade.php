@extends('layouts.app')

@section('title', 'Raw Material Detail')

@push('styles')
<style>

.container {
    max-width: 1000px;
    margin: 50px auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
h3 {
    color: #333;
    margin-bottom: 10px;
}
.material-info {
    font-size: 1rem;
    color: #555;
    margin-bottom: 20px;
}
.table thead {
    background-color: #6366f1;
    color: white;
}
.table-hover tbody tr:hover {
    background-color: #f1f1f1;
}

.btn-info {
        background: linear-gradient(135deg, #3b82f6 0%, #4579e9 100%); 
    border: none;
    color: #fff;
    font-weight: 600;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(115, 149, 224, 0.25);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .btn-info:hover { 
        background:#3b82f6 ;
     }
     .btn-info:hover,
.btn-info:focus {
    background: linear-gradient(135deg, #4b72c7 0%, #526eba 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(29, 78, 216, 0.35);
}
@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto;
    }
}
</style>
@endpush
@section('content')
<div class="container">

    <!-- Back Button -->
    <button class="btn btn-info mb-3" onclick="window.history.back()">← Back to Materials</button>

    <!-- Material Info -->
    <h3>
        {{ $material->material_name }} ({{ $material->material_code }})
    </h3>
    <p class="material-info">
        <strong>Unit:</strong> {{ $material->unit }} | 
        <strong>Stock:</strong> {{ $material->stocks }} | 
        <strong>Store:</strong> {{ $material->store->store_name ?? '-' }}
    </p>

    <!-- Issue History -->
    <h4 class="mt-4 mb-3">Issue History</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Issue No</th>
                    <th>Date</th>
                    <th>Issued By</th>
                    <th>Issued To</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($issueItems as $item)
                <tr>
                    <td>{{ $item->issue->issue_no ?? 'N/A' }}</td>
                    <td>{{ optional($item->issue)->issue_date ? \Carbon\Carbon::parse($item->issue->issue_date)->format('d M, Y') : 'N/A' }}</td>
                    <td>{{ $item->issue->issued_by ?? 'N/A' }}</td>
                    <td>{{ $item->issue->issued_to ?? 'N/A' }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->unit ?? '-' }}</td>
                    <td>{{ $item->issue->remarks ?? '-' }}</td>
                    
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No issue history available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
