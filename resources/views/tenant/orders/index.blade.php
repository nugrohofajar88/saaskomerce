@extends('layouts.tenant')

@section('page-title', 'Pesanan')

@section('tenant-content')
    @php
        $tabs = [
            '' => ['label' => 'Semua', 'count' => $statusCounts->sum(), 'icon' => 'receipt_long'],
            'pending_payment' => ['label' => 'Menunggu Bayar', 'count' => $statusCounts->get('pending_payment', 0), 'icon' => 'pending'],
            'paid' => ['label' => 'Lunas', 'count' => $statusCounts->get('paid', 0), 'icon' => 'task_alt'],
            'expired' => ['label' => 'Kedaluwarsa', 'count' => $statusCounts->get('expired', 0), 'icon' => 'cancel'],
            'cancelled' => ['label' => 'Dibatalkan', 'count' => $statusCounts->get('cancelled', 0), 'icon' => 'cancel'],
        ];
    @endphp

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex flex-col gap-1">
            <span class="text-headline-xl text-on-surface">Daftar Pesanan</span>
            <p class="text-body-md text-on-surface-variant">Pantau transaksi masuk &amp; verifikasi bukti transfer manual.</p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        @foreach ($tabs as $value => $tab)
            <a href="{{ route('tenant.orders.index', array_filter(['status' => $value, 'search' => $search])) }}"
               class="flex flex-col justify-between p-4 rounded-2xl bg-surface-container-lowest shadow-sm hover:shadow-md transition-all {{ $status === $value || ($status === null && $value === '') ? 'ring-2 ring-primary' : '' }}">
                <div class="flex items-center justify-between">
                    <span class="text-label-md text-on-surface-variant">{{ $tab['label'] }}</span>
                    <span class="material-symbols-outlined text-[20px] text-on-surface-variant">{{ $tab['icon'] }}</span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-headline-lg text-on-surface">{{ $tab['count'] }}</span>
                </div>
            </a>
        @endforeach
    </div>

    <div class="bg-surface-container-lowest rounded-2xl p-4 shadow-sm">
        <form method="GET" class="relative">
            @if ($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-on-surface-variant">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. Pesanan, nama pembeli, atau no. WhatsApp..."
                   class="w-full h-11 pl-11 pr-4 bg-surface-container-low rounded-xl text-on-surface text-body-sm placeholder:text-on-surface-variant focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary transition-all">
        </form>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant text-label-md uppercase tracking-wider">
                        <th class="py-3.5 px-4" scope="col">No. Pesanan</th>
                        <th class="py-3.5 px-4" scope="col">Pelanggan</th>
                        <th class="py-3.5 px-4" scope="col">Item</th>
                        <th class="py-3.5 px-4" scope="col">Total</th>
                        <th class="py-3.5 px-4" scope="col">Bukti Transfer</th>
                        <th class="py-3.5 px-4" scope="col">Status</th>
                        <th class="py-3.5 pr-6 pl-4 text-right" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-high/40">
                    @forelse ($orders as $order)
                        @php
                            $hasProof = (bool) $order->payment_proof_path;
                            $statusMeta = match ($order->status) {
                                'paid' => ['bg-tertiary-fixed text-on-tertiary-fixed', 'Lunas'],
                                'pending_payment' => [$hasProof ? 'bg-primary-fixed text-on-primary-fixed' : 'bg-secondary-fixed text-on-secondary-fixed-variant', 'Menunggu Konfirmasi'],
                                'expired' => ['bg-error-container text-on-error-container', 'Kedaluwarsa'],
                                default => ['bg-error-container text-on-error-container', 'Dibatalkan'],
                            };
                        @endphp
                        <tr class="hover:bg-surface-container-low/60 transition-colors {{ $order->status === 'pending_payment' && $hasProof ? 'bg-primary-fixed/20' : '' }}">
                            <td class="py-4 px-4 align-top">
                                <div class="flex flex-col gap-1">
                                    <span class="text-label-lg font-bold text-on-surface">{{ $order->order_number }}</span>
                                    <span class="text-label-sm text-on-surface-variant">{{ $order->created_at->translatedFormat('d M, H:i') }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 align-top">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-label-lg font-semibold text-on-surface">{{ $order->customer_name }}</span>
                                    <span class="text-body-sm text-on-surface-variant flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[15px] text-tertiary">chat</span>{{ $order->customer_phone }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-4 align-top max-w-[220px]">
                                <div class="flex flex-col gap-1">
                                    @foreach ($order->items->take(2) as $item)
                                        <div class="flex items-center gap-2 text-on-surface">
                                            <span class="w-5 h-5 rounded bg-surface-container flex items-center justify-center text-label-sm font-semibold shrink-0">{{ $item->qty }}x</span>
                                            <span class="text-body-sm truncate">{{ $item->product_name }}</span>
                                        </div>
                                    @endforeach
                                    @if ($order->items->count() > 2)
                                        <span class="text-label-sm text-on-surface-variant">+{{ $order->items->count() - 2 }} item lain</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-4 align-top">
                                <span class="text-price-md text-on-surface font-bold">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                                <span class="block text-label-sm text-on-surface-variant">{{ $order->payment_method === 'transfer' ? 'Transfer Manual' : $order->payment_method }}</span>
                            </td>
                            <td class="py-4 px-4 align-top">
                                @if ($hasProof)
                                    <a href="{{ asset('storage/'.$order->payment_proof_path) }}" target="_blank" class="inline-flex items-center gap-1 text-label-sm font-semibold text-primary">
                                        <span class="material-symbols-outlined text-[14px]">receipt_long</span>Ada Bukti
                                    </a>
                                @else
                                    <span class="text-label-sm text-on-surface-variant italic">Belum upload</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 align-top">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-label-sm font-semibold {{ $statusMeta[0] }}">{{ $statusMeta[1] }}</span>
                            </td>
                            <td class="py-4 pr-6 pl-4 text-right align-top">
                                <a href="{{ route('tenant.orders.show', $order) }}" class="px-3 py-1.5 rounded-lg bg-surface-container-low text-on-surface text-label-sm hover:bg-surface-container-high transition-colors">
                                    {{ $order->status === 'pending_payment' && $hasProof ? 'Periksa Bukti' : 'Detail' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-10 text-center text-on-surface-variant">Belum ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $orders->links() }}</div>
@endsection
