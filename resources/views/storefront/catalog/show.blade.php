@extends('layouts.storefront')

@section('storefront-content')
    <a href="{{ route('storefront.home') }}" class="inline-flex items-center gap-1.5 text-primary text-label-lg mb-4 group">
        <span class="material-symbols-outlined text-[18px] transition-transform group-hover:-translate-x-1">arrow_back</span>
        <span>Kembali ke Katalog</span>
    </a>

    <div class="grid md:grid-cols-2 gap-8">
        <div class="aspect-square bg-surface-container rounded-2xl overflow-hidden flex items-center justify-center shadow-sm">
            @if ($product->images->isNotEmpty())
                <img src="{{ $product->images->first()->url }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
            @else
                <span class="material-symbols-outlined text-outline text-[48px]">image</span>
            @endif
        </div>

        <div>
            @if ($product->category)
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-label-sm bg-surface-container-high text-on-surface-variant font-medium">{{ $product->category->name }}</span>
            @endif
            <h1 class="text-headline-lg text-on-surface tracking-tight mt-2">{{ $product->name }}</h1>
            @if ($product->description)
                <p class="text-body-md text-on-surface-variant mt-2 leading-relaxed">{{ $product->description }}</p>
            @endif

            @if ($product->variants->isEmpty())
                <div class="mt-6 inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-error-container text-on-error-container text-label-lg">
                    <span class="material-symbols-outlined text-[18px]">block</span><span>Stok habis</span>
                </div>
            @else
                <form method="POST" action="{{ route('storefront.cart.store') }}" class="mt-6 flex flex-col gap-4">
                    @csrf
                    <div class="flex flex-col gap-1.5">
                        <label class="text-label-lg text-on-surface">Varian</label>
                        <div class="relative">
                            <select name="variant_id" class="w-full h-11 px-3.5 pr-10 rounded-lg bg-surface-container-lowest text-on-surface text-body-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-primary appearance-none cursor-pointer">
                                @foreach ($product->variants as $variant)
                                    <option value="{{ $variant->id }}" @disabled($variant->stock <= 0)>
                                        {{ $variant->label }} — Rp{{ number_format($variant->price, 0, ',', '.') }}
                                        @if ($variant->stock <= 0) (habis) @endif
                                    </option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-3 text-on-surface-variant pointer-events-none text-[20px]">expand_more</span>
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
@endsection
