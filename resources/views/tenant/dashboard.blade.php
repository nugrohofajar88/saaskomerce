@extends('layouts.tenant')

@section('tenant-content')
    <h1 class="text-xl font-bold mb-6">Dashboard</h1>

    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="text-sm text-gray-500">Produk</div>
            <div class="text-2xl font-bold">{{ $productCount }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="text-sm text-gray-500">Pesanan Menunggu Bayar</div>
            <div class="text-2xl font-bold">{{ $pendingOrderCount }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="text-sm text-gray-500">Pesanan Lunas</div>
            <div class="text-2xl font-bold">{{ $paidOrderCount }}</div>
        </div>
    </div>
@endsection
