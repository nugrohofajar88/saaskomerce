<?php

use App\Http\Middleware\EnsurePlatformAdmin;
use App\Http\Middleware\EnsureTenantMatchesAuth;
use App\Http\Middleware\RequireApprovedTenant;
use App\Http\Middleware\RequireNoTenantContext;
use App\Http\Middleware\RequireTenantContext;
use App\Http\Middleware\ResolveTenant;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // File route terpisah per konteks (platform/tenant admin/storefront).
            // platform.php pakai domain LITERAL (app.<base>); tenant_admin.php &
            // storefront.php TANPA domain constraint sama sekali - lihat catatan
            // panjang di kedua file itu kenapa ({subdomain}.<base> dgn placeholder
            // bikin argumen controller kegeser posisi).
            Route::middleware('web')->group(base_path('routes/platform.php'));
            Route::middleware('web')->group(base_path('routes/tenant_admin.php'));
            Route::middleware('web')->group(base_path('routes/storefront.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ResolveTenant WAJIB prepend (bukan append) - SubstituteBindings (route
        // model binding, mis. {order:order_number}) ada di akhir grup 'web' bawaan
        // Laravel, dan binding itu butuh tenant context SUDAH ter-bind duluan
        // (soalnya Order/Product pakai global scope tenant_id). No-op kalau host
        // gak match subdomain manapun (lihat isi middleware-nya).
        $middleware->web(prepend: [ResolveTenant::class]);

        $middleware->alias([
            'tenant.require' => RequireTenantContext::class,
            'tenant.none' => RequireNoTenantContext::class,
            'tenant.approved' => RequireApprovedTenant::class,
            'tenant.match-auth' => EnsureTenantMatchesAuth::class,
            'platform.admin' => EnsurePlatformAdmin::class,
        ]);

        // Laravel default-nya redirect guest ke route literal 'login' - kita gak
        // punya route bernama itu (ada 3: platform.login/tenant.login/
        // storefront.login, beda per konteks). Tanpa ini, auth:platform|
        // tenant_admin|customer yang nemu guest bakal lempar RouteNotFoundException
        // alih-alih redirect ke halaman login yang bener.
        $middleware->redirectGuestsTo(function (Request $request) {
            $host = $request->getHost();
            $baseDomain = config('app.base_domain');

            return match (true) {
                $host === 'app.'.$baseDomain => route('platform.login'),
                str_starts_with($request->path(), 'admin') => route('tenant.login'),
                default => route('storefront.login'),
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
