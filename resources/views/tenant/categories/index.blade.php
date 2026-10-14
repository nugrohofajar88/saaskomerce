@extends('layouts.tenant')

@section('tenant-content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold">Kategori</h1>
        <a href="{{ route('tenant.categories.create') }}" class="rounded-lg bg-gray-900 text-white px-4 py-2 text-sm font-semibold">Tambah Kategori</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm divide-y">
        @forelse ($categories as $category)
            <div class="p-4 flex items-center justify-between">
                <span>{{ $category->name }}</span>
                <div class="flex gap-3 text-sm">
                    <a href="{{ route('tenant.categories.edit', $category) }}" class="text-blue-600">Edit</a>
                    <form method="POST" action="{{ route('tenant.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-4 text-gray-500 text-sm">Belum ada kategori.</div>
        @endforelse
    </div>
@endsection
