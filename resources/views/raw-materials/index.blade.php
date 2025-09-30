@extends('layouts.app')   {{-- uses the sidebar + navbar master layout --}}

@section('title','Raw Materials')

@push('styles')
<style>
    /* ===== Page Styling ===== */

    
    .raw-container {
        max-width: 1100px;
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .page-header h3 {
        font-weight: 700;
        letter-spacing: .4px;
        color: #1e293b;
    }
    .page-header .btn {
        border-radius: 6px;
        font-weight: 600;
    }
 /* Dashboard-style button */
.btn-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); /* bright indigo/blue gradient */
    border: none;
    color: #fff;
    font-weight: 600;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.btn-info:hover,
.btn-info:focus {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);

    
}

.btn-info:active {
    transform: translateY(0);
    box-shadow: 0 3px 8px rgba(29, 78, 216, 0.3);
}


    #searchInput {
        margin-bottom: 15px;
        max-width: 320px;
    }

    /* ===== Table ===== */
    .table thead th {
        background: #f1f5f9;
        color: #334155;
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
    }
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }
    .table td {
        vertical-align: middle;
    }

    /* ===== Responsive ===== */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        #searchInput {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="raw-container">
    {{-- Header --}}
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-bricks me-2"></i> Raw Materials</h3>
        <div class="d-flex gap-2">
            <a href="/raw-material/create" class="btn btn-info text-white">+ Add New Material</a>               
              <a href="/raw-material/creates" class="btn btn-info text-white">+ Add Material Issues</a>             
        </div>
    </div>

    {{-- Search --}}
    <input type="text"
           id="searchInput"
           class="form-control"
           placeholder="Search material..."
           onkeyup="filterTable()">

    {{-- Table --}}
    <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover align-middle" id="rawMaterialsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Unit</th>
                    <th>Purchase Price</th>
                    <th>Stocks</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entries as $entry)
                    <tr>
                        <td>{{ $entries->firstItem() + $loop->index }}</td>
                        <td>{{ $entry->material_code }}</td>
                        <td>{{ $entry->material_name }}</td>
                        <td>{{ $entry->unit }}</td>
                        <td>{{ rtrim(rtrim(number_format($entry->purchase_price, 2), '0'), '.') }}</td>
                        <td>{{ $entry->stocks }}</td>
                        <td class="d-flex gap-2"> {{-- gap-2 gives consistent spacing --}}
                            {{-- View Button --}}
                            <a href="{{ url('/raw-material-issues?material_id='.$entry->id) }}"
                               class="btn btn-info btn-sm text-white">
                               View
                            </a>
                        
                            {{-- Delete Button --}}
                            <form action="{{ url('/raw-material/'.$entry->id) }}" {{-- proper delete URL --}}
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this material?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Delete
                                </button>
                            </form>
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-4">
        {{ $entries->links('pagination::bootstrap-5') }}
    </div>

    {{-- Back Button --}}
    <div class="d-flex justify-content-start mt-3">
        <a href="{{ url('/') }}" class="btn btn-secondary">
         Back
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    document.querySelectorAll("#rawMaterialsTable tbody tr").forEach(row => {
        const match = Array.from(row.cells).some(cell =>
            cell.textContent.toLowerCase().includes(input)
        );
        row.style.display = match ? "" : "none";
    });
}
</script>
@endpush
