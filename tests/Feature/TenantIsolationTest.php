<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTenants;
use Tests\TestCase;

/**
 * Inti dari seluruh arsitektur multi-tenant: query model domain WAJIB
 * ter-scope ke tenant yang sedang bind, dan WAJIB throw kalau dipanggil
 * tanpa tenant context (bukan diam-diam nampilin semua/nol baris).
 */
class TenantIsolationTest extends TestCase
{
    use CreatesTenants, RefreshDatabase;

    public function test_product_query_only_returns_current_tenants_products(): void
    {
        $tenantA = $this->createTenant();
        $tenantB = $this->createTenant();

        $this->createProductWithVariant($tenantA, ['name' => 'Produk A']);
        $this->createProductWithVariant($tenantB, ['name' => 'Produk B']);

        app()->instance('tenant', $tenantA);
        $this->assertEquals(['Produk A'], Product::pluck('name')->all());

        app()->instance('tenant', $tenantB);
        $this->assertEquals(['Produk B'], Product::pluck('name')->all());
    }

    public function test_querying_without_tenant_context_throws(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('dipanggil tanpa tenant ter-bind');

        Product::all();
    }

    public function test_without_tenant_scope_escape_hatch_sees_all_tenants(): void
    {
        $tenantA = $this->createTenant();
        $tenantB = $this->createTenant();

        $this->createProductWithVariant($tenantA, ['name' => 'Produk A']);
        $this->createProductWithVariant($tenantB, ['name' => 'Produk B']);

        $names = Product::withoutTenantScope()->pluck('name')->sort()->values()->all();

        $this->assertEquals(['Produk A', 'Produk B'], $names);
    }
}
