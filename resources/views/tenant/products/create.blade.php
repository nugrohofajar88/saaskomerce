@extends('layouts.tenant')

@section('page-title', 'Tambah Produk')

@section('tenant-content')
    <form method="POST" action="{{ route('tenant.products.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex flex-col gap-1 min-w-0">
                <a href="{{ route('tenant.products.index') }}" class="inline-flex items-center gap-1.5 text-primary text-label-md hover:underline w-fit">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    <span>Kembali ke Daftar Produk</span>
                </a>
                <div class="flex items-center gap-3">
                    <h1 class="text-headline-lg text-on-surface tracking-tight">Tambah Produk Baru</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-label-sm bg-primary-fixed text-on-primary-fixed font-semibold">Mode Baru</span>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('tenant.products.index') }}" class="px-4 py-2 rounded-lg bg-surface-container-lowest text-on-surface-variant text-label-lg shadow-sm hover:bg-surface-container-high transition-colors">Batal</a>
                <button type="submit" class="flex items-center gap-2 px-5 py-2 rounded-lg bg-primary-container text-on-primary text-label-lg shadow-sm hover:opacity-95 transition-all">
                    <span class="material-symbols-outlined text-[20px]">save</span><span>Simpan Produk</span>
                </button>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl bg-error-container text-on-error-container text-body-sm p-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @include('tenant.products._form')
    </form>
@endsection
