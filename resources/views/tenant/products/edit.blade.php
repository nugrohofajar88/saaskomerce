@extends('layouts.tenant')

@section('tenant-content')
    <h1 class="text-xl font-bold mb-6">Edit Produk</h1>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 text-red-700 text-sm p-3 max-w-2xl">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tenant.products.update', $product) }}" enctype="multipart/form-data" class="max-w-2xl bg-white rounded-xl shadow-sm p-6">
        @csrf @method('PUT')
        @include('tenant.products._form')
        <button type="submit" class="rounded-lg bg-gray-900 text-white px-4 py-2.5 font-semibold mt-6">Simpan</button>
    </form>
@endsection
