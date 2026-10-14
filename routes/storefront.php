<?php

use App\Http\Controllers\Storefront\AboutController;
use App\Http\Controllers\Storefront\Auth\LoginController;
use App\Http\Controllers\Storefront\Auth\RegisterController;
use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\CatalogController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\OrderController;
use Illuminate\Support\Facades\Route;

// {tenant}.saaskomerce.test/* - storefront publik. Cart jalan tanpa login
// (session-based), checkout WAJIB login (lihat CheckoutController).
//
// TANPA Route::domain('{subdomain}.'+base) dgn sengaja - lihat penjelasan
// panjang di routes/tenant_admin.php (domain ber-placeholder bikin argumen
// controller kegeser posisinya). Tenant context 100% dari ResolveTenant +
// tenant.require/tenant.approved, bukan dari parameter domain.
Route::middleware(['tenant.require', 'tenant.approved'])
    ->group(function () {
        Route::get('/', [CatalogController::class, 'index'])->name('storefront.home');
        Route::get('/produk/{product:slug}', [CatalogController::class, 'show'])->name('storefront.product');
        Route::get('/tentang', [AboutController::class, 'index'])->name('storefront.about');

        Route::get('/keranjang', [CartController::class, 'show'])->name('storefront.cart');
        Route::post('/keranjang', [CartController::class, 'store'])->name('storefront.cart.store');
        Route::patch('/keranjang/{variant}', [CartController::class, 'update'])->name('storefront.cart.update');
        Route::delete('/keranjang/{variant}', [CartController::class, 'destroy'])->name('storefront.cart.destroy');

        Route::get('/login', [LoginController::class, 'create'])->name('storefront.login');
        Route::post('/login', [LoginController::class, 'store'])->name('storefront.login.attempt');
        Route::post('/logout', [LoginController::class, 'destroy'])->name('storefront.logout');
        Route::get('/daftar', [RegisterController::class, 'create'])->name('storefront.register');
        Route::post('/daftar', [RegisterController::class, 'store'])->name('storefront.register.attempt');

        Route::middleware(['auth:customer', 'tenant.match-auth:customer'])->group(function () {
            Route::get('/checkout', [CheckoutController::class, 'show'])->name('storefront.checkout');
            Route::post('/checkout', [CheckoutController::class, 'store'])->name('storefront.checkout.store');

            Route::get('/pesanan', [OrderController::class, 'index'])->name('storefront.orders.index');
            Route::get('/pesanan/{order:order_number}', [OrderController::class, 'show'])->name('storefront.order.show');
            Route::post('/pesanan/{order:order_number}/bukti-bayar', [OrderController::class, 'uploadProof'])->name('storefront.order.upload-proof');
        });
    });
