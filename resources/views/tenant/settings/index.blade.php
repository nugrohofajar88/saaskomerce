@extends('layouts.tenant')

@section('tenant-content')
    <h1 class="text-xl font-bold mb-6">Pengaturan</h1>

    <form method="POST" action="{{ route('tenant.settings.update') }}" class="max-w-md space-y-4 bg-white rounded-xl shadow-sm p-6">
        @csrf
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="store_open" value="1" @checked($storeOpen)>
            Toko buka (terima pesanan baru)
        </label>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="payment_transfer_enabled" value="1" @checked($paymentTransferEnabled)>
            Terima pembayaran transfer manual
        </label>
        <button type="submit" class="rounded-lg bg-gray-900 text-white px-4 py-2.5 font-semibold">Simpan</button>
    </form>
@endsection
