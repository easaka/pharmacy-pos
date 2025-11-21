<?php
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockEntryController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('products', ProductController::class);
    Route::post('stock', [StockEntryController::class,'store']);
    Route::post('pos/checkout', [POSController::class,'checkout']);
    Route::get('reports/low-stock', [ReportController::class,'lowStock']);
    Route::get('reports/expiry-soon', [ReportController::class,'expirySoon']);
});

// Public routes - Login only
Route::post('/login', [AuthController::class, 'login']);

// Registration restricted to admin only - should be done via web interface
// Route::post('/register', [AuthController::class, 'register']); // Removed - only admin can register via web

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});