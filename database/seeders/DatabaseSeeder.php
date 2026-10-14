<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\PaymentAccount;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seed data dev-lokal: 1 platform admin + 2 tenant contoh (langsung status
 * 'active', skip alur signup->approval) biar gampang tes multi-subdomain tanpa
 * approve manual tiap reset DB. tenant_id di-isi EKSPLISIT di tiap create()
 * (bukan andalkan auto-fill BelongsToTenant, yang butuh TenantContext ter-bind
 * lewat request HTTP - gak ada di konteks seeder/CLI).
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'tenant_id' => null,
            'role' => 'platform_admin',
            'name' => 'Admin Platform',
            'email' => 'admin@saaskomerce.test',
            'password' => Hash::make('password'),
        ]);

        $this->seedTenant('Toko Kopi Senja', 'kopisenja', [
            ['name' => 'Kopi', 'products' => [
                ['name' => 'Kopi Susu Gula Aren', 'variants' => [
                    ['sku' => 'KSGA-REG', 'label' => 'Regular', 'price' => 18000, 'stock' => 50],
                    ['sku' => 'KSGA-LRG', 'label' => 'Large', 'price' => 22000, 'stock' => 30],
                ]],
                ['name' => 'Americano', 'variants' => [
                    ['sku' => 'AMC-REG', 'label' => 'Regular', 'price' => 15000, 'stock' => 40],
                ]],
            ]],
        ]);

        $this->seedTenant('Toko Baju Mila', 'bajumila', [
            ['name' => 'Kaos', 'products' => [
                ['name' => 'Kaos Polos Katun', 'variants' => [
                    ['sku' => 'KPK-M', 'label' => 'M', 'price' => 65000, 'stock' => 20],
                    ['sku' => 'KPK-L', 'label' => 'L', 'price' => 65000, 'stock' => 15],
                ]],
            ]],
        ]);
    }

    private function seedTenant(string $name, string $subdomain, array $categories): void
    {
        $tenant = Tenant::create([
            'name' => $name,
            'subdomain' => $subdomain,
            'status' => 'active',
            'plan' => 'free',
            'owner_name' => $name.' Owner',
            'owner_email' => $subdomain.'@example.test',
            'approved_at' => now(),
        ]);

        User::create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin',
            'name' => $name.' Owner',
            'email' => 'admin@'.$subdomain.'.test',
            'password' => Hash::make('password'),
        ]);

        PaymentAccount::create([
            'tenant_id' => $tenant->id,
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => $name,
            'is_active' => true,
        ]);

        foreach ($categories as $i => $categoryData) {
            $category = Category::create([
                'tenant_id' => $tenant->id,
                'name' => $categoryData['name'],
                'slug' => \Illuminate\Support\Str::slug($categoryData['name']),
                'sort_order' => $i,
            ]);

            foreach ($categoryData['products'] as $j => $productData) {
                $product = Product::create([
                    'tenant_id' => $tenant->id,
                    'category_id' => $category->id,
                    'name' => $productData['name'],
                    'slug' => \Illuminate\Support\Str::slug($productData['name']),
                    'is_active' => true,
                    'sort_order' => $j,
                ]);

                foreach ($productData['variants'] as $k => $variantData) {
                    ProductVariant::create([
                        'tenant_id' => $tenant->id,
                        'product_id' => $product->id,
                        'sku' => $variantData['sku'],
                        'label' => $variantData['label'],
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                        'is_default' => $k === 0,
                        'is_active' => true,
                        'sort_order' => $k,
                    ]);
                }
            }
        }
    }
}
