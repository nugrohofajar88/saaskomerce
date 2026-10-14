@extends('layouts.base')

@section('content')
    @php
        $navItems = [
            ['route' => 'tenant.dashboard', 'icon' => 'dashboard', 'label' => 'Dashboard'],
            ['route' => 'tenant.categories.index', 'icon' => 'category', 'label' => 'Kategori'],
            ['route' => 'tenant.products.index', 'icon' => 'inventory_2', 'label' => 'Produk'],
            ['route' => 'tenant.orders.index', 'icon' => 'shopping_bag', 'label' => 'Pesanan'],
            ['route' => 'tenant.payment-accounts.index', 'icon' => 'account_balance_wallet', 'label' => 'Rekening Pembayaran'],
            ['route' => 'tenant.settings.index', 'icon' => 'settings', 'label' => 'Pengaturan'],
        ];
        $storeOpen = app(\App\Support\SettingService::class)->storeOpen();
        $storefrontUrl = 'http://'.tenant()->subdomain.'.'.config('app.base_domain');
    @endphp

    <aside class="fixed left-0 top-0 h-full w-72 bg-surface-container-lowest shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-50 flex flex-col justify-between">
        <div class="flex flex-col">
            <div class="p-4 pb-2">
                <div class="flex items-center gap-2 p-2 rounded-xl bg-surface-container-low">
                    <div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shrink-0 shadow-sm">
                        <span class="material-symbols-outlined text-[22px]">storefront</span>
                    </div>
                    <div class="flex flex-col min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-1">
                            <span class="text-label-lg text-on-surface truncate">{{ tenant()->name }}</span>
                            @if ($storeOpen)
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-label-sm bg-tertiary-fixed text-on-tertiary-fixed shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-tertiary-container animate-pulse"></span>Online
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-label-sm bg-error-container text-on-error-container shrink-0">Tutup</span>
                            @endif
                        </div>
                        <span class="text-label-sm text-on-surface-variant truncate">{{ tenant()->subdomain }}.{{ config('app.base_domain') }}</span>
                    </div>
                </div>
                <div class="mt-2 px-1 flex items-center justify-between">
                    <span class="text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Menu Toko</span>
                </div>
            </div>
            <nav class="flex flex-col gap-1 px-4">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all text-label-lg {{ request()->routeIs($item['route'].'*') ? 'bg-primary-container text-on-primary font-semibold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                        <span class="material-symbols-outlined text-[20px]">{{ $item['icon'] }}</span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>
        <div class="p-4 flex flex-col gap-1">
            <div class="p-2 rounded-lg bg-surface-container-low flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">verified</span>
                    <div class="flex flex-col">
                        <span class="text-label-sm font-semibold text-on-surface">Paket {{ ucfirst(tenant()->plan) }}</span>
                        <span class="text-label-sm text-on-surface-variant capitalize">Status: {{ tenant()->status }}</span>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('tenant.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg text-on-surface-variant hover:bg-error-container hover:text-on-error-container transition-all text-label-lg">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    <span>Keluar Toko</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="pl-72">
        <header class="fixed top-0 left-72 right-0 h-16 bg-surface/80 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-40 flex items-center justify-between px-8">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 text-label-lg text-on-surface-variant">
                    <span class="material-symbols-outlined text-[18px]">home</span>
                    <span>/</span>
                    <span class="text-on-surface font-semibold">@yield('page-title', 'Admin Toko')</span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ $storefrontUrl }}" target="_blank" class="flex items-center gap-1.5 px-4 py-1.5 rounded-lg bg-surface-container-low text-primary hover:bg-primary-container hover:text-on-primary transition-colors text-label-md font-semibold">
                    <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                    <span>Lihat Toko Online</span>
                </a>
                <div class="h-8 w-px bg-outline-variant/30"></div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
                    </div>
                    <div class="flex-col text-left hidden sm:flex">
                        <span class="text-label-md font-semibold text-on-surface leading-tight">{{ auth('tenant_admin')->user()->name }}</span>
                        <span class="text-label-sm text-on-surface-variant leading-tight">Pemilik Toko</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="relative w-full pt-16 bg-background min-h-screen">
            <div class="px-8 py-6 flex flex-col gap-6 max-w-[1440px] w-full mx-auto">
                @yield('tenant-content')
            </div>
        </main>
    </div>
@endsection
