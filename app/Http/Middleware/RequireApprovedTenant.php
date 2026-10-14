<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Dipasang SETELAH RequireTenantContext (jadi tenant pasti sudah ter-bind).
 * Status pending/suspended -> halaman informatif (BUKAN 404 polos), karena owner
 * yang baru signup wajar langsung coba buka subdomain-nya sendiri buat ngecek.
 */
class RequireApprovedTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! tenant()->isApproved()) {
            return response()->view('tenant.not-approved', ['tenant' => tenant()], 403);
        }

        return $next($request);
    }
}
