<?php

namespace App\Http\Controllers\Storefront\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('storefront.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $attempt = [...$credentials, 'tenant_id' => tenant()->id, 'role' => 'customer'];

        if (! Auth::guard('customer')->attempt($attempt)) {
            return back()->withErrors(['email' => 'Email/password salah.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('storefront.home'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('storefront.home');
    }
}
