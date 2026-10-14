@extends('layouts.storefront')

@section('storefront-content')
    @php
        $variantsData = $product->variants->map(fn ($v) => ['id' => $v->id, 'label' => $v->label, 'price' => $v->price, 'stock' => $v->stock]);
        $imagesData = $product->images->map(fn ($i) => $i->url);
    @endphp

    <a href="{{ route('storefront.home') }}" class="inline-flex items-center gap-1.5 text-primary text-label-lg mb-4 group">
        <span class="material-symbols-outlined text-[18px] transition-transform group-hover:-translate-x-1">arrow_back</span>
        <span>Kembali ke Katalog</span>
    </a>

    <div x-data="{
            images: @js($imagesData),
            activeImage: {{ $imagesData->isNotEmpty() ? 0 : 'null' }},
            variants: @js($variantsData),
            selectedVariantId: {{ $variantsData->first()['id'] ?? 'null' }},
            get selectedVariant() { return this.variants.find(v => v.id === this.selectedVariantId) },
         }"
         class="grid md:grid-cols-2 gap-8">
        <div class="flex flex-col gap-3">
            <div class="aspect-square bg-surface-container rounded-2xl overflow-hidden flex items-center justify-center shadow-sm">
                <template x-if="images.length">
                    <img :src="images[activeImage]" class="w-full h-full object-cover" :alt="'{{ $product->name }}'">
                </template>
                <template x-if="!images.length">
                    <span class="material-symbols-outlined text-outline text-[48px]">image</span>
                </template>
            </div>
            <div class="grid grid-cols-5 gap-2" x-show="images.length > 1">
                <template x-for="(img, i) in images" :key="i">
                    <button type="button" @click="activeImage = i"
                            class="aspect-square rounded-lg overflow-hidden border-2 transition-colors"
                            :class="activeImage === i ? 'border-primary' : 'border-transparent'">
                        <img :src="img" class="w-full h-full object-cover">
                    </button>
                </template>
            </div>
        </div>

        <div>
            @if ($product->category)
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-label-sm bg-surface-container-high text-on-surface-variant font-medium">{{ $product->category->name }}</span>
            @endif
            <h1 class="text-headline-lg text-on-surface tracking-tight mt-2">{{ $product->name }}</h1>
            <p class="text-price-lg text-primary mt-2" x-text="'Rp' + (selectedVariant ? selectedVariant.price.toLocaleString('id-ID') : '0')"></p>

            @if ($product->description)
                <p class="text-body-md text-on-surface-variant mt-3 leading-relaxed">{{ $product->description }}</p>
            @endif

            @if ($product->variants->isEmpty())
                <div class="mt-6 inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-error-container text-on-error-container text-label-lg">
                    <span class="material-symbols-outlined text-[18px]">block</span><span>Stok habis</span>
                </div>
            @else
                <form method="POST" action="{{ route('storefront.cart.store') }}" class="mt-6 flex flex-col gap-4">
                    @csrf
                    <input type="hidden" name="variant_id" x-model="selectedVariantId">

                    <div class="flex flex-col gap-2">
                        <label class="text-label-lg text-on-surface">Varian</label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="variant in variants" :key="variant.id">
                                <button type="button" @click="selectedVariantId = variant.id" :disabled="variant.stock <= 0"
                                        class="px-4 py-2 rounded-lg text-label-md border transition-all disabled:opacity-40 disabled:cursor-not-allowed"
                                        :class="selectedVariantId === variant.id ? 'bg-primary-container text-on-primary border-primary-container font-semibold' : 'bg-surface-container-lowest text-on-surface border-outline-variant hover:border-primary'">
                                    <span x-text="variant.label"></span>
                                    <span x-show="variant.stock <= 0" class="ml-1 text-label-sm">(habis)</span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-label-lg text-on-surface">Jumlah</label>
                        <input type="number" name="qty" value="1" min="1" class="w-24 h-11 px-3.5 rounded-lg bg-surface-container-lowest text-on-surface text-body-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary-container text-on-primary px-6 py-3 text-label-lg font-semibold shadow-md hover:bg-primary transition-all">
                        <span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>
                        <span>Tambah ke Keranjang</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if ($relatedProducts->isNotEmpty())
        <div class="mt-16">
            <h2 class="text-headline-md text-on-surface mb-4">Produk Lainnya</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($relatedProducts as $related)
                    <a href="{{ route('storefront.product', $related) }}" class="block bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition-shadow group">
                        <div class="aspect-square bg-surface-container flex items-center justify-center overflow-hidden">
                            @if ($related->images->isNotEmpty())
                                <img src="{{ $related->images->first()->url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" alt="{{ $related->name }}">
                            @else
                                <span class="material-symbols-outlined text-outline text-[28px]">image</span>
                            @endif
                        </div>
                        <div class="p-3">
                            <div class="text-label-lg text-on-surface truncate group-hover:text-primary transition-colors">{{ $related->name }}</div>
                            <div class="text-price-md text-on-surface mt-0.5">Rp{{ number_format($related->price, 0, ',', '.') }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
@endsection
