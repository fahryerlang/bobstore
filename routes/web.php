<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\DiscountRuleController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Kasir\ProductController as KasirProductController;
use App\Http\Controllers\Kasir\CustomerRegistrationController;
use App\Http\Controllers\Kasir\TransactionController as KasirTransactionController;
use App\Http\Controllers\Customer\TransactionController as CustomerTransactionController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ExportController;

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

    // Discount & Promotion Management
    Route::resource('discounts', DiscountController::class);

    Route::post('discounts/{discount}/rules', [DiscountRuleController::class, 'store'])->name('discounts.rules.store');
    Route::put('discount-rules/{discountRule}', [DiscountRuleController::class, 'update'])->name('discount-rules.update');
    Route::delete('discount-rules/{discountRule}', [DiscountRuleController::class, 'destroy'])->name('discount-rules.destroy');

    Route::post('discounts/{discount}/coupons', [CouponController::class, 'store'])->name('discounts.coupons.store');
    Route::put('coupons/{coupon}', [CouponController::class, 'update'])->name('coupons.update');
    Route::delete('coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');

});


// Grup untuk Kasir
// Middleware 'role:admin,kasir' berarti Admin DAN Kasir bisa akses.
Route::middleware(['auth', 'role:admin,kasir'])->group(function () {
    Route::get('/kasir/transaksi', [KasirTransactionController::class, 'index'])->name('kasir.transactions.index');
    Route::get('/kasir/transaksi/cari-produk', [KasirTransactionController::class, 'searchProducts'])->name('kasir.transactions.search');
    Route::post('/kasir/transaksi', [KasirTransactionController::class, 'store'])->name('kasir.transactions.store');
    Route::get('/kasir/transaksi/{transaction:invoice_number}', [KasirTransactionController::class, 'show'])->name('kasir.transactions.show');

    Route::get('/kasir/member-baru', [CustomerRegistrationController::class, 'create'])->name('kasir.customers.create');
    Route::post('/kasir/member-baru', [CustomerRegistrationController::class, 'store'])->name('kasir.customers.store');

    Route::get('/kasir/produk', [KasirProductController::class, 'index'])->name('kasir.products.index');

    Route::get('/admin/laporan-penjualan', [SalesReportController::class, 'index'])->name('sales.report');
    Route::get('/admin/laporan-penjualan/cetak', [SalesReportController::class, 'print'])->name('sales.report.print');

    // Export page
    Route::get('/export', function () {
        return view('exports.index');
    })->name('export.index');

    // Export routes for Admin and Kasir
    Route::prefix('export')->name('export.')->group(function () {
        // Products Export
        Route::get('/products/excel', [ExportController::class, 'exportProductsExcel'])->name('products.excel');
        Route::get('/products/pdf', [ExportController::class, 'exportProductsPdf'])->name('products.pdf');
        
        // Sales Export
        Route::get('/sales/excel', [ExportController::class, 'exportSalesExcel'])->name('sales.excel');
        Route::get('/sales/pdf', [ExportController::class, 'exportSalesPdf'])->name('sales.pdf');
        
        // Customers Export
        Route::get('/customers/excel', [ExportController::class, 'exportCustomersExcel'])->name('customers.excel');
        Route::get('/customers/pdf', [ExportController::class, 'exportCustomersPdf'])->name('customers.pdf');
    });

});


// Grup untuk Pembeli
// Middleware 'role:admin,pembeli' berarti Admin DAN Pembeli bisa akses.
// (Admin harus bisa melihat apa yang pembeli lihat)
Route::middleware(['auth', 'role:admin,pembeli'])->group(function () {
    Route::get('/belanja', [ProductCatalogController::class, 'index'])->name('customer.shop');
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/keranjang/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('cart.processCheckout');
    Route::post('/beli-langsung', [CartController::class, 'buyNow'])->name('cart.buyNow');

    Route::get('/riwayat-transaksi', [CustomerTransactionController::class, 'index'])->name('customer.transactions.index');

});


require __DIR__.'/auth.php';