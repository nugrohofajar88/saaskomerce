@php
    $initialVariants = isset($product)
        ? $product->variants->map(fn ($v) => [
            'id' => $v->id, 'sku' => $v->sku, 'label' => $v->label, 'price' => $v->price,
            'compare_at_price' => $v->compare_at_price, 'stock' => $v->stock,
            'weight_grams' => $v->weight_grams, 'is_default' => $v->is_default, 'is_active' => $v->is_active,
        ])->values()
        : collect([['id' => null, 'sku' => '', 'label' => '', 'price' => 0, 'compare_at_price' => null, 'stock' => 0, 'weight_grams' => null, 'is_default' => true, 'is_active' => true]]);
@endphp

<div x-data="{ variants: @js($initialVariants) }">
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required class="w-full rounded-lg border px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Kategori</label>
            <select name="category_id" class="w-full rounded-lg border px-3 py-2">
                <option value="">— Tanpa kategori —</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? null) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full rounded-lg border px-3 py-2">{{ old('description', $product->description ?? '') }}</textarea>
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))>
            Aktif (tampil di storefront)
        </label>

        <div>
            <label class="block text-sm font-medium mb-1">Gambar</label>
            <input type="file" name="images[]" multiple accept="image/*" class="w-full text-sm">
            @isset($product)
                <div class="flex gap-2 mt-2">
                    @foreach ($product->images as $image)
                        <div class="relative">
                            <img src="{{ $image->url }}" class="w-16 h-16 object-cover rounded-lg border">
                            <button type="submit" form="delete-image-{{ $image->id }}" class="absolute -top-1 -right-1 bg-red-600 text-white rounded-full w-5 h-5 text-xs">×</button>
                        </div>
                    @endforeach
                </div>
                @foreach ($product->images as $image)
                    <form id="delete-image-{{ $image->id }}" method="POST" action="{{ route('tenant.products.images.destroy', [$product, $image]) }}" class="hidden">
                        @csrf @method('DELETE')
                    </form>
                @endforeach
            @endisset
        </div>
    </div>

    <h2 class="font-semibold mt-6 mb-2">Varian</h2>
    <p class="text-xs text-gray-500 mb-3">Harga/stok/berat produk SELALU diambil dari varian — minimal 1 varian wajib diisi. Centang "Default" pada varian yang jadi harga utama produk.</p>

    <template x-for="(variant, i) in variants" :key="i">
        <div class="grid grid-cols-12 gap-2 items-center mb-2 bg-gray-50 rounded-lg p-2">
            <input type="hidden" :name="`variants[${i}][id]`" x-model="variant.id">
            <input type="text" :name="`variants[${i}][sku]`" x-model="variant.sku" placeholder="SKU" required class="col-span-2 rounded-lg border px-2 py-1.5 text-sm">
            <input type="text" :name="`variants[${i}][label]`" x-model="variant.label" placeholder="Label (mis. 500ml)" required class="col-span-3 rounded-lg border px-2 py-1.5 text-sm">
            <input type="number" :name="`variants[${i}][price]`" x-model="variant.price" placeholder="Harga" required class="col-span-2 rounded-lg border px-2 py-1.5 text-sm">
            <input type="number" :name="`variants[${i}][stock]`" x-model="variant.stock" placeholder="Stok" required class="col-span-1 rounded-lg border px-2 py-1.5 text-sm">
            <input type="number" :name="`variants[${i}][weight_grams]`" x-model="variant.weight_grams" placeholder="Berat (g)" class="col-span-2 rounded-lg border px-2 py-1.5 text-sm">
            <label class="col-span-1 flex items-center gap-1 text-xs">
                <input type="checkbox" :name="`variants[${i}][is_default]`" value="1" x-model="variant.is_default"> Default
            </label>
            <button type="button" @click="variants.splice(i, 1)" x-show="variants.length > 1" class="col-span-1 text-red-600 text-sm">Hapus</button>
        </div>
    </template>

    <button type="button"
            @click="variants.push({ id: null, sku: '', label: '', price: 0, compare_at_price: null, stock: 0, weight_grams: null, is_default: false, is_active: true })"
            class="text-sm text-blue-600 mt-1">+ Tambah Varian</button>
</div>
