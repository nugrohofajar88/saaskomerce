<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::call() (BUKAN ::command()) - sama pola kayak larashop-be/simple-pos-
// backoffice: hosting shared sering matikan proc_open(), yang dibutuhkan
// ::command() buat spawn proses PHP terpisah. ::call() jalan in-process, aman.
Schedule::call(fn () => Artisan::call('orders:expire-unpaid'))->hourly();
