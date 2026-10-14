<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesTenants;
use Tests\TestCase;

/** Alur penuh storefront: daftar -> keranjang -> checkout -> upload bukti -> admin tandai lunas. */
class CheckoutFlowTest extends TestCase
{
    use CreatesTenants, RefreshDatabase;

    public function test_full_checkout_reserves_stock_and_admin_can_mark_paid(): void
    {
        Storage::fake('public');

        $tenant = $this->createTenant();
        $variant = $this->createProductWithVariant($tenant, [], ['price' => 15000, 'stock' => 10]);
        $this->createPaymentAccount($tenant);
        $admin = $this->createTenantAdmin($tenant, ['email' => 'admin@test.com']);

        // Daftar sbg customer
        $this->post($this->tenantUrl($tenant, '/daftar'), [
            'name' => 'Budi',
            'email' => 'budi@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect();

        $this->assertAuthenticated('customer');

        // Tambah ke keranjang
        $this->post($this->tenantUrl($tenant, '/keranjang'), [
            'variant_id' => $variant->id,
            'qty' => 2,
        ])->assertRedirect();

        // Checkout
        $this->post($this->tenantUrl($tenant, '/checkout'), [
            'name' => 'Budi',
            'phone' => '08123456789',
            'address' => 'Jl. Test No. 1',
        ])->assertRedirect();

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertSame('pending_payment', $order->status);
        $this->assertSame(30000, $order->total);
        $this->assertSame(8, $variant->fresh()->stock, 'stok harus berkurang 2 (ke-reserve) begitu order dibuat');

        // Upload bukti bayar
        $this->post($this->tenantUrl($tenant, '/pesanan/'.$order->order_number.'/bukti-bayar'), [
            'proof' => UploadedFile::fake()->image('bukti.jpg'),
        ])->assertRedirect();

        $this->assertNotNull($order->fresh()->payment_proof_path);

        // Admin tandai lunas
        $this->actingAs($admin, 'tenant_admin');
        $this->post($this->tenantUrl($tenant, '/admin/orders/'.$order->id.'/mark-paid'))->assertRedirect();

        $this->assertSame('paid', $order->fresh()->status);
        $this->assertSame(8, $variant->fresh()->stock, 'stok TETAP terkurangi (bukan direlease) begitu order lunas');
    }

    public function test_checkout_without_login_redirects_to_login(): void
    {
        $tenant = $this->createTenant();

        $response = $this->get($this->tenantUrl($tenant, '/checkout'));

        $response->assertRedirect($this->tenantUrl($tenant, '/login'));
    }

    public function test_checkout_blocked_when_stock_sold_out_after_adding_to_cart(): void
    {
        $tenant = $this->createTenant();
        $variant = $this->createProductWithVariant($tenant, [], ['stock' => 1]);
        $customer = $this->createCustomer($tenant);

        $this->actingAs($customer, 'customer');
        app()->instance('tenant', $tenant);

        $this->post($this->tenantUrl($tenant, '/keranjang'), ['variant_id' => $variant->id, 'qty' => 1]);

        // Stok dikurangi manual di belakang layar (simulasi race/order lain) biar checkout ini over-order
        $variant->update(['stock' => 0]);

        $response = $this->post($this->tenantUrl($tenant, '/checkout'), [
            'name' => 'Budi', 'phone' => '08123456789',
        ]);

        $response->assertRedirect($this->tenantUrl($tenant, '/keranjang'));
        $this->assertSame(0, Order::count());
    }
}
