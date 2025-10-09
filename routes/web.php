<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\RawStockController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
// Home
// Route::get('/', function () {return view('welcome');})->name('welcome');
// Invoice
Route::get('/sales', [InvoiceController::class, 'create'])->name('invoice.create');

// Products
Route::get('/products/search', [InvoiceController::class, 'search'])->name('products.search');
Route::get('/buyers/{id}/balance', [InvoiceController::class, 'getBalance']);
Route::post('/sales-invoices', [InvoiceController::class, 'store']);
Route::get('/invoices', [InvoiceController::class, 'index'])
     ->name('invoices.index');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products', [ProductController::class, 'list']);   
Route::post('/products', [ProductController::class, 'store']); 
// web.php
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::get('/products', [ProductController::class, 'indesx'])->name('products.update-index');
// Raw Stocks
Route::get('/raw-stocks', [RawStockController::class, 'index'])->name('raw-stocks.index');
Route::get('/raw-stocks/search', [RawStockController::class, 'search']);
Route::get('/raw-stocks/{product}/history', [RawStockController::class, 'history']);

// Raw Materials 
Route::get('/raw-material', [RawMaterialController::class, 'index'])->name('raw_materials.index');
Route::get('/raw-material/create', [RawMaterialController::class, 'createIssue'])->name('raw_materials.create');
Route::get('/raw-material/creates', [RawMaterialController::class, 'createIssues'])->name('raw_materials.create-rawissues');
Route::post('/raw-material-issue', [RawMaterialController::class, 'storeIssue'])->name('raw_materials.issue.store');
Route::get('/api/raw-materials', [RawMaterialController::class, 'list'])->name('raw_materials.list');
Route::post('/api/raw-material-issue', [RawMaterialController::class, 'storeIssue'])->name('raw_materials.issue.store');
Route::get('/raw-material-issues', [RawMaterialController::class, 'showIssues']) ->name('raw-material.issues');
Route::post('/api/raw-material', [RawMaterialController::class, 'store'])->name('raw_materials.store');
Route::delete('/raw-material/{id}', [RawMaterialController::class, 'destroy'])
    ->name('raw_materials.destroy');

// Route::get('/api/raw-materials', [PurchaseController::class, 'materials']);


// Stores
 Route::get('/stores-json', [StoreController::class, 'list']); 

 //  Purchase
Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
Route::get('/api/suppliers', [PurchaseController::class, 'suppliers']);
Route::get('/api/raw-materials', [PurchaseController::class, 'materials']);

// Supplier
Route::get('/suppliers-json', [PurchaseController::class, 'suppliers']);
Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index'); 
Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create'); 
Route::post('/api/suppliers', [SupplierController::class, 'store'])->name('suppliers.api.store');
Route::post('/suppliers/{id}/update-status', [SupplierController::class, 'updateStatus']);

// Users 

Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users/{id}/update-status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

// Customers 


Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
Route::post('/customers/{id}/update-status', [CustomerController::class, 'updateStatus']) ->name('customers.updateStatus');


Route::get('/customers/invoice', [CustomerController::class, 'createInvoice'])->name('customers.customer_invoice');
Route::post('/customer-invoices', [CustomerController::class, 'storeInvoice'])->name('customer_invoices.store');

     use App\Http\Controllers\LedgerController;

Route::get('/ledger', [LedgerController::class, 'index'])->name('ledger.index');
Route::get('/ledger/create', [LedgerController::class, 'create'])->name('ledger.create');
Route::post('/ledger', [LedgerController::class, 'store'])->name('ledger.store');

use App\Http\Controllers\ReportController;
Route::get('/report/total-sales', [ReportController::class, 'totalSalesReport'])
    ->name('reports.total_sales');
    Route::get('/report/returns_sales_report', [ReportController::class, 'salesReturnReport'])
     ->name('reports.returns_sales_report');

     Route::get('/reports/total-purchases',[ReportController::class, 'totalPurchaseReport'])->name('reports.total_purchases');
 Route::get('/reports/purchase-returns', [ReportController::class, 'totalPurchaseReturnReport'])->name('reports.purchase_returns');
 Route::get('/reports', [ReportController::class, 'index'])->name('report.index');
 
 Route::get('/reports/sales-summary',[ReportController::class, 'salesSummary'])->name('reports.sales_summary');
 
 Route::get('/reports/raw-supplier-purchases', [ReportController::class, 'rawSupplierPurchaseSummary'])
 ->name('reports.raw_supplier_purchase_summary');
 
 Route::get('/reports/raw_material_item_report', [ReportController::class, 'rawMaterialItemReport'])
 ->name('reports.raw_material_item_report');
 
 Route::get('/reports/orders_summary', [ReportController::class, 'ordersSummary'])
 ->name('report.orders_summary');
 
 Route::get('/report/stock', [ReportController::class, 'stockReport'])
 ->name('reports.stock');
 
 Route::get('/report/sale_stock', [ReportController::class, 'saleStockReport'])
 ->name('reports.sale_stock_report');
 
 Route::get('/reports/sale-sheet', [ReportController::class, 'saleSheetReport'])->name('reports.sale_sheet');
 
 Route::get('/reports/ledger', [ReportController::class, 'ledgerReport'])
 ->name('reports.ledger');
 // web.php
