<?php

namespace Tests\Feature;

use App\Support\SettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTenants;
use Tests\TestCase;

class TenantAboutPageTest extends TestCase
{
    use CreatesTenants, RefreshDatabase;

    public function test_tenant_admin_can_save_about_content(): void
    {
        $tenant = $this->createTenant();
        $admin = $this->createTenantAdmin($tenant);

        $this->actingAs($admin, 'tenant_admin');

        $response = $this->post($this->tenantUrl($tenant, '/admin/about'), [
            'about_content' => 'Toko kami berdiri sejak 2021.',
            'why_us_content' => 'Kualitas terjamin & harga bersahabat.',
        ]);

        $response->assertRedirect();

        app()->instance('tenant', $tenant);
        $settings = new SettingService($tenant->id);
        $this->assertSame('Toko kami berdiri sejak 2021.', $settings->aboutContent());
        $this->assertSame('Kualitas terjamin & harga bersahabat.', $settings->whyUsContent());
    }

    public function test_storefront_about_page_shows_content_when_set(): void
    {
        $tenant = $this->createTenant();
        app()->instance('tenant', $tenant);
        (new SettingService($tenant->id))->set('about_content', 'Cerita toko kami yang unik.');

        $response = $this->get($this->tenantUrl($tenant, '/tentang'));

        $response->assertOk();
        $response->assertSee('Cerita toko kami yang unik.');
    }

    public function test_storefront_about_page_404_when_nothing_configured(): void
    {
        $tenant = $this->createTenant();

        $response = $this->get($this->tenantUrl($tenant, '/tentang'));

        $response->assertNotFound();
    }

    public function test_nav_link_hidden_when_no_about_content(): void
    {
        $tenant = $this->createTenant();

        $response = $this->get($this->tenantUrl($tenant, '/'));

        $response->assertOk();
        $response->assertDontSee(route('storefront.about', [], false));
    }
}
