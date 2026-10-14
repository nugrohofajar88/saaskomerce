<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTenants;
use Tests\TestCase;

class SuspendExpiredTrialsTest extends TestCase
{
    use CreatesTenants, RefreshDatabase;

    public function test_expired_trial_gets_suspended(): void
    {
        $expired = $this->createTenant(['status' => 'trial', 'trial_ends_at' => now()->subDay()]);
        $stillValid = $this->createTenant(['status' => 'trial', 'trial_ends_at' => now()->addDays(3)]);
        $activeNoTrial = $this->createTenant(['status' => 'active', 'trial_ends_at' => null]);

        $this->artisan('tenants:suspend-expired-trials')->assertSuccessful();

        $this->assertSame('suspended', $expired->fresh()->status);
        $this->assertSame('trial', $stillValid->fresh()->status);
        $this->assertSame('active', $activeNoTrial->fresh()->status);
    }
}
