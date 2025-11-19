<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductWebController;
use App\Http\Controllers\StockWebController;
use App\Http\Controllers\POSWebController;
use App\Http\Controllers\ReportWebController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportExportController;
// Auth pages
Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Auth actions
Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.post');
Route::post('/register', [AuthController::class, 'registerWeb'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logoutWeb'])->name('logout');

// Protected dashboard
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function(){

    // Products
    Route::resource('products', ProductWebController::class)->except(['show']);
    Route::get('products/{product}/show', [ProductWebController::class,'show'])->name('products.show');

    // Stock
    Route::get('stock', [StockWebController::class,'index'])->name('stock.index');
    Route::get('stock/create', [StockWebController::class,'create'])->name('stock.create');
    Route::post('stock', [StockWebController::class,'store'])->name('stock.store');

    // POS
    Route::get('pos', [POSWebController::class,'index'])->name('pos.index');
    Route::post('pos/checkout', [POSWebController::class,'checkout'])->name('pos.checkout');
    Route::get('pos/sales', [POSWebController::class,'salesHistory'])->name('pos.sales');
    // Printable receipt
    Route::get('pos/receipt/{sale}', [POSWebController::class, 'receipt'])->name('pos.receipt');


    Route::prefix('reports')->middleware(['auth', 'verified'])->group(function () {

    Route::get('/', [ReportController::class, 'dashboard'])->name('reports.dashboard');

    Route::get('/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/sales/export/pdf', [ReportController::class, 'exportSalesPdf'])->name('reports.sales.pdf');
    Route::get('/sales/export/excel', [ReportController::class, 'exportSalesExcel'])->name('reports.sales.excel');

    Route::get('/purchases', [ReportController::class, 'purchases'])->name('reports.purchases');
    Route::get('/purchases/export/pdf', [ReportController::class, 'exportPurchasesPdf'])->name('reports.purchases.pdf');

    Route::get('/fast-moving', [ReportController::class, 'fastMoving'])->name('reports.fastmoving');
    Route::get('/fast-moving/export/excel', [ReportController::class, 'exportFastMovingExcel'])->name('reports.fastmoving.excel');
    Route::get('/fast-moving/export/pdf', [ReportController::class, 'exportFastMovingPdf'])->name('reports.fastmoving.pdf');

    Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profitloss');

    Route::get('/stock-valuation', [ReportController::class, 'stockValuation'])->name('reports.stockvaluation');

    Route::get('/expiry', [ReportController::class, 'expiry'])->name('reports.expiry');

    Route::get('/low-stock', [ReportController::class, 'lowStock'])->name('reports.lowstock');
});

});