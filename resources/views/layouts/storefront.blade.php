@extends('layouts.base')

@section('content')
    @if (! app(\App\Support\SettingService::class)->storeOpen())
        <div class="bg-amber-50 text-amber-800 text-sm px-4 py-2 text-center">
            Toko sedang tutup sementara — katalog masih bisa dilihat, tapi belum bisa menerima pesanan baru.
        </div>
    @endif

    <header class="border-b bg-white">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('storefront.home') }}" class="font-bold text-lg">{{ tenant()?->name }}</a>
            <nav class="flex items-center gap-4 text-sm">
                <a href="{{ route('storefront.cart') }}">Keranjang</a>
                @auth('customer')
                    <a href="{{ route('storefront.orders.index') }}">Pesanan Saya</a>
                    <span class="text-gray-500">{{ auth('customer')->user()->name }}</span>
                    <form method="POST" action="{{ route('storefront.logout') }}">
                        @csrf
                        <button>Keluar</button>
                    </form>
                @else
                    <a href="{{ route('storefront.login') }}">Masuk</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-8">
        @yield('storefront-content')
    </main>
@endsection
