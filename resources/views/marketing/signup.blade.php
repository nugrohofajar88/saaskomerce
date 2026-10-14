@extends('layouts.marketing')

@section('marketing-content')
    <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-bold mb-6">Daftarkan Toko</h1>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 text-red-700 text-sm p-3">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('marketing.signup.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Nama Toko</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Subdomain</label>
                <div class="flex items-center">
                    <input type="text" name="subdomain" value="{{ old('subdomain') }}" required
                           class="w-full rounded-lg border px-3 py-2" placeholder="tokokamu">
                    <span class="ml-2 text-gray-500 text-sm whitespace-nowrap">.{{ config('app.base_domain') }}</span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nama Pemilik</label>
                <input type="text" name="owner_name" value="{{ old('owner_name') }}" required class="w-full rounded-lg border px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="owner_email" value="{{ old('owner_email') }}" required class="w-full rounded-lg border px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">No. WhatsApp</label>
                <input type="text" name="owner_phone" value="{{ old('owner_phone') }}" class="w-full rounded-lg border px-3 py-2">
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
    </div>
@endsection
