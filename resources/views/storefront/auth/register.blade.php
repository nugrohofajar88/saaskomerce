@extends('layouts.storefront')

@section('storefront-content')
    <div class="max-w-sm mx-auto bg-surface-container-lowest rounded-2xl shadow-sm p-6 sm:p-8">
        <h1 class="text-headline-lg text-on-surface tracking-tight mb-1 text-center">Daftar Akun</h1>
        <p class="text-body-sm text-on-surface-variant text-center mb-6">{{ tenant()?->name }}</p>

        @if ($errors->any())
            <div class="mb-4 rounded-xl bg-error-container text-on-error-container text-body-sm p-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('storefront.register.attempt') }}" class="flex flex-col gap-4">
            @csrf
            <div class="flex flex-col gap-1.5">
                <label class="text-label-lg text-on-surface">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full h-11 px-3.5 rounded-lg bg-surface-container-low text-on-surface text-body-sm focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary transition-all">
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-label-lg text-on-surface">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full h-11 px-3.5 rounded-lg bg-surface-container-low text-on-surface text-body-sm focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary transition-all">
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-label-lg text-on-surface">Password</label>
                <input type="password" name="password" required
                       class="w-full h-11 px-3.5 rounded-lg bg-surface-container-low text-on-surface text-body-sm focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary transition-all">
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-label-lg text-on-surface">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full h-11 px-3.5 rounded-lg bg-surface-container-low text-on-surface text-body-sm focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary transition-all">
            </div>
            <button type="submit" class="w-full mt-2 inline-flex items-center justify-center gap-2 rounded-xl bg-primary-container text-on-primary px-4 py-3 text-label-lg font-semibold shadow-md hover:bg-primary transition-all">Daftar</button>
        </form>

        <p class="text-center text-label-md text-on-surface-variant mt-5">Sudah punya akun? <a href="{{ route('storefront.login') }}" class="text-primary font-semibold hover:underline">Masuk</a></p>
    </div>
@endsection
