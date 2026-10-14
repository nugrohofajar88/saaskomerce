<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Support\SettingService;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(SettingService $settings): View
    {
        abort_unless($settings->hasAboutPage(), 404);

        return view('storefront.about', [
            'aboutContent' => $settings->aboutContent(),
            'whyUsContent' => $settings->whyUsContent(),
        ]);
    }
}