Route::get('/reports/payments', [ReportController::class, 'paymentsReport'])->name('reports.payments');
Route::get('/reports/stock-summary', [ReportController::class, 'stockSummary'])
     ->name('reports.stock-summary');
     Route::get('/reports/daily-sheet', [ReportController::class, 'dailySheet'])
     ->name('reports.daily-sheet');

// Route::get('/reports/sales-detail', [ReportController::class, 'salesDetailReport'])->name('reports.sales_detail');
// Route::get('/reports/purchases-detail', [ReportController::class, 'purchaseDetailReport'])->name('reports.purchase_detail');
// Route::get('/reports/stock', [ReportController::class, 'stockReport'])->name('reports.stock');
// Route::get('/reports/summary', [ReportController::class, 'summaryReport'])->name('reports.summary');

// Route::get('/reports/purchase-detail', [ReportController::class, 'purchaseDetailReport'])->name('reports.purchase_detail');
// Route::get('/reports/purchase-detail-data', [ReportController::class, 'purchaseDetailReportData'])->name('reports.purchase_detail_data');
// Route::get('/reports/sales', [ReportController::class, 'salesReport'])->name('reports.sales');

use App\Http\Controllers\ExpenseController;

Route::resource('expenses', ExpenseController::class);

use App\Http\Controllers\GatePassController;
Route::get('/gate-pass', [GatePassController::class, 'index'])->name('gatepass.index');
Route::get('/gate-pass/create', [GatePassController::class, 'create'])->name('gatepass.create');
Route::post('/gate-pass/store', [GatePassController::class, 'store'])->name('gatepass.store');
Route::get('/gate-pass/{id}', [GatePassController::class, 'show'])->name('gatepass.show');

use App\Http\Controllers\PaymentController;

Route::get('/payments', [PaymentController::class,'index'])->name('payments.index');
Route::post('/payments/customer', [PaymentController::class,'storeCustomer'])->name('payments.customer.store');
Route::post('/payments/supplier', [PaymentController::class,'storeSupplier'])->name('payments.supplier.store');

Route::get('/reports/customers-outstanding', [PaymentController::class,'customersOutstanding'])->name('reports.customers.outstanding');
Route::get('/reports/suppliers-outstanding', [PaymentController::class,'suppliersOutstanding'])->name('reports.suppliers.outstanding');

Route::get('/dashboard', [PaymentController::class,'dashboard'])->name('dashboard');

Route::get('/reports/pending-receivables', [PaymentController::class, 'pendingReceivables'])->name('reports.pending_receivables');
Route::get('/reports/pending-payables', [PaymentController::class, 'pendingPayables'])->name('reports.pending_payables');
Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');


use App\Http\Controllers\PurchaseReturnController;

Route::get('/purchase-returns', [PurchaseReturnController::class, 'index'])
        ->name('purchase_returns.index');

Route::post('/purchase-returns/search', [PurchaseReturnController::class, 'search'])
        ->name('purchase_returns.search');

Route::post('/purchase-returns/{purchase}', [PurchaseReturnController::class, 'store'])
        ->name('purchase_returns.store');
        Route::get('/purchase-returns/search', [PurchaseReturnController::class, 'searchGet'])
        ->name('purchase_returns.search.get');

        
        use App\Http\Controllers\SalesReturnController;

        Route::get('/sales-returns', [SalesReturnController::class, 'index'])
            ->name('sales_returns.index');
        
        Route::post('/sales-returns/search', [SalesReturnController::class, 'search'])
            ->name('sales_returns.search');
        
        Route::get('/sales-returns/search', [SalesReturnController::class, 'searchGet'])
            ->name('sales_returns.search.get');
        
        Route::post('/sales-returns/{invoice}', [SalesReturnController::class, 'store'])
            ->name('sales_returns.store');
        

            use App\Http\Controllers\DashboardController;

            // Root route now runs the controller
            Route::get('/', [DashboardController::class, 'index'])->name('welcome');
            