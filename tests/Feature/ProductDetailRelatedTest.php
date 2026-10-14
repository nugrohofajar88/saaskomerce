<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTenants;
use Tests\TestCase;

class ProductDetailRelatedTest extends TestCase
{
    use CreatesTenants, RefreshDatabase;

    public function test_related_products_from_same_category_are_shown(): void
    {
        $tenant = $this->createTenant();
        $category = $this->createCategory($tenant);

        app()->instance('tenant', $tenant); // cuma dibutuhkan utk baca ->product di bawah, bukan buat request-nya
        $mainProduct = $this->createProductWithVariant($tenant, ['name' => 'Produk Utama', 'category_id' => $category->id])->product;
        $this->createProductWithVariant($tenant, ['name' => 'Produk Terkait', 'category_id' => $category->id]);
        $this->createProductWithVariant($tenant, ['name' => 'Produk Kategori Lain']);

        $response = $this->get($this->tenantUrl($tenant, '/produk/'.$mainProduct->slug));

        $response->assertOk();
        $response->assertSee('Produk Terkait');
        $response->assertDontSee('Produk Kategori Lain');
    }
}
