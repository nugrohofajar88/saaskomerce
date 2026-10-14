@extends('layouts.storefront')

@section('storefront-content')
    <div class="max-w-sm mx-auto">
        <h1 class="text-xl font-bold mb-6 text-center">Masuk</h1>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 text-red-700 text-sm p-3">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('storefront.login.attempt') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" required class="w-full rounded-lg border px-3 py-2">
            </div>
            <button type="submit" class="w-full rounded-lg bg-gray-900 text-white px-4 py-2.5 font-semibold">Masuk</button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-4">Belum punya akun? <a href="{{ route('storefront.register') }}" class="text-blue-600">Daftar</a></p>
    </div>
@endsection
