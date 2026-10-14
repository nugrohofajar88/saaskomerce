<?php

use App\Http\Controllers\Marketing\HomeController;
use App\Http\Controllers\Marketing\SignupController;
use Illuminate\Support\Facades\Route;

// Apex/no-subdomain (saaskomerce.test) - marketing + form signup tenant.
// domain() LITERAL (bukan '{subdomain}.'+base) dengan sengaja - domain dinamis
// ({placeholder}) bikin Laravel nge-pass nilainya POSISIONAL ke SEMUA controller
// method di rute itu (lihat catatan panjang di tenant_admin.php/storefront.php).
// Domain literal gak punya masalah itu, DAN mencegah rute marketing ini "nyaplok"
// request ke subdomain tenant (tanpa ini, '/' di sini bisa match duluan sebelum
// storefront.home sempat dicoba, krn urutan registrasi web.php lebih dulu).
Route::domain(config('app.base_domain'))->middleware('tenant.none')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('marketing.home');

    Route::get('/daftar', [SignupController::class, 'create'])->name('marketing.signup');
    Route::post('/daftar', [SignupController::class, 'store'])->name('marketing.signup.store');
    Route::get('/daftar/selesai/{tenant}', [SignupController::class, 'done'])->name('marketing.signup.done');
});
