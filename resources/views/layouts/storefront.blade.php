@extends('layouts.base')

@section('content')
    @php
        $cartCount = app(\App\Support\Cart::class)->hydrated()->sum('qty');
        $hasAboutPage = app(\App\Support\SettingService::class)->hasAboutPage();
    @endphp

    @if (! app(\App\Support\SettingService::class)->storeOpen())
        <div class="bg-amber-50 text-amber-800 text-label-md px-4 py-2 text-center">
            Toko sedang tutup sementara — katalog masih bisa dilihat, tapi belum bisa menerima pesanan baru.
        </div>
    @endif

    <header class="sticky top-0 z-50 bg-surface/80 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
        <div class="max-w-5xl mx-auto px-4 h-18 py-3 flex items-center justify-between gap-4">
            <a href="{{ route('storefront.home') }}" class="flex items-center gap-2 min-w-0">
                <div class="w-9 h-9 rounded-xl bg-primary-container text-on-primary flex items-center justify-center shrink-0 shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">storefront</span>
                </div>
                <span class="text-headline-sm text-on-surface truncate">{{ tenant()?->name }}</span>
            </a>
            <nav class="flex items-center gap-1 text-label-lg">
                @if ($hasAboutPage)
                    <a href="{{ route('storefront.about') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined text-[20px]">info</span><span>Tentang</span>
                    </a>
                @endif
                <a href="{{ route('storefront.cart') }}" class="relative inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                    <span class="hidden sm:inline">Keranjang</span>
                    @if ($cartCount > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-error text-on-error text-[11px] font-bold flex items-center justify-center">{{ $cartCount }}</span>
                    @endif
                </a>
                @auth('customer')
                    <a href="{{ route('storefront.orders.index') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined text-[20px]">receipt_long</span><span>Pesanan Saya</span>
                    </a>
                    <div class="flex items-center gap-2 pl-2 ml-1 border-l border-outline-variant/30">
                        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-primary text-[16px]">person</span>
                        </div>
                        <span class="hidden md:inline text-label-md text-on-surface-variant truncate max-w-[100px]">{{ auth('customer')->user()->name }}</span>
                        <form method="POST" action="{{ route('storefront.logout') }}">
                            @csrf
                            <button type="submit" class="w-8 h-8 rounded-lg hover:bg-surface-container-low flex items-center justify-center text-on-surface-variant hover:text-error transition-colors" title="Keluar">
                                <span class="material-symbols-outlined text-[18px]">logout</span>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('storefront.login') }}" class="ml-1 inline-flex items-center justify-center px-5 py-2 rounded-lg bg-primary-container text-on-primary hover:bg-primary transition-all shadow-sm">Masuk</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-8 min-h-[60vh]">
        @yield('storefront-content')
    </main>

    <footer class="border-t border-outline-variant/30 py-8 mt-8">
        <div class="max-w-5xl mx-auto px-4 text-center text-label-sm text-on-surface-variant">
            <span>{{ tenant()?->name }} — ditenagai oleh SaasKomerce</span>
        </div>
    </footer>
@endsection
