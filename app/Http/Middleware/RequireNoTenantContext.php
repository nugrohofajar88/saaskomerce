<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Guard rute platform/marketing - 404 kalau entah kenapa ResolveTenant sempat bind tenant. */
class RequireNoTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->bound('tenant')) {
            abort(404);
        }

        return $next($request);
    }
}
