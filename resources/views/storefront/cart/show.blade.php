@extends('layouts.storefront')

@section('storefront-content')
    <h1 class="text-xl font-bold mb-6">Keranjang</h1>

    @if ($items->isEmpty())
        <p class="text-gray-500">Keranjang masih kosong. <a href="{{ route('storefront.home') }}" class="text-blue-600">Belanja sekarang</a></p>
    @else
        <div class="bg-white rounded-xl shadow-sm divide-y">
            @foreach ($items as $item)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <div class="font-medium">{{ $item['variant']->product->name }}</div>
                        <div class="text-sm text-gray-500">{{ $item['variant']->label }} · Rp{{ number_format($item['variant']->price, 0, ',', '.') }}</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <form method="POST" action="{{ route('storefront.cart.update', $item['variant']->id) }}" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <input type="number" name="qty" value="{{ $item['qty'] }}" min="0" max="{{ $item['variant']->stock }}" class="w-16 rounded-lg border px-2 py-1 text-sm">
                            <button type="submit" class="text-sm text-blue-600">Update</button>
                        </form>
                        <form method="POST" action="{{ route('storefront.cart.destroy', $item['variant']->id) }}">
                            @csrf @method('DELETE')
                            <button class="text-sm text-red-600">Hapus</button>
                        </form>
                        <span class="font-medium w-24 text-right">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 flex items-center justify-between">
            <span class="text-lg font-bold">Total: Rp{{ number_format($total, 0, ',', '.') }}</span>
            <a href="{{ route('storefront.checkout') }}" class="rounded-lg bg-gray-900 text-white px-5 py-2.5 font-semibold">Checkout</a>
        </div>
    @endif
@endsection
