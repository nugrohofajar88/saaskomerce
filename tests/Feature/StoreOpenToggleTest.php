<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Support\SettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTenants;
use Tests\TestCase;

class StoreOpenToggleTest extends TestCase
{
    use CreatesTenants, RefreshDatabase;

    public function test_cannot_add_to_cart_when_store_closed(): void
    {
        $tenant = $this->createTenant();
        $variant = $this->createProductWithVariant($tenant);
        $customer = $this->createCustomer($tenant);

        app()->instance('tenant', $tenant);
        (new SettingService($tenant->id))->set('store_open', '0');

        $this->actingAs($customer, 'customer');

        $response = $this->post($this->tenantUrl($tenant, '/keranjang'), [
            'variant_id' => $variant->id,
            'qty' => 1,
        ]);

        $response->assertSessionHas('error');
    }

    public function test_cannot_checkout_when_store_closed(): void
    {
        $tenant = $this->createTenant();
        $customer = $this->createCustomer($tenant);

        app()->instance('tenant', $tenant);
        (new SettingService($tenant->id))->set('store_open', '0');

        $this->actingAs($customer, 'customer');

        $response = $this->get($this->tenantUrl($tenant, '/checkout'));

        $response->assertRedirect($this->tenantUrl($tenant, '/keranjang'));
        $this->assertSame(0, Order::count());
    }

    public function test_storefront_still_browsable_when_store_closed(): void
    {
        $tenant = $this->createTenant();
        $this->createProductWithVariant($tenant, ['name' => 'Produk Tetap Kelihatan']);

        app()->instance('tenant', $tenant);
        (new SettingService($tenant->id))->set('store_open', '0');

        $response = $this->get($this->tenantUrl($tenant, '/'));

        $response->assertOk();
        $response->assertSee('Produk Tetap Kelihatan');
        $response->assertSee('Toko sedang tutup sementara');
    }
}
