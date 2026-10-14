@extends('layouts.tenant')

@section('page-title', 'Admin Toko')

@section('tenant-content')
    @php $storefrontUrl = 'http://'.tenant()->subdomain.'.'.config('app.base_domain'); @endphp

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-1 text-on-surface-variant text-label-sm uppercase tracking-wider mb-1">
                <span>Overview</span><span>•</span>
                <span class="text-primary font-semibold">{{ tenant()->status === 'active' ? 'Toko Aktif' : ucfirst(tenant()->status) }}</span>
            </div>
            <h1 class="text-headline-lg text-on-surface tracking-tight">Dashboard</h1>
            <p class="text-body-sm text-on-surface-variant mt-0.5">Ringkasan performa dan aktivitas toko Anda hari ini.</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('tenant.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary text-on-primary text-label-lg shadow-sm hover:bg-primary/95 transition-all">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                <span>+ Tambah Produk</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-label-md text-on-surface-variant uppercase tracking-wider">Total Produk</span>
                <div class="w-11 h-11 rounded-xl bg-surface-container-low text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[24px]">inventory_2</span>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-headline-xl text-on-surface">{{ $productCount }}</span>
                <span class="text-body-md text-on-surface-variant">Produk</span>
            </div>
        </div>
        <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-label-md text-on-surface-variant uppercase tracking-wider">Menunggu Bayar</span>
                <div class="w-11 h-11 rounded-xl bg-secondary-fixed text-on-secondary-fixed-variant flex items-center justify-center">
                    <span class="material-symbols-outlined text-[24px]">pending_actions</span>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl text-on-surface">{{ $pendingOrderCount }}</span>
                    <span class="text-body-md text-on-surface-variant">Pesanan</span>
                </div>
                @if ($pendingVerificationTotal > 0)
                    <div class="mt-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed-variant text-label-sm">
                        <span class="material-symbols-outlined text-[15px]">notification_important</span>
                        <span>Rp{{ number_format($pendingVerificationTotal, 0, ',', '.') }} butuh verifikasi</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-label-md text-on-surface-variant uppercase tracking-wider">Pesanan Lunas</span>
                <div class="w-11 h-11 rounded-xl bg-tertiary-fixed text-on-tertiary-fixed flex items-center justify-center">
                    <span class="material-symbols-outlined text-[24px]">task_alt</span>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl text-on-surface">{{ $paidOrderCount }}</span>
                    <span class="text-body-md text-on-surface-variant">Pesanan Selesai</span>
                </div>
                <div class="mt-3 flex items-center gap-2">
                    <span class="text-label-sm text-on-surface-variant">Total Omset:</span>
                    <span class="text-price-md text-tertiary">Rp{{ number_format($totalOmset, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-8 rounded-2xl bg-surface-container-lowest shadow-sm overflow-hidden">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <h2 class="text-headline-md text-on-surface">Pesanan Terbaru</h2>
                    <p class="text-body-sm text-on-surface-variant mt-0.5">Transaksi pelanggan yang baru masuk.</p>
                </div>
                <a href="{{ route('tenant.orders.index') }}" class="inline-flex items-center gap-1 text-primary text-label-lg">
                    <span>Lihat Semua</span>
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant text-label-md uppercase tracking-wider">
                            <th class="py-3 px-4 font-semibold">No. Pesanan</th>
                            <th class="py-3 px-4 font-semibold">Pelanggan</th>
                            <th class="py-3 px-4 font-semibold">Total</th>
                            <th class="py-3 px-4 font-semibold">Status</th>
                            <th class="py-3 px-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-on-surface text-body-sm">
                        @forelse ($recentOrders as $order)
                            @php
                                $statusStyle = match ($order->status) {
                                    'paid' => 'bg-tertiary-fixed text-on-tertiary-fixed',
                                    'pending_payment' => 'bg-secondary-fixed text-on-secondary-fixed-variant',
                                    default => 'bg-error-container text-on-error-container',
                                };
                                $statusLabel = match ($order->status) {
                                    'paid' => 'Lunas', 'pending_payment' => 'Menunggu Bayar',
                                    'expired' => 'Kedaluwarsa', default => 'Dibatalkan',
                                };
                            @endphp
                            <tr class="hover:bg-surface-container-low/60 transition-colors border-t border-outline-variant/20">
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="text-label-lg font-bold text-on-surface">{{ $order->order_number }}</span>
                                    <span class="block text-label-sm text-on-surface-variant">{{ $order->created_at->translatedFormat('d M, H:i') }}</span>
                                </td>
                                <td class="py-4 px-4">{{ $order->customer_name }}</td>
                                <td class="py-4 px-4 whitespace-nowrap text-price-md">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-label-sm {{ $statusStyle }}">{{ $statusLabel }}</span>
                                </td>
                                <td class="py-4 px-4 text-right whitespace-nowrap">
                                    <a href="{{ route('tenant.orders.show', $order) }}" class="px-3 py-1.5 rounded-lg bg-surface-container-low text-on-surface text-label-sm hover:bg-surface-container-high transition-colors">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-8 px-4 text-center text-on-surface-variant">Belum ada pesanan masuk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="lg:col-span-4 flex flex-col gap-4">
            <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-primary text-[20px]">bolt</span>
                    <h3 class="text-headline-sm text-on-surface">Aksi Cepat</h3>
                </div>
                <div class="flex flex-col gap-2.5">
                    <a href="{{ route('tenant.products.create') }}" class="flex items-center justify-between p-3 rounded-xl bg-surface-container-low hover:bg-surface-container transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-primary-container text-on-primary flex items-center justify-center"><span class="material-symbols-outlined text-[18px]">add_box</span></div>
                            <div class="flex flex-col">
                                <span class="text-label-lg font-semibold text-on-surface">Tambah Produk Baru</span>
                                <span class="text-label-sm text-on-surface-variant">Upload foto, nama & harga</span>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-[18px] text-on-surface-variant group-hover:text-primary group-hover:translate-x-0.5 transition-all">chevron_right</span>
                    </a>
                    <a href="{{ route('tenant.payment-accounts.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-surface-container-low hover:bg-surface-container transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-surface-container-high text-on-surface flex items-center justify-center"><span class="material-symbols-outlined text-[18px]">account_balance</span></div>
                            <div class="flex flex-col">
                                <span class="text-label-lg font-semibold text-on-surface">Rekening Pembayaran</span>
                                <span class="text-label-sm text-on-surface-variant">Kelola rekening bank</span>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-[18px] text-on-surface-variant group-hover:text-primary group-hover:translate-x-0.5 transition-all">chevron_right</span>
                    </a>
                    <a href="{{ route('tenant.settings.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-surface-container-low hover:bg-surface-container transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-surface-container-high text-on-surface flex items-center justify-center"><span class="material-symbols-outlined text-[18px]">schedule</span></div>
                            <div class="flex flex-col">
                                <span class="text-label-lg font-semibold text-on-surface">Pengaturan Toko</span>
                                <span class="text-label-sm text-on-surface-variant">Buka/tutup & metode bayar</span>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-[18px] text-on-surface-variant group-hover:text-primary group-hover:translate-x-0.5 transition-all">chevron_right</span>
                    </a>
                </div>
            </div>

            <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-secondary text-[22px]">lightbulb</span>
                    <span class="text-label-sm font-semibold uppercase tracking-wider text-secondary">Bagikan Toko</span>
                </div>
                <h4 class="text-headline-sm text-on-surface mb-2">Maksimalkan Omset Tanpa Potongan Komisi</h4>
                <p class="text-body-sm text-on-surface-variant leading-relaxed">
                    Bagikan link katalog ke bio Instagram & status WhatsApp — pesanan langsung masuk ke rekening Anda.
                </p>
                <div x-data="{ copied: false }" class="mt-4 p-2 rounded-xl bg-surface-container-low flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="material-symbols-outlined text-[18px] text-tertiary shrink-0">link</span>
                        <span class="text-label-sm text-on-surface truncate">{{ tenant()->subdomain }}.{{ config('app.base_domain') }}</span>
                    </div>
                    <button type="button"
                            @click="navigator.clipboard.writeText('{{ $storefrontUrl }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            class="shrink-0 px-2.5 py-1 rounded-lg bg-surface-container-lowest text-primary text-label-sm hover:bg-primary-container hover:text-on-primary transition-colors">
                        <span x-text="copied ? 'Tersalin!' : 'Salin Link'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
