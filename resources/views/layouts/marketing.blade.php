@extends('layouts.base')

@section('content')
    <header class="border-b bg-white">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('marketing.home') }}" class="font-bold text-lg">{{ config('app.name') }}</a>
            <a href="{{ route('marketing.signup') }}" class="rounded-lg bg-gray-900 text-white px-4 py-2 text-sm font-semibold">Daftar Toko</a>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-10">
        @yield('marketing-content')
    </main>
@endsection
