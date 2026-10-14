@extends('layouts.storefront')

@section('storefront-content')
    <h1 class="text-xl font-bold mb-6">Pesanan Saya</h1>

    @if ($orders->isEmpty())
        <p class="text-gray-500">Belum ada pesanan. <a href="{{ route('storefront.home') }}" class="text-blue-600">Mulai belanja</a></p>
    @else
        <div class="bg-white rounded-xl shadow-sm divide-y">
            @foreach ($orders as $order)
                <a href="{{ route('storefront.order.show', $order) }}" class="p-4 flex items-center justify-between hover:bg-gray-50">
                    <div>
                        <div class="font-semibold">{{ $order->order_number }}</div>
                        <div class="text-sm text-gray-500">{{ $order->created_at->translatedFormat('d M Y H:i') }} · Rp{{ number_format($order->total, 0, ',', '.') }}</div>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100">{{ $order->status }}</span>
                </a>
            @endforeach
        </div>

        <div class="mt-4">{{ $orders->links() }}</div>
    @endif
@endsection
