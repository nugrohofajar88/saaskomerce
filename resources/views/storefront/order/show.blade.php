@extends('layouts.storefront')

@section('storefront-content')
    @php
        $statusMeta = match ($order->status) {
            'paid' => ['bg-tertiary-fixed text-on-tertiary-fixed', 'Lunas'],
            'pending_payment' => ['bg-amber-50 text-amber-800', 'Menunggu Pembayaran'],
            'expired' => ['bg-error-container text-on-error-container', 'Kedaluwarsa'],
            default => ['bg-error-container text-on-error-container', 'Dibatalkan'],
        };
    @endphp

    <div class="max-w-xl mx-auto">
        <div class="flex items-center gap-3 mb-1">
            <h1 class="text-headline-lg text-on-surface tracking-tight">{{ $order->order_number }}</h1>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-label-sm font-semibold {{ $statusMeta[0] }}">{{ $statusMeta[1] }}</span>
        </div>
        <p class="text-body-sm text-on-surface-variant mb-6">Dibuat {{ $order->created_at->translatedFormat('d M Y, H:i') }}</p>

        <div class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden">
            <div class="divide-y divide-surface-container">
                @foreach ($order->items as $item)
                    <div class="p-4 flex justify-between text-body-sm text-on-surface gap-3">
                        <span>{{ $item->qty }}x {{ $item->product_name }} <span class="text-on-surface-variant">({{ $item->variant_label }})</span></span>
                        <span class="shrink-0 text-price-md">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
            <div class="p-5 bg-surface-container-low flex justify-between items-center">
                <span class="text-headline-sm text-on-surface">Total</span>
                <span class="text-price-lg text-primary">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        @if ($order->status === 'pending_payment')
            <div class="mt-6 bg-surface-container-lowest rounded-2xl shadow-sm p-6">
                <h2 class="text-headline-sm text-on-surface mb-3">Transfer ke salah satu rekening</h2>
                <div class="flex flex-col gap-2">
                    @forelse ($paymentAccounts as $account)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-container-low">
                            <div class="w-9 h-9 rounded-lg bg-primary flex items-center justify-center text-on-primary text-label-sm font-bold shrink-0">{{ Str::substr($account->bank_name, 0, 3) }}</div>
                            <div class="flex flex-col">
                                <span class="text-label-lg text-on-surface font-bold">{{ $account->account_number }}</span>
                                <span class="text-label-sm text-on-surface-variant">{{ $account->bank_name }} a.n. {{ $account->account_holder }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-body-sm text-on-surface-variant">Hubungi toko untuk info pembayaran.</p>
                    @endforelse
                </div>

                <h2 class="text-headline-sm text-on-surface mt-6 mb-3">Upload Bukti Transfer</h2>
                @if ($order->payment_proof_path)
                    <div class="flex items-center gap-2 text-tertiary text-label-lg font-semibold mb-3">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span><span>Bukti terkirim, menunggu konfirmasi admin.</span>
                    </div>
                    <img src="{{ asset('storage/'.$order->payment_proof_path) }}" class="max-w-xs rounded-xl shadow-sm" alt="Bukti transfer">
                @else
                    <form method="POST" action="{{ route('storefront.order.upload-proof', $order) }}" enctype="multipart/form-data" class="flex flex-col gap-3">
                        @csrf
                        <input type="file" name="proof" accept="image/*" required class="text-body-sm">
                        <button type="submit" class="self-start inline-flex items-center gap-2 rounded-xl bg-primary-container text-on-primary px-5 py-2.5 text-label-lg font-semibold shadow-sm hover:bg-primary transition-all">
                            <span class="material-symbols-outlined text-[18px]">upload</span><span>Kirim Bukti</span>
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>
@endsection
