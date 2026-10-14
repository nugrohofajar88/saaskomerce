@extends('layouts.tenant')

@section('tenant-content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold">Rekening Pembayaran</h1>
        <a href="{{ route('tenant.payment-accounts.create') }}" class="rounded-lg bg-gray-900 text-white px-4 py-2 text-sm font-semibold">Tambah Rekening</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm divide-y">
        @forelse ($accounts as $account)
            <div class="p-4 flex items-center justify-between">
                <div>
                    <div class="font-semibold">{{ $account->bank_name }} — {{ $account->account_number }}</div>
                    <div class="text-sm text-gray-500">a.n. {{ $account->account_holder }} · {{ $account->is_active ? 'Aktif' : 'Nonaktif' }}</div>
                </div>
                <div class="flex gap-3 text-sm">
                    <a href="{{ route('tenant.payment-accounts.edit', $account) }}" class="text-blue-600">Edit</a>
                    <form method="POST" action="{{ route('tenant.payment-accounts.destroy', $account) }}" onsubmit="return confirm('Hapus rekening ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-4 text-gray-500 text-sm">Belum ada rekening. Pelanggan butuh ini buat transfer.</div>
        @endforelse
    </div>
@endsection
