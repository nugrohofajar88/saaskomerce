@extends('layouts.storefront')

@section('storefront-content')
    <div class="mb-8 text-center">
        <h1 class="text-headline-xl text-on-surface tracking-tight">{{ tenant()->name }}</h1>
        <p class="text-body-md text-on-surface-variant mt-1">Pilih produk favoritmu &amp; pesan langsung dari sini.</p>
    </div>

    @if ($products->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 rounded-full bg-surface-container-low flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-outline text-[28px]">inventory_2</span>
            </div>
            <p class="text-body-md text-on-surface-variant">Belum ada produk tersedia di toko ini.</p>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($products as $product)
                <a href="{{ route('storefront.product', $product) }}" class="block bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition-shadow group">
                    <div class="aspect-square bg-surface-container flex items-center justify-center overflow-hidden">
                        @if ($product->images->isNotEmpty())
                            <img src="{{ $product->images->first()->url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" alt="{{ $product->name }}">
                        @else
                            <span class="material-symbols-outlined text-outline text-[32px]">image</span>
                        @endif
                    </div>
                    <div class="p-3">
                        <div class="text-label-lg text-on-surface truncate group-hover:text-primary transition-colors">{{ $product->name }}</div>
                        <div class="text-price-md text-on-surface mt-0.5">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
