<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * SENGAJA TIDAK pakai trait BelongsToTenant/TenantScope di sini (beda dari model
 * domain lain). User diakses dari 3 konteks: platform (tenant_id=null, TANPA
 * tenant context sama sekali), tenant_admin, dan customer - scope yang
 * auto-throw-tanpa-tenant bakal matiin login platform_admin. Filter tenant_id
 * dilakukan EKSPLISIT di controller (login tenant_admin/customer) saat memang perlu.
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // role: platform_admin (tenant_id=null) | tenant_admin (1 per tenant) | customer
    protected $fillable = [
        'tenant_id',
        'role',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isPlatformAdmin(): bool
    {
        return $this->role === 'platform_admin';
    }

    public function isTenantAdmin(): bool
    {
        return $this->role === 'tenant_admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function approvedTenants(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'id', 'approved_by');
    }
}
