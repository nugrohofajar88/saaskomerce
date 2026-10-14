<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    // status: pending | trial | active | suspended
    protected $fillable = [
        'name',
        'subdomain',
        'custom_domain',
        'status',
        'plan',
        'owner_name',
        'owner_email',
        'owner_phone',
        'trial_ends_at',
        'approved_at',
        'approved_by',
        'suspended_at',
        'suspended_reason',
    ];

    protected function casts(): array
    {
        return [
            'trial_ends_at' => 'datetime',
            'approved_at' => 'datetime',
            'suspended_at' => 'datetime',
        ];
    }

    public function isApproved(): bool
    {
        return in_array($this->status, ['trial', 'active'], true);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
