@extends('layouts.tenant')

@section('tenant-content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold">Produk</h1>
        <a href="{{ route('tenant.products.create') }}" class="rounded-lg bg-gray-900 text-white px-4 py-2 text-sm font-semibold">Tambah Produk</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm divide-y">
        @forelse ($products as $product)
            <div class="p-4 flex items-center justify-between">
                <div>
                    <div class="font-semibold">{{ $product->name }}</div>
                    <div class="text-sm text-gray-500">
                        {{ $product->category?->name ?? 'Tanpa kategori' }} ·
                        Rp{{ number_format($product->price ?? 0, 0, ',', '.') }} ·
                        Stok {{ $product->stock }} ·
                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                    </div>
                </div>
                <div class="flex gap-3 text-sm">
                    <a href="{{ route('tenant.products.edit', $product) }}" class="text-blue-600">Edit</a>
                    <form method="POST" action="{{ route('tenant.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-4 text-gray-500 text-sm">Belum ada produk.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
@endsection
