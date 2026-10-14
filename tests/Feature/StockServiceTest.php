<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Support\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\Concerns\CreatesTenants;
use Tests\TestCase;

class StockServiceTest extends TestCase
{
    use CreatesTenants, RefreshDatabase;

    public function test_reserve_decrements_stock_atomically(): void
    {
        $tenant = $this->createTenant();
        app()->instance('tenant', $tenant); // cuma dibutuhkan utk assertion $order->items() di bawah, bukan StockService-nya

        $variant = $this->createProductWithVariant($tenant, [], ['stock' => 5]);

        $order = Order::create([
            'tenant_id' => $tenant->id,
            'order_number' => 'TEST-1',
            'status' => 'pending_payment',
            'subtotal' => 20000,
            'total' => 20000,
            'customer_name' => 'Test',
        ]);
        $order->items()->create([
            'tenant_id' => $tenant->id,
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id,
            'product_name' => 'x', 'product_sku' => $variant->sku, 'variant_label' => 'x',
            'price' => 10000, 'qty' => 3, 'subtotal' => 30000,
        ]);

        (new StockService)->reserveForOrder($order);

        $this->assertSame(2, $variant->fresh()->stock);
        $this->assertSame(3, $order->items()->first()->reserved_qty);
    }

    public function test_reserve_throws_when_stock_insufficient(): void
    {
        $tenant = $this->createTenant();
        $variant = $this->createProductWithVariant($tenant, [], ['stock' => 1]);

        $order = Order::create([
            'tenant_id' => $tenant->id, 'order_number' => 'TEST-2', 'status' => 'pending_payment',
            'subtotal' => 20000, 'total' => 20000, 'customer_name' => 'Test',
        ]);
        $order->items()->create([
            'tenant_id' => $tenant->id, 'product_id' => $variant->product_id, 'product_variant_id' => $variant->id,
            'product_name' => 'x', 'product_sku' => $variant->sku, 'variant_label' => 'x',
            'price' => 10000, 'qty' => 5, 'subtotal' => 50000,
        ]);

        $this->expectException(ValidationException::class);

        (new StockService)->reserveForOrder($order);
    }

    public function test_release_is_idempotent(): void
    {
        $tenant = $this->createTenant();
        $variant = $this->createProductWithVariant($tenant, [], ['stock' => 5]);

        $order = Order::create([
            'tenant_id' => $tenant->id, 'order_number' => 'TEST-3', 'status' => 'pending_payment',
            'subtotal' => 20000, 'total' => 20000, 'customer_name' => 'Test',
        ]);
        $order->items()->create([
            'tenant_id' => $tenant->id, 'product_id' => $variant->product_id, 'product_variant_id' => $variant->id,
            'product_name' => 'x', 'product_sku' => $variant->sku, 'variant_label' => 'x',
            'price' => 10000, 'qty' => 2, 'subtotal' => 20000,
        ]);

        $service = new StockService;
        $service->reserveForOrder($order);
        $this->assertSame(3, $variant->fresh()->stock);

        $service->releaseForOrder($order);
        $this->assertSame(5, $variant->fresh()->stock);

        // Panggil lagi - gak boleh nambah stok KEDUA kalinya (reserved_qty sudah 0)
        $service->releaseForOrder($order);
        $this->assertSame(5, $variant->fresh()->stock);
    }

    public function test_release_works_without_ambient_tenant_context(): void
    {
        $tenant = $this->createTenant();
        $variant = $this->createProductWithVariant($tenant, [], ['stock' => 5]);

        $order = Order::create([
            'tenant_id' => $tenant->id, 'order_number' => 'TEST-4', 'status' => 'pending_payment',
            'subtotal' => 10000, 'total' => 10000, 'customer_name' => 'Test',
        ]);
        $order->items()->create([
            'tenant_id' => $tenant->id, 'product_id' => $variant->product_id, 'product_variant_id' => $variant->id,
            'product_name' => 'x', 'product_sku' => $variant->sku, 'variant_label' => 'x',
            'price' => 10000, 'qty' => 2, 'subtotal' => 20000,
        ]);

        $service = new StockService;
        $service->reserveForOrder($order);

        // Simulasikan command cron lintas-tenant: TIDAK ada tenant context ter-bind.
        // StockService harus tetap jalan (ini intinya kenapa dia query OrderItem
        // via withoutTenantScope(), bukan lewat relasi $order->items).
        app()->forgetInstance('tenant');

        $service->releaseForOrder($order);

        $this->assertSame(5, $variant->fresh()->stock);
    }
}
