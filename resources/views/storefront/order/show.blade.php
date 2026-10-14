@extends('layouts.storefront')

@section('storefront-content')
    <div class="max-w-xl mx-auto">
        <h1 class="text-xl font-bold mb-1">Pesanan {{ $order->order_number }}</h1>
        <p class="text-sm text-gray-500 mb-6">Status: {{ $order->status }}</p>

        <div class="bg-white rounded-xl shadow-sm divide-y">
            @foreach ($order->items as $item)
                <div class="p-4 flex justify-between text-sm">
                    <span>{{ $item->qty }}x {{ $item->product_name }} ({{ $item->variant_label }})</span>
                    <span>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
            <div class="p-4 flex justify-between font-bold">
                <span>Total</span>
                <span>Rp{{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        @if ($order->status === 'pending_payment')
            <div class="mt-6 bg-white rounded-xl shadow-sm p-6">
                <h2 class="font-semibold mb-2">Transfer ke salah satu rekening</h2>
                @forelse ($paymentAccounts as $account)
                    <p class="text-sm mb-1">{{ $account->bank_name }} — {{ $account->account_number }} a.n. {{ $account->account_holder }}</p>
                @empty
                    <p class="text-sm text-gray-500">Hubungi toko untuk info pembayaran.</p>
                @endforelse

                <h2 class="font-semibold mt-6 mb-2">Upload Bukti Transfer</h2>
                @if ($order->payment_proof_path)
                    <p class="text-sm text-green-700 mb-2">Bukti sudah terkirim, menunggu konfirmasi admin.</p>
                    <img src="{{ asset('storage/'.$order->payment_proof_path) }}" class="max-w-xs rounded-lg border">
                @else
                    <form method="POST" action="{{ route('storefront.order.upload-proof', $order) }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <input type="file" name="proof" accept="image/*" required class="text-sm">
                        <button type="submit" class="rounded-lg bg-gray-900 text-white px-4 py-2 text-sm font-semibold">Kirim Bukti</button>
                    </form>
                @endif
            </div>
        @endif
    </div>
@endsection
