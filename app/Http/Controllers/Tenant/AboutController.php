<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Support\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(SettingService $settings): View
    {
        return view('tenant.about.index', [
            'aboutContent' => $settings->aboutContent(),
            'whyUsContent' => $settings->whyUsContent(),
        ]);
    }

    public function update(Request $request, SettingService $settings): RedirectResponse
    {
        $validated = $request->validate([
            'about_content' => ['nullable', 'string', 'max:5000'],
            'why_us_content' => ['nullable', 'string', 'max:5000'],
        ]);

        $settings->set('about_content', $validated['about_content'] ?? '');
        $settings->set('why_us_content', $validated['why_us_content'] ?? '');

        return back()->with('status', 'Konten "Tentang Toko" disimpan.');
    }
}
