<?php

namespace App\Support;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TenantProvisioningService
{
    /** Bikin tenant status=pending + 1 user tenant_admin, TAPI belum bisa login (lihat RequireApprovedTenant). */
    public function createPending(array $data): Tenant
    {
        return DB::transaction(function () use ($data) {
            $tenant = Tenant::create([
                'name' => $data['name'],
                'subdomain' => $data['subdomain'],
                'status' => 'pending',
                'plan' => 'free',
                'owner_name' => $data['owner_name'],
                'owner_email' => $data['owner_email'],
                'owner_phone' => $data['owner_phone'] ?? null,
            ]);

            User::create([
                'tenant_id' => $tenant->id,
                'role' => 'tenant_admin',
                'name' => $data['owner_name'],
                'email' => $data['owner_email'],
                'password' => Hash::make($data['password']),
            ]);

            return $tenant;
        });
    }

    public function approve(Tenant $tenant, User $approver, string $status = 'trial'): void
    {
        $tenant->update([
            'status' => $status,
            'approved_at' => now(),
            'approved_by' => $approver->id,
            'trial_ends_at' => $status === 'trial' ? now()->addDays(14) : null,
            'suspended_at' => null,
            'suspended_reason' => null,
        ]);
    }

    public function suspend(Tenant $tenant, ?string $reason = null): void
    {
        $tenant->update([
            'status' => 'suspended',
            'suspended_at' => now(),
            'suspended_reason' => $reason,
        ]);
    }
}
