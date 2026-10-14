<?php

namespace App\Models\Concerns;

use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Auto-filter tenant_id di SEMUA query model yang pakai trait BelongsToTenant.
 * Gagal-keras (throw) kalau dipanggil tanpa tenant context ter-bind, BUKAN diam-diam
 * nampilin semua baris atau nol baris - biar bug "controller jalan tanpa tenant
 * context" ketahuan cepat saat dev, bukan jadi kebocoran data senyap di produksi.
 *
 * Query lintas-tenant yang LEGIT (command cron, dashboard platform admin) pakai
 * escape hatch Model::withoutTenantScope() (lihat trait BelongsToTenant).
 */
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where($model->getTable().'.tenant_id', TenantContext::require()->id);
    }
}
