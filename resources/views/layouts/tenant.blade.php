@extends('layouts.base')

@section('content')
    <div class="flex min-h-screen">
        <aside class="w-56 shrink-0 bg-gray-900 text-gray-200 p-4 space-y-1">
            <div class="font-bold text-white mb-4">{{ tenant()?->name }}</div>
            @php
                $links = [
                    'tenant.dashboard' => 'Dashboard',
                    'tenant.categories.index' => 'Kategori',
                    'tenant.products.index' => 'Produk',
                    'tenant.orders.index' => 'Pesanan',
                    'tenant.payment-accounts.index' => 'Rekening',
                    'tenant.settings.index' => 'Pengaturan',
                ];
            @endphp
            @foreach ($links as $route => $label)
                <a href="{{ route($route) }}"
                   class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs($route.'*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-800' }}">
                    {{ $label }}
                </a>
            @endforeach
            <form method="POST" action="{{ route('tenant.logout') }}" class="pt-4">
                @csrf
                <button class="block w-full text-left rounded-lg px-3 py-2 text-sm text-gray-400 hover:bg-gray-800">Keluar</button>
            </form>
        </aside>

        <main class="flex-1 p-8">
            @yield('tenant-content')
        </main>
    </div>
@endsection
