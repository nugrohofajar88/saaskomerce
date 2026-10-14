@extends('layouts.platform')

@section('platform-content')
    <div class="mb-6">
        <h1 class="text-headline-lg text-on-surface tracking-tight">Tenant</h1>
        <p class="text-body-sm text-on-surface-variant mt-0.5">{{ $pendingCount }} toko menunggu approval.</p>
    </div>

    <div class="flex gap-2 mb-5 text-label-md flex-wrap">
        @foreach (['' => 'Semua', 'pending' => 'Pending', 'trial' => 'Trial', 'active' => 'Active', 'suspended' => 'Suspended'] as $value => $label)
            <a href="{{ route('platform.tenants.index', array_filter(['status' => $value])) }}"
               class="px-4 py-2 rounded-lg {{ $status === $value || ($status === null && $value === '') ? 'bg-primary-container text-on-primary font-semibold shadow-sm' : 'bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container-low shadow-sm' }} transition-all">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-surface-container-lowest rounded-2xl shadow-sm divide-y divide-surface-container">
        @forelse ($tenants as $tenant)
            @php
                $statusMeta = match ($tenant->status) {
                    'active' => ['bg-tertiary-fixed text-on-tertiary-fixed', 'Active'],
                    'trial' => ['bg-amber-50 text-amber-800', 'Trial'],
                    'suspended' => ['bg-error-container text-on-error-container', 'Suspended'],
                    default => ['bg-surface-container-highest text-on-surface-variant', 'Pending'],
                };
            @endphp
            <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-label-lg text-on-surface font-bold">{{ $tenant->name }}</span>
                        <span class="text-label-sm text-on-surface-variant">({{ $tenant->subdomain }})</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-label-sm font-semibold {{ $statusMeta[0] }}">{{ $statusMeta[1] }}</span>
                    </div>
                    <div class="text-body-sm text-on-surface-variant mt-0.5">{{ $tenant->owner_name }} · {{ $tenant->owner_email }} · {{ $tenant->owner_phone }}</div>
                    <div class="text-label-sm text-on-surface-variant mt-0.5">Daftar {{ $tenant->created_at->diffForHumans() }}</div>
                </div>
                <div class="flex gap-2 shrink-0">
                    @if ($tenant->status === 'pending')
                        <form method="POST" action="{{ route('platform.tenants.approve', $tenant) }}">
                            @csrf
                            <input type="hidden" name="status" value="trial">
                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-tertiary-container text-on-tertiary px-3.5 py-2 text-label-sm font-semibold shadow-sm hover:bg-tertiary transition-colors">
                                <span class="material-symbols-outlined text-[16px]">check_circle</span><span>Setujui (Trial)</span>
                            </button>
                        </form>
                    @endif
                    @if ($tenant->status !== 'suspended')
                        <form method="POST" action="{{ route('platform.tenants.suspend', $tenant) }}" onsubmit="return confirm('Suspend toko ini?')">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-error-container text-on-error-container px-3.5 py-2 text-label-sm font-semibold hover:bg-rose-100 transition-colors">
                                <span class="material-symbols-outlined text-[16px]">block</span><span>Suspend</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-body-sm text-on-surface-variant">Belum ada tenant.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $tenants->links() }}</div>
@endsection
