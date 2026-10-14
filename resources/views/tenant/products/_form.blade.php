@php
    $initialVariants = isset($product)
        ? $product->variants->map(fn ($v) => [
            'id' => $v->id, 'sku' => $v->sku, 'label' => $v->label, 'price' => $v->price,
            'compare_at_price' => $v->compare_at_price, 'stock' => $v->stock,
            'weight_grams' => $v->weight_grams, 'is_default' => $v->is_default, 'is_active' => $v->is_active,
        ])->values()
        : collect([['id' => null, 'sku' => '', 'label' => '', 'price' => 0, 'compare_at_price' => null, 'stock' => 0, 'weight_grams' => null, 'is_default' => true, 'is_active' => true]]);
@endphp

<div x-data="{ variants: @js($initialVariants), active: {{ old('is_active', $product->is_active ?? true) ? 'true' : 'false' }} }">
    <input type="hidden" name="is_active" :value="active ? 1 : 0">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-8 flex flex-col gap-6">
            <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm flex flex-col gap-6">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-surface-container-low text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">info</span>
                    </div>
                    <h2 class="text-headline-md text-on-surface">Informasi Dasar</h2>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-label-lg text-on-surface" for="product-name">Nama Produk <span class="text-error">*</span></label>
                    <input id="product-name" name="name" type="text" required value="{{ old('name', $product->name ?? '') }}"
                           placeholder="Contoh: Biji Kopi Arabika Lintong"
                           class="w-full h-11 px-3.5 rounded-lg bg-surface-container-lowest text-on-surface text-body-sm focus:outline-none focus:ring-2 focus:ring-primary shadow-sm">
                </div>

                <div class="flex flex-col gap-1.5">
                    <div class="flex items-center justify-between">
                        <label class="text-label-lg text-on-surface" for="product-category">Kategori</label>
                        <a href="{{ route('tenant.categories.index') }}" class="text-primary hover:underline text-label-sm inline-flex items-center gap-0.5">
                            <span class="material-symbols-outlined text-[16px]">add_circle</span><span>Kelola Kategori</span>
                        </a>
                    </div>
                    <div class="relative">
                        <select id="product-category" name="category_id"
                                class="w-full h-11 px-3.5 pr-10 rounded-lg bg-surface-container-lowest text-on-surface text-body-sm focus:outline-none focus:ring-2 focus:ring-primary shadow-sm appearance-none cursor-pointer">
                            <option value="">— Tanpa kategori —</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? null) == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-3 text-on-surface-variant pointer-events-none text-[20px]">expand_more</span>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-label-lg text-on-surface" for="product-desc">Deskripsi Produk</label>
                    <textarea id="product-desc" name="description" rows="4" placeholder="Jelaskan produk Anda..."
                              class="w-full p-3.5 rounded-lg bg-surface-container-lowest text-on-surface text-body-sm focus:outline-none focus:ring-2 focus:ring-primary shadow-sm resize-y">{{ old('description', $product->description ?? '') }}</textarea>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-label-lg text-on-surface">Foto Produk</label>
                    <p class="text-label-sm text-on-surface-variant -mt-1">Format PNG/JPG/WEBP, bisa pilih beberapa sekaligus.</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-1">
                        @isset($product)
                            @foreach ($product->images as $image)
                                <div class="relative group rounded-xl overflow-hidden aspect-square shadow-sm bg-surface-container-high">
                                    <img src="{{ $image->url }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                                    <div class="absolute inset-0 bg-inverse-surface/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <button type="submit" form="delete-image-{{ $image->id }}"
                                                class="w-8 h-8 rounded-full bg-surface-container-lowest text-error hover:bg-error-container flex items-center justify-center shadow-md">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endisset
                        <label class="relative rounded-xl aspect-square bg-surface-container-low hover:bg-surface-container hover:shadow-md transition-all flex flex-col items-center justify-center text-center p-3 cursor-pointer">
                            <div class="w-10 h-10 rounded-full bg-surface-container-lowest text-primary flex items-center justify-center shadow-sm mb-1.5">
                                <span class="material-symbols-outlined text-[22px]">add_photo_alternate</span>
                            </div>
                            <span class="text-label-md font-semibold text-primary">Upload Foto</span>
                            <input type="file" name="images[]" multiple accept="image/*" class="sr-only">
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm flex flex-col gap-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-surface-container-low text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">layers</span>
                        </div>
                        <div>
                            <h2 class="text-headline-md text-on-surface">Varian Produk &amp; Harga</h2>
                            <p class="text-label-sm text-on-surface-variant">Minimal 1 varian — harga &amp; stok toko dihitung dari sini.</p>
                        </div>
                    </div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-low text-primary text-label-sm">
                        <span class="material-symbols-outlined text-[16px]">calculate</span>
                        <span x-text="`Total ${variants.length} Varian`"></span>
                    </div>
                </div>

                <div class="w-full overflow-x-auto rounded-xl shadow-sm">
                    <table class="w-full min-w-[700px] text-left border-collapse bg-surface-container-lowest">
                        <thead>
                            <tr class="bg-surface-container-low text-on-surface text-label-md">
                                <th class="py-3 px-3 text-center w-14">Default</th>
                                <th class="py-3 px-3">Label Varian</th>
                                <th class="py-3 px-3">SKU</th>
                                <th class="py-3 px-3 w-36">Harga Jual</th>
                                <th class="py-3 px-3 w-24">Stok</th>
                                <th class="py-3 px-3 w-28">Berat (gr)</th>
                                <th class="py-3 px-3 text-center w-12">Hapus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-container text-body-sm">
                            <template x-for="(variant, i) in variants" :key="i">
                                <tr class="hover:bg-surface-container-low/60 transition-colors">
                                    <td class="py-3 px-3 text-center align-middle">
                                        <input type="radio" name="default_variant_radio" :checked="variant.is_default"
                                               @change="variants.forEach((v, j) => v.is_default = (j === i))"
                                               class="w-4 h-4 text-primary accent-primary cursor-pointer">
                                        <input type="hidden" :name="`variants[${i}][is_default]`" :value="variant.is_default ? 1 : 0">
                                    </td>
                                    <td class="py-3 px-3 align-middle">
                                        <input type="hidden" :name="`variants[${i}][id]`" x-model="variant.id">
                                        <input type="text" :name="`variants[${i}][label]`" x-model="variant.label" required placeholder="mis. 250 gram"
                                               class="w-full h-9 px-2.5 rounded-md bg-surface-container-lowest text-on-surface text-label-lg focus:outline-none focus:ring-2 focus:ring-primary shadow-sm">
                                    </td>
                                    <td class="py-3 px-3 align-middle">
                                        <input type="text" :name="`variants[${i}][sku]`" x-model="variant.sku" required placeholder="SKU"
                                               class="w-full h-9 px-2.5 rounded-md bg-surface-container-lowest text-on-surface text-label-md uppercase focus:outline-none focus:ring-2 focus:ring-primary shadow-sm">
                                    </td>
                                    <td class="py-3 px-3 align-middle">
                                        <div class="relative flex items-center">
                                            <span class="absolute left-2 text-on-surface-variant text-label-sm font-semibold">Rp</span>
                                            <input type="number" :name="`variants[${i}][price]`" x-model="variant.price" required min="0"
                                                   class="w-full h-9 pl-8 pr-2 text-right rounded-md bg-surface-container-lowest text-on-surface text-label-lg focus:outline-none focus:ring-2 focus:ring-primary shadow-sm">
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 align-middle">
                                        <input type="number" :name="`variants[${i}][stock]`" x-model="variant.stock" required min="0"
                                               class="w-full h-9 px-2 text-right rounded-md bg-surface-container-lowest text-on-surface text-body-sm focus:outline-none focus:ring-2 focus:ring-primary shadow-sm">
                                    </td>
                                    <td class="py-3 px-3 align-middle">
                                        <div class="relative flex items-center">
                                            <input type="number" :name="`variants[${i}][weight_grams]`" x-model="variant.weight_grams" min="0"
                                                   class="w-full h-9 pr-7 pl-2 text-right rounded-md bg-surface-container-lowest text-on-surface text-body-sm focus:outline-none focus:ring-2 focus:ring-primary shadow-sm">
                                            <span class="absolute right-2 text-on-surface-variant text-label-sm">g</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 text-center align-middle">
                                        <button type="button" @click="variants.splice(i, 1)" x-show="variants.length > 1"
                                                class="p-1.5 text-on-surface-variant hover:text-error hover:bg-error-container rounded transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <button type="button"
                        @click="variants.push({ id: null, sku: '', label: '', price: 0, compare_at_price: null, stock: 0, weight_grams: null, is_default: false, is_active: true })"
                        class="w-full py-3 px-4 rounded-xl bg-surface-container-low hover:bg-surface-container text-primary text-label-lg flex items-center justify-center gap-2 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    <span>+ Tambah Baris Varian Baru</span>
                </button>
            </div>
        </div>

        <div class="lg:col-span-4 flex flex-col gap-6 lg:sticky lg:top-20">
            <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-headline-sm text-on-surface">Status Publikasi</h3>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-label-sm font-semibold"
                          :class="active ? 'bg-tertiary-fixed text-on-tertiary-fixed' : 'bg-surface-container-highest text-on-surface-variant'"
                          x-text="active ? 'Siap Dijual' : 'Disembunyikan'"></span>
                </div>
                <div class="p-2 rounded-xl bg-surface-container-low flex items-start justify-between gap-3">
                    <div class="flex flex-col">
                        <span class="text-label-lg text-on-surface font-semibold">Produk Aktif</span>
                        <span class="text-label-sm text-on-surface-variant">Langsung tayang di etalase toko.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                        <input type="checkbox" x-model="active" class="sr-only peer">
                        <div class="w-11 h-6 bg-surface-container-highest rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-surface-container-lowest after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-container"></div>
                    </label>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-tertiary">storefront</span>
                    <span class="text-label-sm text-on-surface-variant truncate">Katalog: <strong class="text-on-surface font-semibold">{{ tenant()->subdomain }}.{{ config('app.base_domain') }}</strong></span>
                </div>
            </div>
        </div>
    </div>
</div>

@isset($product)
    @foreach ($product->images as $image)
        <form id="delete-image-{{ $image->id }}" method="POST" action="{{ route('tenant.products.images.destroy', [$product, $image]) }}" class="hidden">
            @csrf @method('DELETE')
        </form>
    @endforeach
@endisset
