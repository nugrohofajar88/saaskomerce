@extends('layouts.marketing')

@section('marketing-content')
    <div class="max-w-md mx-auto text-center py-10">
        <h1 class="text-2xl font-bold mb-3">Pendaftaran terkirim!</h1>
        <p class="text-gray-600">Toko <strong>{{ $tenant->name }}</strong> ({{ $tenant->subdomain }}.{{ config('app.base_domain') }}) sedang kami tinjau. Kamu akan bisa login setelah toko disetujui.</p>
    </div>
@endsection
