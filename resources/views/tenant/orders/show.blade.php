@extends('layouts.tenant')

@section('page-title', 'Pesanan')

@section('tenant-content')
    @php
        $statusMeta = match ($order->status) {
            'paid' => ['bg-tertiary-fixed text-on-tertiary-fixed', 'Lunas'],
            'pending_payment' => ['bg-amber-50 text-amber-800', 'Menunggu Verifikasi Pembayaran'],
            'expired' => ['bg-error-container text-on-error-container', 'Kedaluwarsa'],
            default => ['bg-error-container text-on-error-container', 'Dibatalkan'],
        };
        $waNumber = preg_replace('/^0/', '62', preg_replace('/\D/', '', (string) $order->customer_phone));
    @endphp

    <div class="flex items-center justify-between mb-2">
        <a href="{{ route('tenant.orders.index') }}" class="inline-flex items-center gap-2 text-primary text-label-lg group">
            <span class="material-symbols-outlined text-[18px] transition-transform group-hover:-translate-x-1">arrow_back</span>
            <span>Kembali ke Daftar Pesanan</span>
        </a>
    </div>

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-2">
        <div class="flex flex-col gap-1.5">
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-headline-xl text-on-surface font-bold tracking-tight">{{ $order->order_number }}</h1>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-label-md font-semibold {{ $statusMeta[0] }} shadow-sm">{{ $statusMeta[1] }}</span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-label-sm bg-surface-container-high text-on-surface-variant font-medium">
                    <span class="material-symbols-outlined text-[14px]">account_balance</span>{{ $order->payment_method === 'transfer' ? 'Transfer Manual' : $order->payment_method }}
                </span>
            </div>
            <div class="flex items-center gap-2 text-body-sm text-on-surface-variant">
                <span class="material-symbols-outlined text-[16px]">schedule</span>
                <span>Dibuat {{ $order->created_at->diffForHumans() }} ({{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB)</span>
            </div>
        </div>
        @if ($order->status === 'pending_payment')
            <form method="POST" action="{{ route('tenant.orders.mark-paid', $order) }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-tertiary-container text-on-tertiary hover:bg-tertiary transition-all shadow-md text-label-lg font-semibold">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    <span>Tandai Lunas &amp; Verifikasi</span>
                </button>
            </form>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-8 flex flex-col gap-6">
            <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-2.5 pb-4 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[18px]">person</span>
                    </span>
                    <h2 class="text-headline-md text-on-surface">Data Pelanggan &amp; Pengiriman</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1 p-4 rounded-xl bg-surface-container-low">
                        <span class="text-label-sm uppercase tracking-wider text-on-surface-variant font-semibold">Profil Pembeli</span>
                        <span class="text-headline-sm text-on-surface font-semibold mt-1">{{ $order->customer_name }}</span>
                        @if ($order->customer_email)
                            <div class="flex items-center gap-2 text-body-sm text-on-surface-variant">
                                <span class="material-symbols-outlined text-[16px]">mail</span><span>{{ $order->customer_email }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between gap-2 text-body-sm text-on-surface pt-1">
                            <div class="flex items-center gap-1.5 font-medium">
                                <span class="material-symbols-outlined text-[16px] text-tertiary">chat</span><span>{{ $order->customer_phone }}</span>
                            </div>
                            @if ($waNumber)
                                <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-surface-container-lowest text-tertiary-container hover:bg-tertiary-fixed text-label-sm font-semibold shadow-sm transition-colors">
                                    <span class="material-symbols-outlined text-[14px]">send</span>Chat WhatsApp
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col gap-1 p-4 rounded-xl bg-surface-container-low">
                        <span class="text-label-sm uppercase tracking-wider text-on-surface-variant font-semibold">Alamat Tujuan</span>
                        <p class="text-body-md text-on-surface leading-relaxed mt-1">{{ $order->shipping_address ?: 'Ambil langsung di toko' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-2.5 pb-3 mb-3">
                    <span class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[18px]">local_mall</span>
                    </span>
                    <h2 class="text-headline-md text-on-surface">Rincian Barang ({{ $order->items->count() }} Produk)</h2>
                </div>
                <div class="flex flex-col gap-2.5">
                    @foreach ($order->items as $item)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-surface-container-low gap-4">
                            <div class="flex flex-col min-w-0">
                                <span class="text-headline-sm text-on-surface font-semibold truncate">{{ $item->product_name }}</span>
                                <div class="flex flex-wrap items-center gap-2 text-label-sm text-on-surface-variant mt-0.5">
                                    <span class="bg-surface-container-lowest px-2 py-0.5 rounded font-medium">Varian: {{ $item->variant_label }}</span>
                                    <span>•</span><span>SKU: {{ $item->product_sku }}</span>
                                </div>
                                <span class="text-body-sm text-on-surface-variant mt-1">Rp{{ number_format($item->price, 0, ',', '.') }} × {{ $item->qty }}</span>
                            </div>
                            <span class="text-price-md text-on-surface shrink-0">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 pt-4 flex items-center justify-between bg-surface-container-low p-4 rounded-xl">
                    <span class="text-headline-sm text-on-surface font-bold">Total Pembayaran</span>
                    <span class="text-price-lg text-primary font-extrabold tracking-tight">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 flex flex-col gap-6">
            <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-md {{ $order->payment_proof_path ? 'ring-2 ring-amber-400/40' : '' }}">
                <div class="flex items-center justify-between pb-3 mb-3">
                    <h2 class="text-headline-sm text-on-surface font-bold">Verifikasi Pembayaran</h2>
                </div>

                @if ($order->payment_proof_path)
                    <a href="{{ asset('storage/'.$order->payment_proof_path) }}" target="_blank" class="block rounded-xl overflow-hidden shadow-sm">
                        <img src="{{ asset('storage/'.$order->payment_proof_path) }}" class="w-full object-cover" alt="Bukti transfer">
                    </a>
                    <p class="text-label-sm text-on-surface-variant mt-2">Diupload {{ $order->payment_proof_uploaded_at?->diffForHumans() }}.</p>
                @else
                    <div class="p-4 rounded-xl bg-surface-container-low text-center text-body-sm text-on-surface-variant">
                        Pembeli belum mengunggah bukti transfer.
                    </div>
                @endif

                @if ($order->status === 'pending_payment')
                    <div class="mt-4 flex flex-col gap-2.5">
                        <form method="POST" action="{{ route('tenant.orders.mark-paid', $order) }}">
                            @csrf
                            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-tertiary-container text-on-tertiary hover:bg-tertiary font-bold flex items-center justify-center gap-2 shadow-md transition-all">
                                <span class="material-symbols-outlined text-[20px]">task_alt</span>
                                <span>Setujui &amp; Tandai Lunas (Rp{{ number_format($order->total, 0, ',', '.') }})</span>
                            </button>
                        </form>
                        @if ($waNumber)
                            <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Kak '.$order->customer_name.', mohon info bukti transfer untuk pesanan '.$order->order_number.' ya.') }}" target="_blank"
                               class="w-full py-2.5 px-4 rounded-xl bg-surface-container-low text-on-surface hover:bg-surface-container-high font-label-md font-semibold flex items-center justify-center gap-2 transition-all">
                                <span class="material-symbols-outlined text-[18px]">warning</span><span>Hubungi via WhatsApp</span>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
