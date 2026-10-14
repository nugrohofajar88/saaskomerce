@extends('layouts.base')

@section('content')
    <header class="fixed top-0 w-full z-50 bg-surface/80 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
        <div class="h-20 max-w-7xl mx-auto px-4 lg:px-8 flex items-center justify-between gap-4">
            <a href="{{ route('marketing.home') }}" class="flex items-center gap-2 shrink-0">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-container to-secondary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-on-primary text-[20px]">storefront</span>
                </div>
                <span class="text-headline-md text-on-surface font-bold tracking-tight">SaasKomerce</span>
            </a>
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('marketing.home') }}#kenapa-kami" class="text-label-lg text-on-surface-variant hover:text-on-surface transition-colors">Kenapa Kami</a>
                <a href="{{ route('marketing.home') }}#fitur" class="text-label-lg text-on-surface-variant hover:text-on-surface transition-colors">Fitur</a>
                <a href="{{ route('marketing.home') }}#cara-kerja" class="text-label-lg text-on-surface-variant hover:text-on-surface transition-colors">Cara Kerja</a>
            </nav>
            <div class="flex items-center gap-4">
                <a href="{{ route('marketing.signup') }}"
                   class="inline-flex items-center justify-center text-label-lg text-on-primary bg-primary-container hover:bg-primary px-6 py-2.5 rounded-xl transition-all shadow-sm">
                    Daftar Toko Gratis
                </a>
            </div>
        </div>
    </header>

    <main class="w-full pt-20 bg-background min-h-screen">
        @yield('marketing-content')

        <footer class="border-t border-outline-variant/30 py-10">
            <div class="max-w-7xl mx-auto px-4 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-label-sm text-on-surface-variant">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-primary-container to-secondary flex items-center justify-center">
                        <span class="material-symbols-outlined text-on-primary text-[16px]">storefront</span>
                    </div>
                    <span class="font-semibold text-on-surface">SaasKomerce</span>
                </div>
                <span>&copy; {{ now()->year }} SaasKomerce. Dibuat untuk UMKM Indonesia.</span>
            </div>
        </footer>
    </main>
@endsection
