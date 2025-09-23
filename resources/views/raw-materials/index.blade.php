@extends('layouts.app')   {{-- uses the sidebar + navbar master layout --}}

@section('title','Raw Material')

@push('styles')
<style>
        body { font-family: Arial; background:#f5f7fa; }
        .container { max-width: 1000px; margin: 50px auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 6px 25px rgba(0,0,0,0.15);}
        .table-hover tbody tr:hover { background-color: #f1f1f1; }
        .btn-info { background:#17a2b8; color:white; border-radius:6px; box-shadow: 0 2px 5px rgba(0,0,0,0.15);}
        .btn-info:hover { background:#138496; color:white; }
        #searchInput { margin-bottom: 15px; max-width: 300px; }
        @media (max-width: 768px) {
            .table-responsive { overflow-x: auto; }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Raw Materials</h3>
            <div class="d-flex gap-2">
                <a href="/raw-material/create" class="btn btn-info">+ Add New Material</a>
                <a href="/raw-material/creates" class="btn btn-info">+ Add Material Issues</a>
            </div>
        </div>

        <!-- Search input -->
        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search Material..." onkeyup="filterTable()">

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="rawMaterialsTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Unit</th>
                        <th>Purchase Price</th>
                        <th>Stocks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                    <tr>
                        <td>{{ $entries->firstItem() + $loop->index }}</td>
                        <td>{{ $entry->material_code }}</td>
                        <td>{{ $entry->material_name }}</td>
                        <td>{{ $entry->unit }}</td>
                        <td>
                            {{rtrim(rtrim(number_format($entry->purchase_price, 2), '0'), '.') }}
                        </td>
                        <td>{{ $entry->stocks }}</td>
                        <td>
                            <a href="/raw-material-issues?material_id={{ $entry->id }}" class="btn btn-info btn-sm">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $entries->links('pagination::bootstrap-5') }}
            </div>

            <!-- Back button -->
            <div class="d-flex justify-content-between mt-3">
                <a href="/" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
    // Search/filter function
    function filterTable() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        const rows = document.querySelectorAll("#rawMaterialsTable tbody tr");
        rows.forEach(row => {
            row.style.display = Array.from(row.cells).some(cell =>
                cell.textContent.toLowerCase().includes(input)
            ) ? "" : "none";
        });
    }
    </script>
@endpush
