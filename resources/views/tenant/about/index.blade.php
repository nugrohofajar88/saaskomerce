@extends('layouts.tenant')

@section('page-title', 'Tentang Toko')

@section('tenant-content')
    <div>
        <h1 class="text-headline-lg text-on-surface tracking-tight">Tentang Toko</h1>
        <p class="text-body-sm text-on-surface-variant mt-0.5">Konten ini tampil di halaman "Tentang" storefront publik toko kamu — kosongkan kalau belum mau ditampilkan.</p>
    </div>

    <form method="POST" action="{{ route('tenant.about.update') }}" class="max-w-2xl flex flex-col gap-5 bg-surface-container-lowest rounded-2xl shadow-sm p-6">
        @csrf

        <div class="flex flex-col gap-1.5">
            <label class="text-label-lg text-on-surface">Tentang Toko Kami</label>
            <p class="text-label-sm text-on-surface-variant -mt-0.5">Ceritakan latar belakang, produk unggulan, atau nilai yang dipegang toko kamu.</p>
            <textarea name="about_content" rows="6" placeholder="Contoh: Kopi Senja Nusantara dimulai dari dapur rumah kecil di Jakarta Selatan tahun 2021..."
                      class="w-full p-3.5 rounded-lg bg-surface-container-low text-on-surface text-body-sm focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary transition-all resize-y">{{ old('about_content', $aboutContent) }}</textarea>
        </div>

        <div class="flex flex-col gap-1.5">
            <label class="text-label-lg text-on-surface">Kenapa Pilih Kami</label>
            <p class="text-label-sm text-on-surface-variant -mt-0.5">Alasan pembeli harus belanja di toko kamu (kualitas, harga, pengalaman, dll).</p>
            <textarea name="why_us_content" rows="6" placeholder="Contoh: Semua biji kopi kami roasting sendiri tiap minggu, jadi selalu fresh..."
                      class="w-full p-3.5 rounded-lg bg-surface-container-low text-on-surface text-body-sm focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary transition-all resize-y">{{ old('why_us_content', $whyUsContent) }}</textarea>
        </div>

        <button type="submit" class="self-start inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary-container text-on-primary text-label-lg font-semibold shadow-sm hover:bg-primary transition-all">
            <span class="material-symbols-outlined text-[18px]">save</span><span>Simpan</span>
        </button>
    </form>
@endsection
