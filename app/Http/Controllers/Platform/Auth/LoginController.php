<?php

namespace App\Http\Controllers\Platform\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('platform.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // tenant_id null -> cuma cocok sama user platform_admin, bukan tenant_admin/customer.
        if (! Auth::guard('platform')->attempt([...$credentials, 'tenant_id' => null, 'role' => 'platform_admin'])) {
            return back()->withErrors(['email' => 'Email/password salah.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('platform.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('platform')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('platform.login');
    }
}
