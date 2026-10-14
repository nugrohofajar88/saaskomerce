@extends('layouts.base')

@section('content')
    <header class="border-b bg-gray-900 text-white">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <span class="font-bold">{{ config('app.name') }} — Platform Admin</span>
            @auth('platform')
                <form method="POST" action="{{ route('platform.logout') }}">
                    @csrf
                    <button class="text-sm text-gray-300 hover:text-white">Keluar</button>
                </form>
            @endauth
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8">
        @yield('platform-content')
    </main>
@endsection
