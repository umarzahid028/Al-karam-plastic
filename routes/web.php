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
Route::get('/', function () {return view('welcome');})->name('welcome');
// Invoice
Route::get('/sales', [InvoiceController::class, 'create'])->name('invoice.create');

// Products
Route::get('/products/search', [InvoiceController::class, 'search'])->name('products.search');
Route::get('/buyers/{id}/balance', [InvoiceController::class, 'getBalance']);
Route::post('/sales-invoices', [InvoiceController::class, 'store']);

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products', [ProductController::class, 'list']);   
Route::post('/products', [ProductController::class, 'store']); 
// web.php
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');

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

Route::get('/reports/sales-detail', [ReportController::class, 'salesDetailReport'])->name('reports.sales_detail');
Route::get('/reports/purchases-detail', [ReportController::class, 'purchaseDetailReport'])->name('reports.purchase_detail');
Route::get('/reports/stock', [ReportController::class, 'stockReport'])->name('reports.stock');
Route::get('/reports/summary', [ReportController::class, 'summaryReport'])->name('reports.summary');

Route::get('/reports/purchase-detail', [ReportController::class, 'purchaseDetailReport'])->name('reports.purchase_detail');
Route::get('/reports/purchase-detail-data', [ReportController::class, 'purchaseDetailReportData'])->name('reports.purchase_detail_data');
use App\Http\Controllers\ExpenseController;

Route::resource('expenses', ExpenseController::class);
Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::post('/expenses/store-multiple', [ExpenseController::class, 'storeMultiple'])->name('expenses.storeMultiple');
// Route::get('/expenses/{id}', [ExpenseController::class, 'show'])->name('expenses.show');
use App\Http\Controllers\GatePassController;
Route::get('/gate-pass', [GatePassController::class, 'index'])->name('gatepass.index');
Route::get('/gate-pass/create', [GatePassController::class, 'create'])->name('gatepass.create');
Route::post('/gate-pass/store', [GatePassController::class, 'store'])->name('gatepass.store');
Route::get('/gate-pass/{id}', [GatePassController::class, 'show'])->name('gatepass.show');
