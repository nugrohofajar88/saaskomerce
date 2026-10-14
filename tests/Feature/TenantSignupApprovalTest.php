<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\CreatesTenants;
use Tests\TestCase;

class TenantSignupApprovalTest extends TestCase
{
    use CreatesTenants, RefreshDatabase;

    public function test_signup_creates_pending_tenant_that_cannot_login_until_approved(): void
    {
        $response = $this->post('http://'.config('app.base_domain').'/daftar', [
            'name' => 'Toko Baru',
            'subdomain' => 'tokobaru',
            'owner_name' => 'Owner Baru',
            'owner_email' => 'owner@tokobaru.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect();

        $tenant = Tenant::where('subdomain', 'tokobaru')->first();
        $this->assertNotNull($tenant);
        $this->assertSame('pending', $tenant->status);

        // Belum di-approve -> login ditolak dgn halaman info, bukan 404
        $loginPage = $this->get($this->tenantUrl($tenant, '/admin/login'));
        $loginPage->assertStatus(403);
        $loginPage->assertSee('masih ditinjau');
    }

    public function test_reserved_subdomain_is_rejected(): void
    {
        $response = $this->post('http://'.config('app.base_domain').'/daftar', [
            'name' => 'Toko Jahat',
            'subdomain' => 'app',
            'owner_name' => 'X',
            'owner_email' => 'x@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('subdomain');
        $this->assertSame(0, Tenant::where('subdomain', 'app')->count());
    }

    public function test_platform_admin_can_approve_pending_tenant_then_tenant_admin_can_login(): void
    {
        $platformAdmin = User::create([
            'tenant_id' => null,
            'role' => 'platform_admin',
            'name' => 'Platform',
            'email' => 'platform@test.com',
            'password' => Hash::make('password'),
        ]);

        $tenant = $this->createTenant(['status' => 'pending', 'approved_at' => null]);
        $admin = $this->createTenantAdmin($tenant, ['email' => 'admin@test.com']);

        $this->actingAs($platformAdmin, 'platform');
        $this->post('http://app.'.config('app.base_domain').'/tenants/'.$tenant->id.'/approve', [
            'status' => 'active',
        ])->assertRedirect();

        $this->assertSame('active', $tenant->fresh()->status);

        // Sekarang tenant admin beneran bisa login
        $this->post($this->tenantUrl($tenant, '/admin/login'), [
            'email' => 'admin@test.com',
            'password' => 'password',
        ])->assertRedirect($this->tenantUrl($tenant, '/admin'));

        $this->assertAuthenticatedAs($admin, 'tenant_admin');
    }
}
