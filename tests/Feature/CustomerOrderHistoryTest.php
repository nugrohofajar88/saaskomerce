<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTenants;
use Tests\TestCase;

class CustomerOrderHistoryTest extends TestCase
{
    use CreatesTenants, RefreshDatabase;

    public function test_customer_sees_only_their_own_orders(): void
    {
        $tenant = $this->createTenant();
        $customerA = $this->createCustomer($tenant, ['email' => 'a@test.com']);
        $customerB = $this->createCustomer($tenant, ['email' => 'b@test.com']);

        Order::create([
            'tenant_id' => $tenant->id, 'user_id' => $customerA->id, 'order_number' => 'ORD-A',
            'status' => 'pending_payment', 'subtotal' => 1000, 'total' => 1000, 'customer_name' => 'A',
        ]);
        Order::create([
            'tenant_id' => $tenant->id, 'user_id' => $customerB->id, 'order_number' => 'ORD-B',
            'status' => 'pending_payment', 'subtotal' => 1000, 'total' => 1000, 'customer_name' => 'B',
        ]);

        $this->actingAs($customerA, 'customer');

        $response = $this->get($this->tenantUrl($tenant, '/pesanan'));

        $response->assertOk();
        $response->assertSee('ORD-A');
        $response->assertDontSee('ORD-B');
    }

    public function test_guest_redirected_to_login(): void
    {
        $tenant = $this->createTenant();

        $response = $this->get($this->tenantUrl($tenant, '/pesanan'));

        $response->assertRedirect($this->tenantUrl($tenant, '/login'));
    }
}
