@extends('layouts.tenant')

@section('tenant-content')
    <a href="{{ route('tenant.orders.index') }}" class="text-sm text-gray-500">&larr; Kembali</a>

    <div class="bg-white rounded-xl shadow-sm p-6 max-w-xl mt-3">
        <div class="flex items-start justify-between">
            <h1 class="text-lg font-bold">{{ $order->order_number }}</h1>
            <span class="text-sm px-2 py-0.5 rounded-full bg-gray-100">{{ $order->status }}</span>
        </div>
        <p class="text-sm text-gray-500 mt-1">{{ $order->customer_name }} · {{ $order->customer_phone }} · {{ $order->customer_email }}</p>
        @if ($order->shipping_address)
            <p class="text-sm text-gray-500">Alamat: {{ $order->shipping_address }}</p>
        @endif

        <div class="mt-4 border-t pt-3 space-y-2">
            @foreach ($order->items as $item)
                <div class="flex justify-between text-sm">
                    <span>{{ $item->qty }}x {{ $item->product_name }} ({{ $item->variant_label }})</span>
                    <span>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="mt-4 border-t pt-3 flex justify-between font-bold">
            <span>Total</span>
            <span>Rp{{ number_format($order->total, 0, ',', '.') }}</span>
        </div>

        @if ($order->payment_proof_path)
            <div class="mt-4 border-t pt-3">
                <div class="text-sm font-medium mb-2">Bukti Transfer</div>
                <a href="{{ asset('storage/'.$order->payment_proof_path) }}" target="_blank">
                    <img src="{{ asset('storage/'.$order->payment_proof_path) }}" class="max-w-xs rounded-lg border">
                </a>
            </div>
        @endif

        @if ($order->status === 'pending_payment')
            <form method="POST" action="{{ route('tenant.orders.mark-paid', $order) }}" class="mt-4">
                @csrf
                <button class="w-full rounded-lg bg-green-600 text-white px-4 py-2.5 font-semibold">Tandai Lunas</button>
            </form>
        @endif
    </div>
@endsection
