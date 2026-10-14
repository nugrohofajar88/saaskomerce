<?php

namespace App\Support;

use App\Models\Tenant;

/**
 * Pembungkus tipis di atas container binding 'tenant' (di-bind oleh middleware
 * ResolveTenant). Dipanggil lewat helper tenant()/tenant_id() di
 * bootstrap/helpers.php - mirip pola auth()/request() bawaan Laravel.
 */
class TenantContext
{
    public static function current(): ?Tenant
    {
        return app()->bound('tenant') ? app('tenant') : null;
    }

    public static function id(): ?int
    {
        return static::current()?->id;
    }

    public static function require(): Tenant
    {
        $tenant = static::current();

        if (! $tenant) {
            throw new \RuntimeException(
                'TenantContext::require() dipanggil tanpa tenant ter-bind. '
                .'Pastikan route ini lewat middleware ResolveTenant, atau pakai '
                .'withoutTenantScope() kalau ini memang query lintas-tenant yang disengaja.'
            );
        }

        return $tenant;
    }
}
