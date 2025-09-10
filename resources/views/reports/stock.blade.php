<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Stock Position</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Stock Position</h3>

  <!-- Search Form -->
  <form method="get" class="row g-2 mb-3">
    <div class="col-auto">
      <input type="text" name="code" value="{{ $filters['code'] ?? '' }}" class="form-control" placeholder="Search Code">
    </div>
    <div class="col-auto">
      <input type="text" name="name" value="{{ $filters['name'] ?? '' }}" class="form-control" placeholder="Search Product">
    </div>
    <div class="col-auto">
      <button class="btn btn-primary">Search</button>
    </div>
    <div class="col-auto">
      <a href="{{ route('reports.stock') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
  </form>

  <!-- Stock Table -->
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="table-light">
        <tr>
          <th>Code</th>
          <th>Product</th>
          <th class="text-end">Total Purchased</th>
          <th class="text-end">Total Sold</th>
          <th class="text-end">Current Stock</th>
        </tr>
      </thead>
      <tbody>
        @forelse($records as $r)
          <tr>
            <td>{{ $r->material_code }}</td>
            <td>{{ $r->material_name }}</td>
            <td class="text-end">
              {{rtrim(rtrim(number_format($r->total_purchased,2), '0'), '.') }}
            </td>
            <td class="text-end">
              {{rtrim(rtrim(number_format($r->total_sold,2), '0'), '.') }}
            </td>
            <td class="text-end">
              {{rtrim(rtrim(number_format($r->current_stock,2), '0'), '.') }}
          </td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted">No products found</td></tr>
        @endforelse
      </tbody>
      @if($records->count())
      <tfoot>
        <tr class="fw-bold">
          <td colspan="2" class="text-end">Totals</td>
          <td class="text-end">
            {{rtrim(rtrim(number_format($totals['purchased'],2), '0'), '.') }}
          </td>
          <td class="text-end">
            {{rtrim(rtrim(number_format($totals['sold'],2), '0'), '.') }}
           </td>
          <td class="text-end">
            {{rtrim(rtrim(number_format($totals['stock'],2), '0'), '.') }}
          </td>
        </tr>
      </tfoot>
      @endif
    </table>
  </div>
</div>
</body>
</html>
