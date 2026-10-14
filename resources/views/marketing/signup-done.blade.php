@extends('layouts.marketing')

@section('marketing-content')
    <div class="flex flex-col w-full items-center justify-center py-10 px-4 sm:px-6 relative overflow-hidden">
        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-96 bg-primary-fixed-dim/25 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-10 left-1/4 w-80 h-80 bg-tertiary-fixed-dim/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="w-full max-w-lg mx-auto relative z-10">
            <div class="bg-surface-container-lowest rounded-2xl shadow-xl p-6 sm:p-8 flex flex-col items-center text-center">
                <div class="relative mb-5 flex items-center justify-center">
                    <div class="absolute w-24 h-24 rounded-full bg-tertiary-fixed/40 animate-ping opacity-75"></div>
                    <div class="relative w-20 h-20 bg-tertiary-fixed rounded-full flex items-center justify-center shadow-md">
                        <span class="material-symbols-outlined text-tertiary text-4xl">check_circle</span>
                    </div>
                </div>

                <div class="mb-3 inline-flex items-center gap-1.5 bg-surface-container-high px-3.5 py-1.5 rounded-full shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-secondary-container animate-pulse"></span>
                    <span class="text-label-sm font-semibold text-on-surface-variant">Status: Menunggu Tinjauan Admin</span>
                </div>

                <h1 class="text-headline-lg text-on-surface mb-2 tracking-tight">Pendaftaran Toko Berhasil Dikirim!</h1>
                <p class="text-body-sm text-on-surface-variant max-w-md mx-auto mb-6">
                    Terima kasih telah memulai langkah digitalisasi toko Anda bersama ekosistem SaasKomerce.
                </p>

                <div class="w-full bg-surface-container-low rounded-xl p-5 text-left mb-6 space-y-3.5 shadow-sm">
                    <div class="flex items-center justify-between pb-2.5 border-b border-surface-container">
                        <span class="text-label-md font-bold uppercase tracking-wider text-outline">Rincian Registrasi</span>
                        <span class="text-label-sm font-semibold text-primary bg-primary-fixed px-2 py-0.5 rounded-md">Baru</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                        <span class="text-body-sm text-outline flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm">storefront</span>Nama Toko
                        </span>
                        <span class="text-label-lg font-semibold text-on-surface">{{ $tenant->name }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                        <span class="text-body-sm text-outline flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm">language</span>Subdomain Toko
                        </span>
                        <span class="text-label-md font-semibold text-primary bg-surface-container-lowest px-2.5 py-1 rounded-lg">{{ $tenant->subdomain }}.{{ config('app.base_domain') }}</span>
                    </div>
                    @if ($tenant->owner_phone)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                            <span class="text-body-sm text-outline flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm">chat</span>Nomor WhatsApp
                            </span>
                            <span class="text-label-lg font-semibold text-on-surface">{{ $tenant->owner_phone }}</span>
                        </div>
                    @endif
                </div>

                <div class="w-full text-left space-y-3 mb-8 bg-surface-container-lowest rounded-xl">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 w-6 h-6 rounded-full bg-primary-fixed flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-primary text-sm">schedule</span>
                        </div>
                        <p class="text-body-sm text-on-surface">
                            <strong class="text-label-lg text-on-surface">Proses Verifikasi:</strong> Tim SaasKomerce sedang meninjau kelengkapan toko Anda sebelum diaktifkan.
                        </p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 w-6 h-6 rounded-full bg-tertiary-fixed flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-tertiary text-sm">mark_email_read</span>
                        </div>
                        <p class="text-body-sm text-on-surface">
                            <strong class="text-label-lg text-on-surface">Login Dashboard:</strong> Setelah disetujui, login di <span class="font-mono">{{ $tenant->subdomain }}.{{ config('app.base_domain') }}/admin/login</span> pakai email &amp; password yang baru kamu daftarkan.
                        </p>
                    </div>
                </div>

                <div class="w-full flex flex-col gap-3">
                    <a href="{{ route('marketing.home') }}" class="w-full bg-transparent hover:bg-surface-container text-on-surface-variant hover:text-primary text-label-lg py-2.5 px-4 rounded-xl transition-colors flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-base">arrow_back</span><span>Kembali ke Beranda</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
