<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductWebController;
use App\Http\Controllers\StockWebController;
use App\Http\Controllers\POSWebController;
use App\Http\Controllers\ReportWebController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportExportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
// Auth pages
Route::get('/', function () {
    // Redirect authenticated users to dashboard
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Login routes
Route::get('/login', function () {
    // Redirect authenticated users to dashboard
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('login');

// Auth actions
Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logoutWeb'])->name('logout');

// Protected dashboard
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function(){

    // Products - View products (all authenticated users)
    Route::get('products', [ProductWebController::class, 'index'])->name('products.index');
    Route::get('products/{product}/show', [ProductWebController::class,'show'])->name('products.show');
    
    // Products - Manage (requires manage-products permission)
    Route::middleware('permission:manage-products')->group(function () {
        Route::get('products/create', [ProductWebController::class, 'create'])->name('products.create');
        Route::post('products', [ProductWebController::class, 'store'])->name('products.store');
        Route::get('products/{product}/edit', [ProductWebController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductWebController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [ProductWebController::class, 'destroy'])->name('products.destroy');
    });

    // Stock - View (all authenticated users can view)
    Route::get('stock', [StockWebController::class,'index'])->name('stock.index');
    
    // Stock - Manage (requires manage-stock permission)
    Route::middleware('permission:manage-stock')->group(function () {
        Route::get('stock/create', [StockWebController::class,'create'])->name('stock.create');
        Route::post('stock', [StockWebController::class,'store'])->name('stock.store');
    });

    // POS - Requires process-sales permission
    Route::middleware('permission:process-sales')->group(function () {
        Route::get('pos', [POSWebController::class,'index'])->name('pos.index');
        Route::post('pos/checkout', [POSWebController::class,'checkout'])->name('pos.checkout');
        Route::get('pos/sales', [POSWebController::class,'salesHistory'])->name('pos.sales');
        Route::get('pos/receipt/{sale}', [POSWebController::class, 'receipt'])->name('pos.receipt');
    });

    // Reports - Requires view-reports permission
    Route::middleware('permission:view-reports')->prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'dashboard'])->name('reports.dashboard');
        Route::get('/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/purchases', [ReportController::class, 'purchases'])->name('reports.purchases');
        Route::get('/fast-moving', [ReportController::class, 'fastMoving'])->name('reports.fastmoving');
        Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profitloss');
        Route::get('/stock-valuation', [ReportController::class, 'stockValuation'])->name('reports.stockvaluation');
        Route::get('/expiry', [ReportController::class, 'expiry'])->name('reports.expiry');
        Route::get('/low-stock', [ReportController::class, 'lowStock'])->name('reports.lowstock');
        
        // Export routes - requires export-reports permission
        Route::middleware('permission:export-reports')->group(function () {
            Route::get('/sales/export/pdf', [ReportController::class, 'exportSalesPdf'])->name('reports.sales.pdf');
            Route::get('/sales/export/excel', [ReportController::class, 'exportSalesExcel'])->name('reports.sales.excel');
            Route::get('/purchases/export/pdf', [ReportController::class, 'exportPurchasesPdf'])->name('reports.purchases.pdf');
            Route::get('/fast-moving/export/excel', [ReportController::class, 'exportFastMovingExcel'])->name('reports.fastmoving.excel');
            Route::get('/fast-moving/export/pdf', [ReportController::class, 'exportFastMovingPdf'])->name('reports.fastmoving.pdf');
            Route::get('/profit-loss/export/excel', [ReportController::class, 'exportProfitLossExcel'])->name('reports.profitloss.excel');
            Route::get('/profit-loss/export/pdf', [ReportController::class, 'exportProfitLossPdf'])->name('reports.profitloss.pdf');
            Route::get('/stock-valuation/export/excel', [ReportController::class, 'exportStockValuationExcel'])->name('reports.stockvaluation.excel');
            Route::get('/stock-valuation/export/pdf', [ReportController::class, 'exportStockValuationPdf'])->name('reports.stockvaluation.pdf');
            Route::get('/expiry/export/excel', [ReportController::class, 'exportExpiryExcel'])->name('reports.expiry.excel');
            Route::get('/expiry/export/pdf', [ReportController::class, 'exportExpiryPdf'])->name('reports.expiry.pdf');
            Route::get('/low-stock/export/excel', [ReportController::class, 'exportLowStockExcel'])->name('reports.lowstock.excel');
            Route::get('/low-stock/export/pdf', [ReportController::class, 'exportLowStockPdf'])->name('reports.lowstock.pdf');
        });
    });

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        // User Management
        Route::resource('users', UserController::class);
        
        // Category Management
        Route::resource('categories', CategoryController::class);
        
        // Permission Management
        Route::get('permissions/roles', [PermissionController::class, 'roles'])->name('permissions.roles');
        Route::post('permissions/roles/{role}', [PermissionController::class, 'updateRolePermissions'])->name('permissions.roles.update');
        Route::get('users/{user}/permissions', [PermissionController::class, 'edit'])->name('users.permissions');
        Route::put('users/{user}/permissions', [PermissionController::class, 'update'])->name('users.permissions.update');
    });
});

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
    // Add these missing profit-loss export routes:
    Route::get('/profit-loss/export/excel', [ReportController::class, 'exportProfitLossExcel'])->name('reports.profitloss.excel');
    Route::get('/profit-loss/export/pdf', [ReportController::class, 'exportProfitLossPdf'])->name('reports.profitloss.pdf');

    Route::get('/stock-valuation', [ReportController::class, 'stockValuation'])->name('reports.stockvaluation');
    // Add stock valuation export routes:
    Route::get('/stock-valuation/export/excel', [ReportController::class, 'exportStockValuationExcel'])->name('reports.stockvaluation.excel');
    Route::get('/stock-valuation/export/pdf', [ReportController::class, 'exportStockValuationPdf'])->name('reports.stockvaluation.pdf');

    Route::get('/expiry', [ReportController::class, 'expiry'])->name('reports.expiry');
    // Add expiry export routes:
    Route::get('/expiry/export/excel', [ReportController::class, 'exportExpiryExcel'])->name('reports.expiry.excel');
    Route::get('/expiry/export/pdf', [ReportController::class, 'exportExpiryPdf'])->name('reports.expiry.pdf');

    Route::get('/low-stock', [ReportController::class, 'lowStock'])->name('reports.lowstock');
    // Add low stock export routes:
    Route::get('/low-stock/export/excel', [ReportController::class, 'exportLowStockExcel'])->name('reports.lowstock.excel');
    Route::get('/low-stock/export/pdf', [ReportController::class, 'exportLowStockPdf'])->name('reports.lowstock.pdf');
});
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

