@extends('layouts.storefront')

@section('storefront-content')
    <div class="max-w-sm mx-auto">
        <h1 class="text-xl font-bold mb-6 text-center">Daftar Akun</h1>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 text-red-700 text-sm p-3">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('storefront.register.attempt') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" required class="w-full rounded-lg border px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="w-full rounded-lg border px-3 py-2">
            </div>
            <button type="submit" class="w-full rounded-lg bg-gray-900 text-white px-4 py-2.5 font-semibold">Daftar</button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-4">Sudah punya akun? <a href="{{ route('storefront.login') }}" class="text-blue-600">Masuk</a></p>
    </div>
@endsection
