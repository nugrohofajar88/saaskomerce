<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Support\TenantProvisioningService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SignupController extends Controller
{
    // Subdomain yang gak boleh dipakai tenant - app/www/admin dipakai platform
    // sendiri; api/mail/ftp dijaga buat kebutuhan infra di masa depan.
    private const RESERVED_SUBDOMAINS = ['app', 'www', 'admin', 'api', 'mail', 'ftp', 'cpanel'];

    public function create(): View
    {
        return view('marketing.signup');
    }

    public function store(Request $request, TenantProvisioningService $provisioning): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subdomain' => [
                'required', 'string', 'max:63', 'alpha_dash:ascii',
                Rule::notIn(self::RESERVED_SUBDOMAINS),
                Rule::unique('tenants', 'subdomain'),
            ],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['required', 'email', 'max:255'],
            'owner_phone' => ['nullable', 'string', 'max:32'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $tenant = $provisioning->createPending([
            ...$validated,
            'subdomain' => strtolower($validated['subdomain']),
        ]);

        return redirect()->route('marketing.signup.done', ['tenant' => $tenant->id]);
    }

    public function done(Tenant $tenant): View
    {
        return view('marketing.signup-done', ['tenant' => $tenant]);
    }
}
