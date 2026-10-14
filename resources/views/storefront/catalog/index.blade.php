@extends('layouts.storefront')

@section('storefront-content')
    <h1 class="text-2xl font-bold mb-6">{{ tenant()->name }}</h1>

    @if ($products->isEmpty())
        <p class="text-gray-500">Belum ada produk tersedia.</p>
    @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($products as $product)
                <a href="{{ route('storefront.product', $product) }}" class="block bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="aspect-square bg-gray-100">
                        @if ($product->images->isNotEmpty())
                            <img src="{{ $product->images->first()->url }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="p-3">
                        <div class="font-medium text-sm truncate">{{ $product->name }}</div>
                        <div class="text-sm text-gray-600">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
