<?php

namespace App\Models\Concerns;

use App\Models\Tenant;
use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        // Auto-isi tenant_id saat create kalau belum di-set manual - controller
        // gak perlu inget nulis tenant_id di tiap create(), sama semangatnya kayak
        // controller tipis di larashop-be.
        static::creating(function ($model) {
            if (is_null($model->tenant_id)) {
                $model->tenant_id = TenantContext::require()->id;
            }
        });
    }

    /** Escape hatch buat query lintas-tenant yang legit (cron, dashboard platform). */
    public static function withoutTenantScope(): Builder
    {
        return static::withoutGlobalScope(TenantScope::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
