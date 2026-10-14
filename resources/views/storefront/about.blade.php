@extends('layouts.storefront')

@section('storefront-content')
    <div class="max-w-2xl mx-auto flex flex-col gap-8">
        <div class="text-center">
            <h1 class="text-headline-xl text-on-surface tracking-tight">Tentang {{ tenant()->name }}</h1>
        </div>

        @if ($aboutContent)
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm p-6 sm:p-8">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[18px]">storefront</span>
                    </div>
                    <h2 class="text-headline-sm text-on-surface">Tentang Toko Kami</h2>
                </div>
                <p class="text-body-md text-on-surface-variant leading-relaxed whitespace-pre-line">{{ $aboutContent }}</p>
            </div>
        @endif

        @if ($whyUsContent)
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm p-6 sm:p-8">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-tertiary-fixed flex items-center justify-center text-tertiary">
                        <span class="material-symbols-outlined text-[18px]">favorite</span>
                    </div>
                    <h2 class="text-headline-sm text-on-surface">Kenapa Pilih Kami</h2>
                </div>
                <p class="text-body-md text-on-surface-variant leading-relaxed whitespace-pre-line">{{ $whyUsContent }}</p>
            </div>
        @endif

        <a href="{{ route('storefront.home') }}" class="self-center inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-container text-on-primary text-label-lg font-semibold shadow-sm hover:bg-primary transition-all">
            <span>Lihat Katalog</span><span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </a>
    </div>
@endsection
