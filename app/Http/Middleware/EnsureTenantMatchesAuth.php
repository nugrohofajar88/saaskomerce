<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * SESSION_DOMAIN dibuat wildcard (.saaskomerce.test) biar cookie nyambung
 * lintas-subdomain (kenyamanan dev/klik-tembus), tapi itu artinya seorang
 * tenant_admin yang login di subdomain tenant A, lalu pindah ke subdomain
 * tenant B, TETAP kebaca "authenticated" oleh guard tenant_admin (session key
 * sama) - padahal dia bukan admin tenant B. Middleware ini yang nutup celah itu:
 * logout paksa dari guard terkait kalau user_id yang login tenant_id-nya beda
 * dari tenant yang lagi di-resolve ResolveTenant.
 */
class EnsureTenantMatchesAuth
{
    public function handle(Request $request, Closure $next, string $guard): Response
    {
        $user = Auth::guard($guard)->user();

        if ($user && tenant() && $user->tenant_id !== tenant()->id) {
            Auth::guard($guard)->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // WAJIB redirect di sini, BUKAN cuma logout lalu lanjut ke $next() -
            // logout cuma ngubah state session utk request BERIKUTNYA; kalau
            // request ini dibiarkan lanjut, controller tetap jalan dgn asumsi
            // user masih authenticated (middleware 'auth:' sudah lolos SEBELUM
            // middleware ini dicek) dan nampilin data tenant B ke admin tenant A.
            $loginRoute = $guard === 'customer' ? 'storefront.login' : 'tenant.login';

            return redirect()->route($loginRoute);
        }

        return $next($request);
    }
}
