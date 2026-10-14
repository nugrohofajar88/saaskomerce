@extends('layouts.marketing')

@section('marketing-content')
    <div class="flex flex-col w-full items-center justify-center py-10 px-4 sm:px-6 relative overflow-hidden">
        <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[680px] h-[480px] bg-gradient-to-b from-primary-fixed via-secondary-fixed/40 to-transparent blur-3xl opacity-70 pointer-events-none -z-10 rounded-full"></div>

        <div class="flex flex-col items-center mb-6 text-center">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed text-label-sm shadow-sm">
                <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                <span>Platform Dagang UMKM Indonesia</span>
            </div>
        </div>

        <div class="w-full max-w-xl bg-surface-container-lowest rounded-2xl shadow-xl shadow-primary/5 p-6 sm:p-10"
             x-data="{
                 name: '{{ old('name') }}',
                 subdomain: '{{ old('subdomain') }}',
                 subdomainTouched: {{ old('subdomain') ? 'true' : 'false' }},
                 slugify(v) { return v.toLowerCase().replace(/[^a-z0-9]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, ''); },
                 onNameInput() { if (!this.subdomainTouched) this.subdomain = this.slugify(this.name); },
                 showPassword: false, showPasswordConfirm: false,
             }">
            <div class="flex flex-col mb-8 text-center sm:text-left">
                <h1 class="text-headline-lg text-on-surface mb-2 tracking-tight">Daftarkan Toko Online Kamu</h1>
                <p class="text-body-md text-on-surface-variant">Mulai langkah digitalisasi bisnismu sekarang. Gratis dan proses cepat.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-error-container text-on-error-container text-body-sm p-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('marketing.signup.store') }}" class="flex flex-col gap-5">
                @csrf
                <div class="flex flex-col gap-1.5">
                    <label class="text-label-lg text-on-surface flex items-center justify-between" for="shop-name">
                        <span>Nama Toko</span><span class="text-label-sm text-outline">Wajib</span>
                    </label>
                    <input id="shop-name" name="name" type="text" required x-model="name" @input="onNameInput()"
                           placeholder="Contoh: Kopi Kulo Sedap / Butik Nona"
                           class="w-full h-11 px-3.5 bg-surface-container-low rounded-lg text-on-surface placeholder:text-outline focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary text-body-sm transition-all">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-label-lg text-on-surface flex items-center justify-between" for="subdomain">
                        <span>Domain Toko</span><span class="text-label-sm text-outline">Alamat Link Toko</span>
                    </label>
                    <div class="flex items-center h-11 rounded-lg bg-surface-container-low overflow-hidden focus-within:ring-2 focus-within:ring-primary focus-within:bg-surface-container-lowest transition-all">
                        <span class="pl-3.5 pr-1 text-label-md text-outline font-mono">https://</span>
                        <input id="subdomain" name="subdomain" type="text" required pattern="[a-z0-9\-]+" placeholder="tokokamu"
                               x-model="subdomain" @input="subdomainTouched = true; subdomain = slugify(subdomain)"
                               class="flex-1 h-full bg-transparent px-1 text-on-surface placeholder:text-outline text-label-lg focus:outline-none lowercase">
                        <span class="px-3 py-2 bg-surface-container-high text-on-surface-variant text-label-md font-medium">.{{ config('app.base_domain') }}</span>
                    </div>
                    <div class="mt-1 flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-tertiary-fixed/30 text-tertiary text-label-sm w-fit transition-all">
                        <span class="material-symbols-outlined text-[16px] text-tertiary">link</span>
                        <span class="font-medium">Alamat tokomu nantinya:</span>
                        <span class="font-semibold underline" x-text="(subdomain || 'tokokamu') + '.{{ config('app.base_domain') }}'"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-label-lg text-on-surface" for="owner-name">Nama Lengkap Pemilik</label>
                        <input id="owner-name" name="owner_name" type="text" required value="{{ old('owner_name') }}" placeholder="Budi Santoso"
                               class="w-full h-11 px-3.5 bg-surface-container-low rounded-lg text-on-surface placeholder:text-outline focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary text-body-sm transition-all">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-label-lg text-on-surface" for="owner-email">Email Bisnis</label>
                        <input id="owner-email" name="owner_email" type="email" required value="{{ old('owner_email') }}" placeholder="owner@tokokamu.com"
                               class="w-full h-11 px-3.5 bg-surface-container-low rounded-lg text-on-surface placeholder:text-outline focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary text-body-sm transition-all">
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-label-lg text-on-surface" for="owner-phone">Nomor WhatsApp Aktif</label>
                    <div class="flex items-center h-11 rounded-lg bg-surface-container-low overflow-hidden focus-within:ring-2 focus-within:ring-primary focus-within:bg-surface-container-lowest transition-all">
                        <div class="flex items-center gap-1 pl-3.5 pr-2.5 py-2 bg-surface-container-high/60 text-on-surface">
                            <span class="material-symbols-outlined text-[18px] text-tertiary">chat</span>
                        </div>
                        <input id="owner-phone" name="owner_phone" type="tel" value="{{ old('owner_phone') }}" placeholder="081234567890"
                               class="flex-1 h-full bg-transparent px-1 text-on-surface placeholder:text-outline text-body-sm focus:outline-none">
                    </div>
                    <p class="text-label-sm text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px] text-outline">info</span>Untuk konfirmasi pesanan dan update toko
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-label-lg text-on-surface" for="password">Kata Sandi</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password" minlength="8" required placeholder="Minimal 8 karakter"
                                   class="w-full h-11 pl-3.5 pr-10 bg-surface-container-low rounded-lg text-on-surface placeholder:text-outline focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary text-body-sm transition-all">
                            <button type="button" @click="showPassword = !showPassword" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface p-1 flex items-center justify-center rounded">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </button>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-label-lg text-on-surface" for="password_confirmation">Konfirmasi Kata Sandi</label>
                        <div class="relative">
                            <input :type="showPasswordConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" minlength="8" required placeholder="Ulangi kata sandi"
                                   class="w-full h-11 pl-3.5 pr-10 bg-surface-container-low rounded-lg text-on-surface placeholder:text-outline focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary text-body-sm transition-all">
                            <button type="button" @click="showPasswordConfirm = !showPasswordConfirm" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface p-1 flex items-center justify-center rounded">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full mt-2 h-12 bg-primary hover:bg-on-primary-fixed-variant text-on-primary text-headline-sm rounded-xl shadow-lg shadow-primary/20 flex items-center justify-center gap-2 transition-all">
                    <span>Daftar Toko Sekarang</span>
                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                </button>

                <div class="p-3.5 rounded-xl bg-surface-container flex items-start gap-3 mt-1">
                    <span class="material-symbols-outlined text-primary text-[18px] shrink-0">info</span>
                    <p class="text-label-sm text-on-surface-variant leading-relaxed">
                        Toko kamu akan ditinjau tim SaasKomerce sebelum aktif untuk menjamin keamanan ekosistem UMKM.
                    </p>
                </div>
            </form>
        </div>

        <div class="mt-8 flex items-center gap-6 text-outline text-label-sm">
            <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px] text-tertiary">support_agent</span>
                <span>Bantuan CS siap membantu</span>
            </div>
        </div>
    </div>
@endsection
