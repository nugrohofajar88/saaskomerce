@extends('layouts.tenant')

@section('page-title', 'Kategori')

@section('tenant-content')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-lg text-on-surface tracking-tight">Kategori</h1>
            <p class="text-body-sm text-on-surface-variant mt-0.5">Kelompokkan produk biar katalog lebih rapi.</p>
        </div>
        <a href="{{ route('tenant.categories.create') }}" class="inline-flex items-center gap-2 h-10 px-5 rounded-lg bg-primary-container text-on-primary text-label-lg font-semibold shadow-sm hover:bg-primary transition-all">
            <span class="material-symbols-outlined text-[18px]">add</span><span>Tambah Kategori</span>
        </a>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl shadow-sm divide-y divide-surface-container">
        @forelse ($categories as $category)
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-surface-container-low text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">category</span>
                    </div>
                    <span class="text-label-lg text-on-surface font-semibold">{{ $category->name }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <a href="{{ route('tenant.categories.edit', $category) }}" title="Ubah" class="w-8 h-8 rounded-lg hover:bg-surface-container-high hover:text-primary flex items-center justify-center text-on-surface-variant transition-colors">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </a>
                    <form method="POST" action="{{ route('tenant.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" title="Hapus" class="w-8 h-8 rounded-lg hover:bg-error-container hover:text-error flex items-center justify-center text-on-surface-variant transition-colors">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-body-sm text-on-surface-variant">Belum ada kategori.</div>
        @endforelse
    </div>
@endsection
