<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

/**
 * Enforcement buat trial_ends_at yang tadinya cuma informational (lihat
 * AGENTS.md "Di luar cakupan"). Tenant status=trial yang trial_ends_at-nya
 * lewat -> otomatis disuspend (owner masih bisa diapprove manual jadi
 * 'active' lagi dari antrean platform admin kalau ternyata sudah bayar).
 */
class SuspendExpiredTrials extends Command
{
    protected $signature = 'tenants:suspend-expired-trials';

    protected $description = 'Suspend tenant status=trial yang trial_ends_at-nya sudah lewat.';

    public function handle(): int
    {
        $tenants = Tenant::where('status', 'trial')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<', now())
            ->get();

        foreach ($tenants as $tenant) {
            $tenant->update([
                'status' => 'suspended',
                'suspended_at' => now(),
                'suspended_reason' => 'Trial berakhir otomatis.',
            ]);
            $this->line("Suspended: {$tenant->name} ({$tenant->subdomain})");
        }

        $this->info("Selesai - {$tenants->count()} tenant disuspend.");

        return self::SUCCESS;
    }
}
