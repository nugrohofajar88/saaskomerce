@extends('layouts.base')

@section('content')
    <main class="max-w-md mx-auto px-4 py-20 text-center">
        @if ($tenant->status === 'pending')
            <h1 class="text-xl font-bold mb-2">Toko "{{ $tenant->name }}" masih ditinjau</h1>
            <p class="text-gray-600">Pendaftaranmu sedang kami review. Kami akan hubungi {{ $tenant->owner_email }} begitu disetujui.</p>
        @else
            <h1 class="text-xl font-bold mb-2">Toko "{{ $tenant->name }}" sedang nonaktif</h1>
            <p class="text-gray-600">Hubungi admin platform untuk informasi lebih lanjut.</p>
        @endif
    </main>
@endsection
