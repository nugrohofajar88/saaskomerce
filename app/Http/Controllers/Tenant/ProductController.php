<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('tenant.products.index', [
            'products' => Product::with(['category', 'variants'])->orderByDesc('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('tenant.products.create', [
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $product = DB::transaction(function () use ($validated, $request) {
            $product = Product::create([
                'category_id' => $validated['category_id'] ?? null,
                'name' => $validated['name'],
                'slug' => $this->uniqueSlug($validated['name']),
                'description' => $validated['description'] ?? null,
                'is_active' => $request->boolean('is_active', true),
            ]);

            $this->syncVariants($product, $validated['variants']);
            $this->storeImages($product, $request);

            return $product;
        });

        return redirect()->route('tenant.products.edit', $product)->with('status', 'Produk ditambahkan.');
    }

    public function edit(Product $product): View
    {
        $product->load(['variants' => fn ($q) => $q->orderBy('sort_order'), 'images']);

        return view('tenant.products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validated($request);

        DB::transaction(function () use ($product, $validated, $request) {
            $product->update([
                'category_id' => $validated['category_id'] ?? null,
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $request->boolean('is_active', true),
            ]);

            $this->syncVariants($product, $validated['variants']);
            $this->storeImages($product, $request);
        });

        return redirect()->route('tenant.products.edit', $product)->with('status', 'Produk diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete(); // soft delete - riwayat order yang mereferensikan produk ini tetap aman

        return redirect()->route('tenant.products.index')->with('status', 'Produk dihapus.');
    }

    public function destroyImage(Product $product, ProductImage $image): RedirectResponse
    {
        abort_unless($image->product_id === $product->id, 404);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('status', 'Gambar dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'images.*' => ['nullable', 'image', 'max:4096'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.id' => ['nullable', 'integer'],
            'variants.*.sku' => ['required', 'string', 'max:100'],
            'variants.*.label' => ['required', 'string', 'max:100'],
            'variants.*.price' => ['required', 'integer', 'min:0'],
            'variants.*.compare_at_price' => ['nullable', 'integer', 'min:0'],
            'variants.*.stock' => ['required', 'integer', 'min:0'],
            'variants.*.weight_grams' => ['nullable', 'integer', 'min:0'],
            'variants.*.is_default' => ['nullable'],
            'variants.*.is_active' => ['nullable'],
        ]);
    }

    /**
     * UPSERT by id (BUKAN delete-all-recreate) - varian yang id-nya stabil tetap
     * stabil, biar tautan order_items ke varian itu gak putus tiap kali produk
     * diedit. Pola ini di-port dari larashop-be AdminProductController::syncVariants.
     * Varian existing yang gak ikut dikirim lagi -> soft delete (bukan hard delete,
     * jaga-jaga kalau ternyata pernah dipakai di order_items).
     */
    private function syncVariants(Product $product, array $variantsInput): void
    {
        $keptIds = [];

        foreach ($variantsInput as $i => $v) {
            $isDefault = isset($v['is_default']) && $v['is_default'];
            $isActive = ! array_key_exists('is_active', $v) || $v['is_active'];

            $data = [
                'product_id' => $product->id,
                'sku' => $v['sku'],
                'label' => $v['label'],
                'price' => $v['price'],
                'compare_at_price' => $v['compare_at_price'] ?? null,
                'stock' => $v['stock'],
                'weight_grams' => $v['weight_grams'] ?? null,
                'is_default' => $isDefault,
                'is_active' => $isActive,
                'sort_order' => $i,
            ];

            if (! empty($v['id'])) {
                $variant = ProductVariant::where('product_id', $product->id)->find($v['id']);
                if ($variant) {
                    $variant->update($data);
                    $keptIds[] = $variant->id;

                    continue;
                }
            }

            // SKU pernah dipakai & soft-deleted sebelumnya (mis. varian lama dihapus
            // lalu ditambah lagi pakai SKU sama) -> pulihkan, jangan bentrok unique.
            $archived = ProductVariant::withTrashed()
                ->where('product_id', $product->id)
                ->where('sku', $v['sku'])
                ->whereNotNull('deleted_at')
                ->first();

            if ($archived) {
                $archived->restore();
                $archived->update($data);
                $keptIds[] = $archived->id;

                continue;
            }

            $created = ProductVariant::create($data);
            $keptIds[] = $created->id;
        }

        // Pastikan TEPAT 1 varian default (varian pertama kalau gak ada yg ditandai).
        $final = ProductVariant::whereIn('id', $keptIds)->get();
        if ($final->isNotEmpty() && $final->where('is_default', true)->isEmpty()) {
            $final->first()->update(['is_default' => true]);
        }

        ProductVariant::where('product_id', $product->id)
            ->whereNotIn('id', $keptIds)
            ->get()
            ->each(fn (ProductVariant $v) => $v->delete());
    }

    private function storeImages(Product $product, Request $request): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $nextOrder = $product->images()->max('sort_order') + 1;

        foreach ($request->file('images') as $i => $file) {
            // Folder ter-namespace per tenant - bukan cuma soal rapi, juga biar
            // backup/export per-tenant gampang nanti (lihat plan SaaS).
            $path = $file->store('tenants/'.tenant()->id.'/products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'sort_order' => $nextOrder + $i,
            ]);
        }
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (Product::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
