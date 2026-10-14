@extends('layouts.storefront')

@section('storefront-content')
    <h1 class="text-headline-lg text-on-surface tracking-tight mb-6">Checkout</h1>

    <div class="grid md:grid-cols-2 gap-6 items-start">
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden order-2 md:order-1">
            <div class="p-5 border-b border-surface-container">
                <h2 class="text-headline-sm text-on-surface">Ringkasan Pesanan</h2>
            </div>
            <div class="divide-y divide-surface-container">
                @foreach ($items as $item)
                    <div class="p-4 flex justify-between text-body-sm text-on-surface gap-3">
                        <span>{{ $item['qty'] }}x {{ $item['variant']->product->name }} <span class="text-on-surface-variant">({{ $item['variant']->label }})</span></span>
                        <span class="shrink-0 text-price-md">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
            <div class="p-5 bg-surface-container-low flex justify-between items-center">
                <span class="text-headline-sm text-on-surface">Total</span>
                <span class="text-price-lg text-primary">Rp{{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="order-1 md:order-2">
            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-error-container text-on-error-container text-body-sm p-4">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('storefront.checkout.store') }}" class="flex flex-col gap-4 bg-surface-container-lowest rounded-2xl shadow-sm p-6">
                @csrf
                <div class="flex flex-col gap-1.5">
                    <label class="text-label-lg text-on-surface">Nama Penerima</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required
                           class="w-full h-11 px-3.5 rounded-lg bg-surface-container-low text-on-surface text-body-sm focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary transition-all">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-label-lg text-on-surface">No. WhatsApp</label>
                    <div class="flex items-center h-11 rounded-lg bg-surface-container-low overflow-hidden focus-within:ring-2 focus-within:ring-primary focus-within:bg-surface-container-lowest transition-all">
                        <span class="pl-3.5 pr-2 text-tertiary"><span class="material-symbols-outlined text-[18px]">chat</span></span>
                        <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="081234567890"
                               class="flex-1 h-full bg-transparent pr-3 text-on-surface text-body-sm focus:outline-none">
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-label-lg text-on-surface">Alamat Pengiriman</label>
                    <textarea name="address" rows="3" placeholder="Opsional kalau ambil sendiri di toko"
                              class="w-full p-3.5 rounded-lg bg-surface-container-low text-on-surface text-body-sm focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary transition-all resize-y">{{ old('address') }}</textarea>
                </div>
                <button type="submit" class="w-full mt-1 inline-flex items-center justify-center gap-2 rounded-xl bg-primary-container text-on-primary px-6 py-3 text-label-lg font-semibold shadow-md hover:bg-primary transition-all">
                    <span>Buat Pesanan</span><span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </button>
            </form>
        </div>
    </div>
@endsection
