<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Support\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(SettingService $settings): View
    {
        return view('tenant.settings.index', [
            'storeOpen' => $settings->storeOpen(),
            'paymentTransferEnabled' => $settings->paymentTransferEnabled(),
        ]);
    }

    public function update(Request $request, SettingService $settings): RedirectResponse
    {
        $settings->set('store_open', $request->boolean('store_open') ? '1' : '0');
        $settings->set('payment_transfer_enabled', $request->boolean('payment_transfer_enabled') ? '1' : '0');

        return back()->with('status', 'Pengaturan disimpan.');
    }
}
