<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTenants;
use Tests\TestCase;

/**
 * SESSION_DOMAIN wildcard (.{base_domain}) bikin cookie nyambung lintas-subdomain
 * demi kenyamanan dev - tapi itu artinya guard session tenant_admin "ke-reuse"
 * kalau admin pindah ke subdomain tenant LAIN. EnsureTenantMatchesAuth yang
 * nutup celah ini (pernah bocor nyata sebelum fix: logout tanpa redirect
 * masih ngelanjutin request yang sedang jalan).
 */
class CrossTenantAuthGuardTest extends TestCase
{
    use CreatesTenants, RefreshDatabase;

    public function test_tenant_admin_session_cannot_access_another_tenants_dashboard(): void
    {
        $tenantA = $this->createTenant(['subdomain' => 'tokoa']);
        $tenantB = $this->createTenant(['subdomain' => 'tokob']);
        $adminA = $this->createTenantAdmin($tenantA);

        $this->actingAs($adminA, 'tenant_admin');

        $response = $this->get($this->tenantUrl($tenantB, '/admin'));

        $response->assertRedirect($this->tenantUrl($tenantB, '/admin/login'));
    }

    public function test_tenant_admin_can_access_own_dashboard(): void
    {
        $tenant = $this->createTenant();
        $admin = $this->createTenantAdmin($tenant);

        $this->actingAs($admin, 'tenant_admin');

        $response = $this->get($this->tenantUrl($tenant, '/admin'));

        $response->assertOk();
        $response->assertSee('Dashboard');
    }

    public function test_unknown_subdomain_returns_404(): void
    {
        $response = $this->get('http://doesnotexist.'.config('app.base_domain').'/');

        $response->assertNotFound();
    }

    public function test_pending_tenant_storefront_shows_not_approved_page_instead_of_404(): void
    {
        $tenant = $this->createTenant(['status' => 'pending', 'approved_at' => null]);

        $response = $this->get($this->tenantUrl($tenant, '/'));

        $response->assertStatus(403);
        $response->assertSee('masih ditinjau');
    }
}
