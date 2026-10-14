@extends('layouts.marketing')

@section('marketing-content')
    <div class="relative w-full overflow-hidden">
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[720px] h-[480px] bg-primary/10 rounded-full blur-[120px] pointer-events-none -z-10"></div>
        <div class="absolute top-64 right-10 w-96 h-96 bg-secondary-container/10 rounded-full blur-[100px] pointer-events-none -z-10"></div>

        <section class="max-w-7xl mx-auto px-4 lg:px-8 pt-8 pb-16 lg:pt-16 lg:pb-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                <div class="lg:col-span-6 flex flex-col items-start space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-fixed/60 text-primary">
                        <span class="material-symbols-outlined text-sm">storefront</span>
                        <span class="text-label-sm font-semibold tracking-wider uppercase">Platform Toko Online untuk UMKM</span>
                    </div>
                    <h1 class="text-headline-xl text-on-surface tracking-tight">
                        Bikin toko online kamu sendiri, <span class="text-primary-container">dalam hitungan menit</span>
                    </h1>
                    <p class="text-body-lg text-on-surface-variant max-w-xl">
                        Solusi e-commerce praktis khusus pengusaha dan UMKM Indonesia. Kelola katalog, terima pesanan, dan verifikasi pembayaran transfer bank tanpa ribet teknis.
                    </p>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full sm:w-auto pt-2">
                        <a href="{{ route('marketing.signup') }}"
                           class="inline-flex items-center justify-center gap-2 text-label-lg text-on-primary bg-primary-container hover:bg-primary px-8 py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                            <span>Daftar Toko Gratis</span>
                            <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </a>
                    </div>
                    <div class="pt-6 w-full">
                        <div class="p-4 rounded-xl bg-surface-container-low/70 flex flex-wrap items-center gap-y-2 gap-x-5 text-on-surface-variant">
                            <div class="flex items-center gap-1.5 text-label-md">
                                <span class="material-symbols-outlined text-tertiary text-base">check_circle</span>
                                <span>Tanpa biaya bulanan tersembunyi</span>
                            </div>
                            <span class="text-outline-variant hidden sm:inline">•</span>
                            <div class="flex items-center gap-1.5 text-label-md">
                                <span class="material-symbols-outlined text-tertiary text-base">check_circle</span>
                                <span>Siap pakai dalam 5 menit</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6 relative flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-md lg:max-w-lg">
                        <div class="w-full bg-surface-container-lowest rounded-3xl shadow-xl overflow-hidden p-6 space-y-5">
                            <div class="flex items-center justify-between pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-surface-container-high flex items-center justify-center">
                                        <span class="material-symbols-outlined text-primary text-[22px]">storefront</span>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-headline-md text-on-surface">Kopi Senja Nusantara</h3>
                                            <span class="px-2 py-0.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant text-label-sm font-semibold">Toko Aktif</span>
                                        </div>
                                        <p class="text-label-md text-primary font-medium">kopisenja.saaskomerce.com</p>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 p-3.5 rounded-2xl bg-surface-container-low">
                                <div>
                                    <p class="text-label-sm text-on-surface-variant">Penjualan Hari Ini</p>
                                    <p class="text-price-md text-primary">Rp1.420.000</p>
                                </div>
                                <div>
                                    <p class="text-label-sm text-on-surface-variant">Pesanan Selesai</p>
                                    <p class="text-price-md text-on-surface">24 Pesanan</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <h4 class="text-label-lg font-bold text-on-surface">Katalog Unggulan</h4>
                                @foreach ([['Cold Brew Gayo 250ml', '18 botol', 'Rp28.000'], ['Biji Kopi Toraja Single Origin', '32 bungkus', 'Rp85.000']] as [$name, $stock, $price])
                                    <div class="flex items-center gap-3.5 p-3 rounded-2xl bg-surface-container-low/60">
                                        <div class="w-14 h-14 rounded-xl shrink-0 bg-surface-container flex items-center justify-center">
                                            <span class="material-symbols-outlined text-outline text-[22px]">coffee</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-headline-sm text-on-surface truncate">{{ $name }}</p>
                                            <p class="text-body-sm text-on-surface-variant">Stok: {{ $stock }}</p>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <p class="text-price-md text-on-surface">{{ $price }}</p>
                                            <span class="text-label-sm text-tertiary font-semibold">Tersedia</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="absolute -bottom-4 -right-4 bg-surface-container-lowest p-3 rounded-2xl shadow-xl flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-tertiary animate-pulse"></span>
                            <span class="text-label-sm text-on-surface font-semibold">Online • Siap Terima Order</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <section class="w-full bg-surface-container-low/60 py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
                <span class="text-label-sm uppercase tracking-wider text-primary font-bold">Fitur Unggulan UMKM</span>
                <h2 class="text-headline-xl text-on-surface tracking-tight">Semua kebutuhan jualan online, tanpa pusing coding</h2>
                <p class="text-body-md text-on-surface-variant">Dirancang khusus agar pengusaha lokal bisa fokus bikin produk dan melayani pembeli.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ([
                    ['inventory_2', 'bg-primary-fixed/70 text-primary', 'Katalog & Varian Produk', 'Kelola stok, ukuran, varian rasa, dan galeri foto dengan mudah dari satu dashboard.'],
                    ['receipt_long', 'bg-tertiary-fixed/70 text-tertiary', 'Pesanan Masuk Otomatis', 'Checkout cepat dengan upload bukti transfer langsung, tanpa perlu hitung manual.'],
                    ['phone_iphone', 'bg-secondary-fixed/70 text-secondary', 'Kelola dari HP', 'Dashboard responsif dan ringan, cek omzet dan proses pesanan langsung dari ponsel.'],
                ] as [$icon, $iconStyle, $title, $desc])
                    <div class="bg-surface-container-lowest p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300">
                        <div class="w-14 h-14 rounded-2xl {{ $iconStyle }} flex items-center justify-center mb-5">
                            <span class="material-symbols-outlined text-3xl">{{ $icon }}</span>
                        </div>
                        <h3 class="text-headline-md text-on-surface mb-2">{{ $title }}</h3>
                        <p class="text-body-md text-on-surface-variant leading-relaxed">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 lg:px-8 py-16 lg:py-24">
        <div class="text-center max-w-xl mx-auto mb-16 space-y-3">
            <span class="text-label-sm uppercase tracking-wider text-primary font-bold">Langkah Mudah</span>
            <h2 class="text-headline-xl text-on-surface tracking-tight">Cara Kerja SaasKomerce</h2>
            <p class="text-body-md text-on-surface-variant">Tiga langkah menuju toko online milik usahamu sendiri.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach ([
                ['1', 'Daftar Toko', 'Isi data usaha & tentukan subdomain toko kamu, mis. namatoko.saaskomerce.com.', 'timer', 'Hanya 2 menit pengisian form'],
                ['2', 'Verifikasi Cepat', 'Admin meninjau data tokomu untuk menjaga ekosistem perdagangan yang tepercaya.', 'verified_user', 'Pemeriksaan aman'],
                ['3', 'Toko Kamu Live!', 'Siap bagikan link tokomu ke Instagram, TikTok, & grup WhatsApp pelanggan.', 'rocket_launch', 'Langsung bisa terima order'],
            ] as [$num, $title, $desc, $icon, $hint])
                <div class="bg-surface-container-lowest p-8 rounded-3xl shadow-sm flex flex-col items-start space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary text-on-primary flex items-center justify-center text-headline-md shadow-md">{{ $num }}</div>
                    <div class="space-y-2">
                        <h3 class="text-headline-md text-on-surface">{{ $num }}. {{ $title }}</h3>
                        <p class="text-body-md text-on-surface-variant">{{ $desc }}</p>
                    </div>
                    <div class="pt-2 flex items-center gap-2 text-on-surface-variant text-label-sm">
                        <span class="material-symbols-outlined text-primary text-base">{{ $icon }}</span>
                        <span>{{ $hint }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 lg:px-8 pb-20 lg:pb-28">
        <div class="relative rounded-3xl bg-gradient-to-br from-primary-container via-primary to-secondary p-8 sm:p-12 lg:p-16 text-center text-on-primary shadow-2xl overflow-hidden">
            <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
            <div class="relative max-w-3xl mx-auto space-y-6">
                <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-white/15 backdrop-blur-md">
                    <span class="material-symbols-outlined text-sm">stars</span>
                    <span class="text-label-sm font-semibold tracking-wide uppercase">Dukungan Nyata untuk Usaha Lokal</span>
                </div>
                <h2 class="text-headline-xl tracking-tight">Mulai Gratis Sekarang</h2>
                <p class="text-body-lg text-on-primary-container max-w-xl mx-auto">
                    Dukung pertumbuhan UMKM Indonesia. Dapatkan akses penuh ke fitur toko online tanpa biaya langganan awal.
                </p>
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('marketing.signup') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-label-lg text-primary bg-surface-container-lowest hover:bg-surface-container-low px-8 py-4 rounded-xl font-bold shadow-lg transition-all hover:scale-105">
                        <span>Daftar Toko Sekarang — Gratis</span>
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </a>
                </div>
                <p class="text-label-sm text-on-primary-container/80 pt-2">Pendaftaran dibuka untuk seluruh pemilik bisnis di Indonesia.</p>
            </div>
        </div>
    </section>
@endsection
