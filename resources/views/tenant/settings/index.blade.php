@extends('layouts.tenant')

@section('page-title', 'Pengaturan')

@section('tenant-content')
    <h1 class="text-headline-lg text-on-surface tracking-tight">Pengaturan Toko</h1>

    <form method="POST" action="{{ route('tenant.settings.update') }}" class="max-w-md flex flex-col gap-3 bg-surface-container-lowest rounded-2xl shadow-sm p-6" x-data="{ storeOpen: {{ $storeOpen ? 'true' : 'false' }}, transferOn: {{ $paymentTransferEnabled ? 'true' : 'false' }} }">
        @csrf
        <input type="hidden" name="store_open" :value="storeOpen ? 1 : 0">
        <input type="hidden" name="payment_transfer_enabled" :value="transferOn ? 1 : 0">

        <div class="p-3.5 rounded-xl bg-surface-container-low flex items-start justify-between gap-3">
            <div class="flex flex-col">
                <span class="text-label-lg text-on-surface font-semibold">Toko Buka</span>
                <span class="text-label-sm text-on-surface-variant">Terima pesanan baru dari pembeli.</span>
            </div>
            <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                <input type="checkbox" x-model="storeOpen" class="sr-only peer">
                <div class="w-11 h-6 bg-surface-container-highest rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-surface-container-lowest after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-container"></div>
            </label>
        </div>

        <div class="p-3.5 rounded-xl bg-surface-container-low flex items-start justify-between gap-3">
            <div class="flex flex-col">
                <span class="text-label-lg text-on-surface font-semibold">Pembayaran Transfer Manual</span>
                <span class="text-label-sm text-on-surface-variant">Pembeli bisa checkout &amp; upload bukti transfer.</span>
            </div>
            <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                <input type="checkbox" x-model="transferOn" class="sr-only peer">
                <div class="w-11 h-6 bg-surface-container-highest rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-surface-container-lowest after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-container"></div>
            </label>
        </div>

        <button type="submit" class="inline-flex items-center justify-center gap-2 mt-2 px-5 py-2.5 rounded-lg bg-primary-container text-on-primary text-label-lg font-semibold shadow-sm hover:bg-primary transition-all">
            <span class="material-symbols-outlined text-[18px]">save</span><span>Simpan Pengaturan</span>
        </button>
    </form>
@endsection
