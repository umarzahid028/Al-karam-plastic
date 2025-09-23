<nav class="sidebar col-md-3 col-lg-2 d-md-block">
  <div class="d-flex flex-column flex-shrink-0 px-2 py-3">
    <ul class="nav nav-pills flex-column mb-auto">

      <li class="sidebar-heading">Raw Materials</li>
      <li><a class="nav-link {{ request()->routeIs('raw_materials.*')?'active':'' }}"
             href="{{ route('raw_materials.index') }}"><i class="bi bi-bricks me-2"></i>Add Raw Material</a></li>
      <li><a class="nav-link {{ request()->routeIs('purchases.*')?'active':'' }}"
             href="{{ route('purchases.index') }}"><i class="bi bi-box-seam me-2"></i>Purchase / Issue Raw</a></li>

      <li class="sidebar-heading">Sales</li>
      <li><a class="nav-link {{ request()->routeIs('invoice.create')?'active':'' }}"
             href="{{ route('invoice.create') }}"><i class="bi bi-receipt me-2"></i>Create Invoice</a></li>
      <li><a class="nav-link" href="#"><i class="bi bi-credit-card me-2"></i>Make Payment</a></li>
      @include('report.index')

      <li class="sidebar-heading">Management</li>
      <li><a class="nav-link {{ request()->routeIs('users.*')?'active':'' }}"
             href="{{ route('users.index') }}"><i class="bi bi-person-plus me-2"></i>Add Users</a></li>
      <li><a class="nav-link {{ request()->routeIs('suppliers.*')?'active':'' }}"
             href="{{ route('suppliers.index') }}"><i class="bi bi-truck me-2"></i>Add Supplier</a></li>
      <li><a class="nav-link {{ request()->routeIs('products.index')?'active':'' }}"
             href="{{ route('products.index') }}"><i class="bi bi-box me-2"></i>Add Product</a></li>
      <li><a class="nav-link {{ request()->routeIs('products.update-index')?'active':'' }}"
             href="{{ route('products.update-index') }}"><i class="bi bi-arrow-repeat me-2"></i>Update Product</a></li>
      <li><a class="nav-link {{ request()->routeIs('customers.*')?'active':'' }}"
             href="{{ route('customers.index') }}"><i class="bi bi-buildings me-2"></i>Add/Update Customer</a></li>
      <li><a class="nav-link {{ request()->routeIs('ledger.*')?'active':'' }}"
             href="{{ route('ledger.index') }}"><i class="bi bi-journal-text me-2"></i>View Ledger</a></li>
      <li><a class="nav-link {{ request()->routeIs('expenses.*')?'active':'' }}"
             href="{{ route('expenses.index') }}"><i class="bi bi-cash-stack me-2"></i>Add Expense</a></li>
      <li><a class="nav-link {{ request()->routeIs('gatepass.*')?'active':'' }}"
             href="{{ route('gatepass.index') }}"><i class="bi bi-key me-2"></i>Generate Pass</a></li>

      @include('purchase_returns.index')
      @include('sales_returns.index')
    </ul>
  </div>
</nav>
