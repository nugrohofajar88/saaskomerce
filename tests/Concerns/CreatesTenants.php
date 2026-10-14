<?php

namespace Tests\Concerns;

use App\Models\Category;
use App\Models\PaymentAccount;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait CreatesTenants
{
    protected function createTenant(array $attrs = []): Tenant
    {
        return Tenant::create(array_merge([
            'name' => 'Toko Test',
            'subdomain' => 'toko'.Str::lower(Str::random(6)),
            'status' => 'active',
            'plan' => 'free',
            'owner_name' => 'Owner Test',
            'owner_email' => 'owner'.Str::random(6).'@test.com',
            'approved_at' => now(),
        ], $attrs));
    }

    protected function createTenantAdmin(Tenant $tenant, array $attrs = []): User
    {
        return User::create(array_merge([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
            'name' => 'Admin',
            'email' => 'admin-'.Str::random(6).'@test.com',
            'password' => Hash::make('password'),
        ], $attrs));
    }

    protected function createCustomer(Tenant $tenant, array $attrs = []): User
    {
        return User::create(array_merge([
            'tenant_id' => $tenant->id,
            'role' => 'customer',
            'name' => 'Customer',
            'email' => 'customer-'.Str::random(6).'@test.com',
            'password' => Hash::make('password'),
        ], $attrs));
    }

    /** Bikin produk + 1 varian, langsung set tenant_id eksplisit (gak butuh tenant context ter-bind). */
    protected function createProductWithVariant(Tenant $tenant, array $productAttrs = [], array $variantAttrs = []): ProductVariant
    {
        $product = Product::create(array_merge([
            'tenant_id' => $tenant->id,
            'name' => 'Produk Test',
            'slug' => 'produk-test-'.Str::random(6),
            'is_active' => true,
        ], $productAttrs));

        return ProductVariant::create(array_merge([
            'tenant_id' => $tenant->id,
            'product_id' => $product->id,
            'sku' => 'SKU-'.Str::random(8),
            'label' => 'Default',
            'price' => 10000,
            'stock' => 10,
            'is_default' => true,
            'is_active' => true,
        ], $variantAttrs));
    }

    protected function createPaymentAccount(Tenant $tenant): PaymentAccount
    {
        return PaymentAccount::create([
            'tenant_id' => $tenant->id,
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'Toko Test',
            'is_active' => true,
        ]);
    }

    protected function createCategory(Tenant $tenant): Category
    {
        return Category::create([
            'tenant_id' => $tenant->id,
            'name' => 'Kategori Test',
            'slug' => 'kategori-test-'.Str::random(6),
        ]);
    }

    protected function tenantUrl(Tenant $tenant, string $path = '/'): string
    {
        return 'http://'.$tenant->subdomain.'.'.config('app.base_domain').$path;
    }
}
