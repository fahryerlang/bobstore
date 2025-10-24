<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductCatalogController::class, 'index'])->name('catalog.index');
Route::get('/products/{product}', [ProductCatalogController::class, 'show'])->name('catalog.show');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| CONTOH ROUTE BERDASARKAN ROLE
|--------------------------------------------------------------------------
*/

// Grup untuk Admin (Memiliki semua hak akses)
// Middleware 'role:admin' berarti HANYA admin yang bisa akses.
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/panel', function () {
        return '<h1>Selamat Datang di Panel Admin</h1>';
    })->name('admin.panel');

    // CRUD PRODUK (Route ini akan menghasilkan URL seperti /admin/products, /admin/products/create, dll.)
    // Nama route-nya akan menjadi 'products.index', 'products.store', dll.
    Route::resource('products', ProductController::class);

    // CRUD USERS (Kelola Pengguna)
    Route::resource('users', UserController::class);

});


// Grup untuk Kasir
// Middleware 'role:admin,kasir' berarti Admin DAN Kasir bisa akses.
Route::middleware(['auth', 'role:admin,kasir'])->group(function () {

    Route::get('/kasir/transaksi', function () {
        return '<h1>Halaman Proses Transaksi Kasir</h1>';
    })->name('kasir.transaksi');

    Route::get('/admin/laporan-penjualan', [SalesReportController::class, 'index'])->name('sales.report');
    Route::get('/admin/laporan-penjualan/cetak', [SalesReportController::class, 'print'])->name('sales.report.print');

    // Nanti di sini Anda letakkan route untuk memproses pembayaran
    // Route::post('/kasir/proses-bayar', [TransactionController::class, 'store']);

});


// Grup untuk Pembeli
// Middleware 'role:admin,pembeli' berarti Admin DAN Pembeli bisa akses.
// (Admin harus bisa melihat apa yang pembeli lihat)
Route::middleware(['auth', 'role:admin,pembeli'])->group(function () {
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/keranjang/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/keranjang/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

});


require __DIR__.'/auth.php';