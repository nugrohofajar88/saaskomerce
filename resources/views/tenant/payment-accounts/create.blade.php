@extends('layouts.tenant')

@section('page-title', 'Tambah Rekening')

@section('tenant-content')
    <a href="{{ route('tenant.payment-accounts.index') }}" class="inline-flex items-center gap-1.5 text-primary text-label-md hover:underline w-fit">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span><span>Kembali ke Rekening</span>
    </a>
    <h1 class="text-headline-lg text-on-surface tracking-tight">Tambah Rekening</h1>

    <form method="POST" action="{{ route('tenant.payment-accounts.store') }}" class="max-w-md flex flex-col gap-4 bg-surface-container-lowest rounded-2xl shadow-sm p-6">
        @csrf
        @include('tenant.payment-accounts._form')
        <button type="submit" class="inline-flex items-center gap-2 self-start px-5 py-2.5 rounded-lg bg-primary-container text-on-primary text-label-lg font-semibold shadow-sm hover:bg-primary transition-all">
            <span class="material-symbols-outlined text-[18px]">save</span><span>Simpan</span>
        </button>
    </form>
@endsection
