@extends('layouts.storefront')

@section('storefront-content')
    <h1 class="text-xl font-bold mb-6">Checkout</h1>

    <div class="grid md:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl shadow-sm divide-y">
            @foreach ($items as $item)
                <div class="p-4 flex justify-between text-sm">
                    <span>{{ $item['qty'] }}x {{ $item['variant']->product->name }} ({{ $item['variant']->label }})</span>
                    <span>Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                </div>
            @endforeach
            <div class="p-4 flex justify-between font-bold">
                <span>Total</span>
                <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div>
            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 text-red-700 text-sm p-3">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('storefront.checkout.store') }}" class="space-y-4 bg-white rounded-xl shadow-sm p-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Penerima</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="w-full rounded-lg border px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">No. WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full rounded-lg border px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Alamat Pengiriman</label>
                    <textarea name="address" rows="3" class="w-full rounded-lg border px-3 py-2">{{ old('address') }}</textarea>
                </div>
                <button type="submit" class="w-full rounded-lg bg-gray-900 text-white px-4 py-2.5 font-semibold">Buat Pesanan</button>
            </form>
        </div>
    </div>
@endsection
