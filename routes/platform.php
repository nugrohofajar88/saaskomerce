<?php

use App\Http\Controllers\Platform\Auth\LoginController;
use App\Http\Controllers\Platform\TenantController;
use Illuminate\Support\Facades\Route;

// app.saaskomerce.test - super-admin platform (approve/suspend tenant).
// tenant.none: jaga-jaga, rute ini TIDAK boleh pernah jalan dgn tenant ter-bind.
Route::domain('app.'.config('app.base_domain'))
    ->middleware('tenant.none')
    ->group(function () {
        Route::get('/login', [LoginController::class, 'create'])->name('platform.login');
        Route::post('/login', [LoginController::class, 'store'])->name('platform.login.attempt');
        Route::post('/logout', [LoginController::class, 'destroy'])->name('platform.logout');

        Route::middleware('platform.admin')->group(function () {
            Route::get('/', [TenantController::class, 'index'])->name('platform.dashboard');
            Route::get('/tenants', [TenantController::class, 'index'])->name('platform.tenants.index');
            Route::post('/tenants/{tenant}/approve', [TenantController::class, 'approve'])->name('platform.tenants.approve');
            Route::post('/tenants/{tenant}/suspend', [TenantController::class, 'suspend'])->name('platform.tenants.suspend');
        });
    });
