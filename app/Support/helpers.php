<?php

use App\Models\Tenant;
use App\Support\TenantContext;

if (! function_exists('tenant')) {
    /** Tenant yang lagi aktif di request ini (null kalau di luar konteks tenant, mis. rute platform). */
    function tenant(): ?Tenant
    {
        return TenantContext::current();
    }
}

if (! function_exists('tenant_id')) {
    function tenant_id(): ?int
    {
        return TenantContext::id();
    }
}
