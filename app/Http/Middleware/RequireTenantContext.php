<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Dipasang di rute storefront/tenant-admin - 404 kalau subdomain gak match tenant manapun. */
class RequireTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->bound('tenant')) {
            abort(404, 'Toko tidak ditemukan.');
        }

        return $next($request);
    }
}
