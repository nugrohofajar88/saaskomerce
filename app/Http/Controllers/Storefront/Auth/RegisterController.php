<?php

namespace App\Http\Controllers\Storefront\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('storefront.auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // unique PER TENANT (lihat migrasi users) - email yg sama boleh dipakai
            // di toko lain, tapi gak boleh dobel di toko yg sama.
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->where('tenant_id', tenant()->id)],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'tenant_id' => tenant()->id,
            'role' => 'customer',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::guard('customer')->login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('storefront.home'));
    }
}
