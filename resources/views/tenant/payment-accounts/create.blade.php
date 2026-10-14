@extends('layouts.tenant')

@section('tenant-content')
    <h1 class="text-xl font-bold mb-6">Tambah Rekening</h1>

    <form method="POST" action="{{ route('tenant.payment-accounts.store') }}" class="max-w-md space-y-4 bg-white rounded-xl shadow-sm p-6">
        @csrf
        @include('tenant.payment-accounts._form')
        <button type="submit" class="rounded-lg bg-gray-900 text-white px-4 py-2.5 font-semibold">Simpan</button>
    </form>
@endsection
