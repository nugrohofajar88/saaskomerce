@extends('layouts.storefront')

@section('storefront-content')
    <a href="{{ route('storefront.home') }}" class="text-sm text-gray-500">&larr; Kembali</a>

    <div class="grid md:grid-cols-2 gap-8 mt-4">
        <div class="aspect-square bg-gray-100 rounded-xl overflow-hidden">
            @if ($product->images->isNotEmpty())
                <img src="{{ $product->images->first()->url }}" class="w-full h-full object-cover">
            @endif
        </div>

        <div>
            <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
            @if ($product->description)
                <p class="text-gray-600 mt-2">{{ $product->description }}</p>
            @endif

            @if ($product->variants->isEmpty())
                <p class="mt-6 text-red-600">Stok habis.</p>
            @else
                <form method="POST" action="{{ route('storefront.cart.store') }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">Varian</label>
                        <select name="variant_id" class="w-full rounded-lg border px-3 py-2">
                            @foreach ($product->variants as $variant)
                                <option value="{{ $variant->id }}" @disabled($variant->stock <= 0)>
                                    {{ $variant->label }} — Rp{{ number_format($variant->price, 0, ',', '.') }}
                                    @if ($variant->stock <= 0) (habis) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Jumlah</label>
                        <input type="number" name="qty" value="1" min="1" class="w-24 rounded-lg border px-3 py-2">
                    </div>
                    <button type="submit" class="rounded-lg bg-gray-900 text-white px-5 py-2.5 font-semibold">Tambah ke Keranjang</button>
                </form>
            @endif
        </div>
    </div>
@endsection
