<?php

use App\Http\Controllers\Tenant\AboutController;
use App\Http\Controllers\Tenant\Auth\LoginController;
use App\Http\Controllers\Tenant\CategoryController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\OrderController;
use App\Http\Controllers\Tenant\PaymentAccountController;
use App\Http\Controllers\Tenant\ProductController;
use App\Http\Controllers\Tenant\SettingController;
use Illuminate\Support\Facades\Route;

// {tenant}.saaskomerce.test/admin/* - panel admin tenant.
//
// SENGAJA TIDAK pakai Route::domain('{subdomain}.'+base) di sini - domain dgn
// placeholder ({subdomain}) bikin Laravel nambahin nilainya sbg parameter route
// PERTAMA, dan ControllerDispatcher nge-pass parameter ke method controller
// SECARA POSISIONAL (bukan by-name!) - jadi SEMUA argumen controller di rute ini
// bakal kegeser 1 posisi (order_id/product_id dll ketuker sama nilai subdomain).
// Ini bukan teori, ketahuan lewat debug nyata: Order $order di
// Storefront\OrderController malah nerima string "kopisenja" (nilai subdomain),
// bukan Order model-nya.
//
// Gantinya: rute ini TANPA domain constraint (match host APA PUN), tenant
// context & keamanan 100% digantungkan ke ResolveTenant (baca Host header
// manual, prepend di bootstrap/app.php) + tenant.require (404 kalau gak ketemu
// tenant) + tenant.approved. Aman krn web.php/platform.php sudah dibatasi ke
// domain LITERAL (gak ada placeholder), jadi gak akan rebutan match duluan.
Route::middleware(['tenant.require', 'tenant.approved'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/login', [LoginController::class, 'create'])->name('tenant.login');
        Route::post('/login', [LoginController::class, 'store'])->name('tenant.login.attempt');
        Route::post('/logout', [LoginController::class, 'destroy'])->name('tenant.logout');

        Route::middleware(['auth:tenant_admin', 'tenant.match-auth:tenant_admin'])->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('tenant.dashboard');

            Route::name('tenant.')->group(function () {
                Route::resource('categories', CategoryController::class)->except(['show']);
                Route::resource('products', ProductController::class);
                Route::delete('products/{product}/images/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
                Route::resource('payment-accounts', PaymentAccountController::class)->except(['show']);
            });

            Route::get('/orders', [OrderController::class, 'index'])->name('tenant.orders.index');
            Route::get('/orders/{order}', [OrderController::class, 'show'])->name('tenant.orders.show');
            Route::post('/orders/{order}/mark-paid', [OrderController::class, 'markPaid'])->name('tenant.orders.mark-paid');

            Route::get('/settings', [SettingController::class, 'index'])->name('tenant.settings.index');
            Route::post('/settings', [SettingController::class, 'update'])->name('tenant.settings.update');

            Route::get('/about', [AboutController::class, 'index'])->name('tenant.about.index');
            Route::post('/about', [AboutController::class, 'update'])->name('tenant.about.update');
        });
    });
