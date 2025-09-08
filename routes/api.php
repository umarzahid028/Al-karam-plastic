<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\RawSupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LedgerEntryController;

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API working fine!']);
});

// Users CRUD routes
Route::apiResource('users', UserController::class);
// stores CRUD routes
Route::apiResource('stores', StoreController::class);
// Raw Material CRUD routes 


Route::apiResource('raw-materials', RawMaterialController::class);

Route::apiResource('raw-suppliers', RawSupplierController::class);


Route::apiResource('customers', CustomerController::class);


Route::apiResource('ledger-entries', LedgerEntryController::class);



use App\Http\Controllers\PurchaseController;

Route::apiResource('purchases', PurchaseController::class);
use App\Http\Controllers\RawMaterialIssueController;

Route::apiResource('raw-material-issues', RawMaterialIssueController::class);

use App\Http\Controllers\ProductController;

Route::apiResource('products', ProductController::class);
use App\Http\Controllers\RawStockController;

Route::apiResource('raw-stocks', RawStockController::class);

use App\Http\Controllers\RawStockLogController;

Route::apiResource('raw-stock-logs', RawStockLogController::class);

use App\Http\Controllers\SalesInvoiceController;

Route::apiResource('sales-invoices', SalesInvoiceController::class);
