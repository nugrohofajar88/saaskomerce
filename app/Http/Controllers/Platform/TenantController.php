<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Support\TenantProvisioningService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $tenants = Tenant::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('platform.tenants.index', [
            'tenants' => $tenants,
            'status' => $status,
            'pendingCount' => Tenant::where('status', 'pending')->count(),
        ]);
    }

    public function approve(Request $request, Tenant $tenant, TenantProvisioningService $provisioning): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:trial,active'],
        ]);

        $provisioning->approve($tenant, Auth::guard('platform')->user(), $validated['status']);

        return back()->with('status', "Tenant \"{$tenant->name}\" disetujui ({$validated['status']}).");
    }

    public function suspend(Request $request, Tenant $tenant, TenantProvisioningService $provisioning): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $provisioning->suspend($tenant, $validated['reason'] ?? null);

        return back()->with('status', "Tenant \"{$tenant->name}\" disuspend.");
    }
}
