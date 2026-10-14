@extends('layouts.marketing')

@section('marketing-content')
    <div class="text-center py-16">
        <h1 class="text-4xl font-bold mb-4">Bikin toko online kamu sendiri, dalam hitungan menit</h1>
        <p class="text-gray-600 mb-8">Platform jualan online buat UMKM — katalog, keranjang, checkout, semua sudah siap pakai.</p>
        <a href="{{ route('marketing.signup') }}" class="inline-block rounded-lg bg-gray-900 text-white px-6 py-3 font-semibold">Daftar Toko Gratis</a>
    </div>
@endsection
