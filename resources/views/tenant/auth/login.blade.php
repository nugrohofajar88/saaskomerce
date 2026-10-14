@extends('layouts.base')

@section('content')
    <main class="max-w-sm mx-auto px-4 py-20">
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm p-6 sm:p-8">
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-12 h-12 rounded-xl bg-primary-container text-on-primary flex items-center justify-center mb-3 shadow-sm">
                    <span class="material-symbols-outlined text-[24px]">storefront</span>
                </div>
                <h1 class="text-headline-md text-on-surface">{{ tenant()->name }}</h1>
                <p class="text-body-sm text-on-surface-variant">Login Admin Toko</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-error-container text-on-error-container text-body-sm p-4">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('tenant.login.attempt') }}" class="flex flex-col gap-4">
                @csrf
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
                <button type="submit" class="w-full mt-2 inline-flex items-center justify-center gap-2 rounded-xl bg-primary-container text-on-primary px-4 py-3 text-label-lg font-semibold shadow-md hover:bg-primary transition-all">Masuk</button>
            </form>
        </div>
    </main>
@endsection
