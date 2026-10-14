@extends('layouts.tenant')

@section('page-title', 'Produk')

@section('tenant-content')
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-2 text-label-md text-on-surface-variant">
                <a href="{{ route('tenant.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-semibold">Produk</span>
            </div>
            <h1 class="text-headline-xl text-on-surface tracking-tight">Katalog Produk</h1>
            <p class="text-body-md text-on-surface-variant">Kelola inventaris, varian harga, dan ketersediaan stok produk toko Anda.</p>
        </div>
        <a href="{{ route('tenant.products.create') }}" class="h-10 px-6 bg-primary-container hover:bg-primary text-on-primary rounded-lg text-label-lg font-semibold shadow-md hover:shadow-lg transition-all inline-flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px]">add</span>
            <span>Tambah Produk</span>
        </a>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden flex flex-col">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant text-label-md uppercase tracking-wider">
                        <th class="py-3.5 pl-6 pr-3 font-semibold">Produk &amp; Kategori</th>
                        <th class="py-3.5 px-4 font-semibold">Varian</th>
                        <th class="py-3.5 px-4 font-semibold">Harga</th>
                        <th class="py-3.5 px-4 font-semibold">Total Stok</th>
                        <th class="py-3.5 px-4 font-semibold">Status</th>
                        <th class="py-3.5 pr-6 pl-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container text-on-surface text-body-sm">
                    @forelse ($products as $product)
                        @php
                            $prices = $product->variants->pluck('price');
                            $priceLabel = $prices->isEmpty() ? '-' : ($prices->min() === $prices->max()
                                ? 'Rp'.number_format($prices->min(), 0, ',', '.')
                                : 'Rp'.number_format($prices->min(), 0, ',', '.').' – Rp'.number_format($prices->max(), 0, ',', '.'));
                        @endphp
                        <tr class="hover:bg-surface-container-low/70 transition-colors group">
                            <td class="py-4 pl-6 pr-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-surface-container shrink-0 flex items-center justify-center overflow-hidden">
                                        @if ($product->images->isNotEmpty())
                                            <img src="{{ $product->images->first()->url }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                                        @else
                                            <span class="material-symbols-outlined text-outline text-[22px]">image</span>
                                        @endif
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span class="text-headline-sm font-bold text-on-surface group-hover:text-primary transition-colors truncate">{{ $product->name }}</span>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-label-sm bg-surface-container-high text-on-surface-variant font-medium">{{ $product->category?->name ?? 'Tanpa kategori' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex flex-col">
                                    <span class="text-label-lg text-on-surface font-semibold">{{ $product->variants->count() }} Varian</span>
                                    <span class="text-label-sm text-on-surface-variant truncate">{{ $product->variants->pluck('label')->implode(', ') }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4"><span class="text-price-md font-bold text-on-surface">{{ $priceLabel }}</span></td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $product->stock > 5 ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                    <span class="text-label-lg font-semibold {{ $product->stock > 5 ? 'text-emerald-800' : 'text-amber-900' }}">{{ $product->stock }} pcs</span>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                @if ($product->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-label-sm bg-tertiary-fixed text-on-tertiary-fixed font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-tertiary-container"></span>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-label-sm bg-surface-container-highest text-on-surface-variant font-semibold">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-4 pr-6 pl-4 text-right">
                                <div class="inline-flex items-center gap-1 text-on-surface-variant">
                                    <a href="{{ route('tenant.products.edit', $product) }}" title="Ubah Produk" class="w-8 h-8 rounded-lg hover:bg-surface-container-high hover:text-primary flex items-center justify-center transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('tenant.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus Produk" class="w-8 h-8 rounded-lg hover:bg-error-container hover:text-error flex items-center justify-center transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-10 text-center text-on-surface-variant">Belum ada produk. Mulai dengan menambahkan produk pertama.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $products->links() }}</div>
@endsection
