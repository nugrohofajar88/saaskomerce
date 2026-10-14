@extends('layouts.storefront')

@section('storefront-content')
    <h1 class="text-headline-lg text-on-surface tracking-tight mb-6">Keranjang</h1>

    @if ($items->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 rounded-full bg-surface-container-low flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-outline text-[28px]">shopping_bag</span>
            </div>
            <p class="text-body-md text-on-surface-variant mb-4">Keranjang masih kosong.</p>
            <a href="{{ route('storefront.home') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-container text-on-primary text-label-lg font-semibold shadow-sm hover:bg-primary transition-all">Mulai Belanja</a>
        </div>
    @else
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm divide-y divide-surface-container">
            @foreach ($items as $item)
                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-14 h-14 rounded-xl bg-surface-container shrink-0 flex items-center justify-center overflow-hidden">
                            @if ($item['variant']->product->images->isNotEmpty())
                                <img src="{{ $item['variant']->product->images->first()->url }}" class="w-full h-full object-cover" alt="">
                            @else
                                <span class="material-symbols-outlined text-outline text-[20px]">image</span>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <div class="text-label-lg text-on-surface truncate">{{ $item['variant']->product->name }}</div>
                            <div class="text-label-sm text-on-surface-variant">{{ $item['variant']->label }} · Rp{{ number_format($item['variant']->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <form method="POST" action="{{ route('storefront.cart.update', $item['variant']->id) }}" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <input type="number" name="qty" value="{{ $item['qty'] }}" min="0" max="{{ $item['variant']->stock }}"
                                   class="w-16 h-9 px-2 rounded-lg bg-surface-container-low text-on-surface text-body-sm text-center focus:outline-none focus:ring-2 focus:ring-primary">
                            <button type="submit" class="text-label-sm text-primary font-semibold">Update</button>
                        </form>
                        <form method="POST" action="{{ route('storefront.cart.destroy', $item['variant']->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-lg hover:bg-error-container text-on-surface-variant hover:text-error flex items-center justify-center transition-colors">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </form>
                        <span class="text-price-md text-on-surface w-24 text-right">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex items-center justify-between bg-surface-container-lowest rounded-2xl shadow-sm p-5">
            <div class="flex flex-col">
                <span class="text-label-sm text-on-surface-variant">Total Belanja</span>
                <span class="text-headline-md text-on-surface">Rp{{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <a href="{{ route('storefront.checkout') }}" class="inline-flex items-center gap-2 rounded-xl bg-primary-container text-on-primary px-6 py-3 text-label-lg font-semibold shadow-md hover:bg-primary transition-all">
                <span>Checkout</span><span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
    @endif
@endsection
