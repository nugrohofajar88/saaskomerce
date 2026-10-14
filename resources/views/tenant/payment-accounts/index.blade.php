@extends('layouts.tenant')

@section('page-title', 'Rekening Pembayaran')

@section('tenant-content')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-lg text-on-surface tracking-tight">Rekening Pembayaran</h1>
            <p class="text-body-sm text-on-surface-variant mt-0.5">Rekening ini ditampilkan ke pembeli saat checkout.</p>
        </div>
        <a href="{{ route('tenant.payment-accounts.create') }}" class="inline-flex items-center gap-2 h-10 px-5 rounded-lg bg-primary-container text-on-primary text-label-lg font-semibold shadow-sm hover:bg-primary transition-all">
            <span class="material-symbols-outlined text-[18px]">add</span><span>Tambah Rekening</span>
        </a>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl shadow-sm divide-y divide-surface-container">
        @forelse ($accounts as $account)
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center text-on-primary text-label-sm font-bold shrink-0">{{ Str::substr($account->bank_name, 0, 3) }}</div>
                    <div class="flex flex-col">
                        <span class="text-label-lg text-on-surface font-bold">{{ $account->account_number }}</span>
                        <span class="text-label-sm text-on-surface-variant">{{ $account->bank_name }} a.n. {{ $account->account_holder }}</span>
                    </div>
                    @if ($account->is_active)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-label-sm bg-tertiary-fixed text-on-tertiary-fixed font-semibold ml-2">Aktif</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-label-sm bg-surface-container-highest text-on-surface-variant font-semibold ml-2">Nonaktif</span>
                    @endif
                </div>
                <div class="flex items-center gap-1">
                    <a href="{{ route('tenant.payment-accounts.edit', $account) }}" title="Ubah" class="w-8 h-8 rounded-lg hover:bg-surface-container-high hover:text-primary flex items-center justify-center text-on-surface-variant transition-colors">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </a>
                    <form method="POST" action="{{ route('tenant.payment-accounts.destroy', $account) }}" onsubmit="return confirm('Hapus rekening ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" title="Hapus" class="w-8 h-8 rounded-lg hover:bg-error-container hover:text-error flex items-center justify-center text-on-surface-variant transition-colors">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-body-sm text-on-surface-variant">Belum ada rekening. Pelanggan butuh ini buat transfer.</div>
        @endforelse
    </div>
@endsection
