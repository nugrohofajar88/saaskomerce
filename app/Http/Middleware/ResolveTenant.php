<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Baca host request, cocokkan ke tenants.subdomain, bind ke container sbg 'tenant'
 * (diakses lewat helper tenant()/TenantContext). Apex/www/app -> no-op (gak ada
 * tenant yg di-bind), rute platform/marketing yang jalan di situ.
 *
 * TIDAK melakukan apa pun kalau 'tenant' sudah ter-bind sebelumnya (mis. dipanggil
 * lagi di request yg sama) - idempotent.
 */
class ResolveTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $baseDomain = config('app.base_domain');
        $host = $request->getHost();

        if (! $baseDomain || ! str_ends_with($host, '.'.$baseDomain)) {
            return $next($request); // host gak match pola subdomain kita sama sekali
        }

        $label = substr($host, 0, -(strlen($baseDomain) + 1));

        if ($label === '' || $label === 'www' || $label === 'app') {
            return $next($request); // apex/www = marketing, app = platform - no tenant
        }

        // Host header dari HTTP client SELALU lowercase (Symfony getHost() udah
        // normalize), tapi tenants.subdomain bisa aja mixed-case kalau dibuat di
        // luar jalur signup tervalidasi (mis. tinker/seeder ceroboh) - MySQL
        // default-nya case-insensitive collation tapi jangan gantungin ke situ,
        // SQLite (dipakai di test) case-SENSITIVE. Lowercase eksplisit di sini.
        $tenant = Tenant::whereRaw('LOWER(subdomain) = ?', [strtolower($label)])->first();

        if ($tenant) {
            app()->instance('tenant', $tenant);
        }

        return $next($request);
    }
}
