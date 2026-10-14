<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('tenant.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // tenant_id = tenant subdomain yang lagi dibuka -> gak mungkin login sbg
        // admin tenant LAIN walau tau email+password-nya org lain.
        $attempt = [...$credentials, 'tenant_id' => tenant()->id, 'role' => 'tenant_admin'];

        if (! Auth::guard('tenant_admin')->attempt($attempt)) {
            return back()->withErrors(['email' => 'Email/password salah.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('tenant.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('tenant_admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('tenant.login');
    }
}
