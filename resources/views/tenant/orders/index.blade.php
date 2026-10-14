@extends('layouts.tenant')

@section('tenant-content')
    <h1 class="text-xl font-bold mb-4">Pesanan</h1>

    <div class="flex gap-2 mb-4 text-sm">
        @foreach (['' => 'Semua', 'pending_payment' => 'Menunggu Bayar', 'paid' => 'Lunas', 'cancelled' => 'Batal', 'expired' => 'Kedaluwarsa'] as $value => $label)
            <a href="{{ route('tenant.orders.index', array_filter(['status' => $value])) }}"
               class="px-3 py-1.5 rounded-full border {{ $status === $value || ($status === null && $value === '') ? 'bg-gray-900 text-white' : '' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm divide-y">
        @forelse ($orders as $order)
            <a href="{{ route('tenant.orders.show', $order) }}" class="p-4 flex items-center justify-between hover:bg-gray-50">
                <div>
                    <div class="font-semibold">
                        {{ $order->order_number }}
                        @if ($order->payment_proof_path)
                            <span class="ml-2 text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">Ada bukti bayar</span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-500">{{ $order->customer_name }} · Rp{{ number_format($order->total, 0, ',', '.') }} · {{ $order->status }}</div>
                </div>
                <span class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</span>
            </a>
        @empty
            <div class="p-4 text-gray-500 text-sm">Belum ada pesanan.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
@endsection
