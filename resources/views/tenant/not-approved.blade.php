@extends('layouts.base')

@section('content')
    <main class="max-w-md mx-auto px-4 py-20 text-center">
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm p-8">
            @if ($tenant->status === 'pending')
                <div class="w-14 h-14 rounded-full bg-amber-50 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-amber-600 text-[28px]">hourglass_top</span>
                </div>
                <h1 class="text-headline-md text-on-surface mb-2">Toko "{{ $tenant->name }}" masih ditinjau</h1>
                <p class="text-body-md text-on-surface-variant">Pendaftaranmu sedang kami review. Kami akan hubungi {{ $tenant->owner_email }} begitu disetujui.</p>
            @else
                <div class="w-14 h-14 rounded-full bg-error-container flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-error text-[28px]">block</span>
                </div>
                <h1 class="text-headline-md text-on-surface mb-2">Toko "{{ $tenant->name }}" sedang nonaktif</h1>
                <p class="text-body-md text-on-surface-variant">Hubungi admin platform untuk informasi lebih lanjut.</p>
            @endif
        </div>
    </main>
@endsection
