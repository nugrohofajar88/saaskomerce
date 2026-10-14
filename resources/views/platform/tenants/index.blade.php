@extends('layouts.platform')

@section('platform-content')
    <h1 class="text-xl font-bold mb-4">Tenant ({{ $pendingCount }} menunggu approval)</h1>

    <div class="flex gap-2 mb-4 text-sm">
        @foreach (['' => 'Semua', 'pending' => 'Pending', 'trial' => 'Trial', 'active' => 'Active', 'suspended' => 'Suspended'] as $value => $label)
            <a href="{{ route('platform.tenants.index', array_filter(['status' => $value])) }}"
               class="px-3 py-1.5 rounded-full border {{ $status === $value || ($status === null && $value === '') ? 'bg-gray-900 text-white' : '' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm divide-y">
        @forelse ($tenants as $tenant)
            <div class="p-4 flex items-center justify-between">
                <div>
                    <div class="font-semibold">{{ $tenant->name }} <span class="text-gray-400 font-normal">({{ $tenant->subdomain }})</span></div>
                    <div class="text-sm text-gray-500">{{ $tenant->owner_name }} · {{ $tenant->owner_email }} · {{ $tenant->owner_phone }}</div>
                    <div class="text-xs text-gray-400 mt-1">Status: {{ $tenant->status }} · Daftar {{ $tenant->created_at->diffForHumans() }}</div>
                </div>
                <div class="flex gap-2">
                    @if ($tenant->status === 'pending')
                        <form method="POST" action="{{ route('platform.tenants.approve', $tenant) }}">
                            @csrf
                            <input type="hidden" name="status" value="trial">
                            <button class="rounded-lg bg-green-600 text-white px-3 py-1.5 text-sm">Setujui (Trial)</button>
                        </form>
                    @endif
                    @if ($tenant->status !== 'suspended')
                        <form method="POST" action="{{ route('platform.tenants.suspend', $tenant) }}" onsubmit="return confirm('Suspend toko ini?')">
                            @csrf
                            <button class="rounded-lg border border-red-300 text-red-600 px-3 py-1.5 text-sm">Suspend</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-4 text-gray-500 text-sm">Belum ada tenant.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $tenants->links() }}</div>
@endsection
