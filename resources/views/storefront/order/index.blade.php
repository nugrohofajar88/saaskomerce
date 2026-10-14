@extends('layouts.storefront')

@section('storefront-content')
    <h1 class="text-headline-lg text-on-surface tracking-tight mb-6">Pesanan Saya</h1>

    @if ($orders->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 rounded-full bg-surface-container-low flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-outline text-[28px]">receipt_long</span>
            </div>
            <p class="text-body-md text-on-surface-variant mb-4">Belum ada pesanan.</p>
            <a href="{{ route('storefront.home') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-container text-on-primary text-label-lg font-semibold shadow-sm hover:bg-primary transition-all">Mulai Belanja</a>
        </div>
    @else
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm divide-y divide-surface-container">
            @foreach ($orders as $order)
                @php
                    $statusMeta = match ($order->status) {
                        'paid' => ['bg-tertiary-fixed text-on-tertiary-fixed', 'Lunas'],
                        'pending_payment' => ['bg-amber-50 text-amber-800', 'Menunggu Pembayaran'],
                        'expired' => ['bg-error-container text-on-error-container', 'Kedaluwarsa'],
                        default => ['bg-error-container text-on-error-container', 'Dibatalkan'],
                    };
                @endphp
                <a href="{{ route('storefront.order.show', $order) }}" class="p-4 flex items-center justify-between hover:bg-surface-container-low transition-colors">
                    <div>
                        <div class="text-label-lg text-on-surface font-bold">{{ $order->order_number }}</div>
                        <div class="text-label-sm text-on-surface-variant">{{ $order->created_at->translatedFormat('d M Y, H:i') }} · Rp{{ number_format($order->total, 0, ',', '.') }}</div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-label-sm font-semibold {{ $statusMeta[0] }}">{{ $statusMeta[1] }}</span>
                </a>
            @endforeach
        </div>

        <div class="mt-4">{{ $orders->links() }}</div>
    @endif
@endsection
