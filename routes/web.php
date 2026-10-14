<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Slide 12, 15, 16, 18)
|--------------------------------------------------------------------------
*/

// 1. Dashboard Utama
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// 2. Resource Controller Produk (Slide 18)
Route::resource('products', ProductController::class);

// 3. Mutasi Stok (Barang Masuk & Keluar dengan Transaksi Database Atomik)
Route::prefix('stock')->name('stock.')->group(function () {
    Route::get('/in', [StockController::class, 'inCreate'])->name('in');
    Route::post('/in', [StockController::class, 'inStore'])->name('in.store');
    
    Route::get('/out', [StockController::class, 'outCreate'])->name('out');
    Route::post('/out', [StockController::class, 'outStore'])->name('out.store');
    
    Route::get('/history', [StockController::class, 'history'])->name('history');
});

// 4. Laporan Agregasi SQL (Slide 14 & 28)
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// 5. Master Data Kategori
Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

// 6. Master Data Supplier
Route::resource('suppliers', SupplierController::class)->only(['index', 'store', 'update', 'destroy']);
