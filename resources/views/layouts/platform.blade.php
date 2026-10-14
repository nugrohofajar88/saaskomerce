@extends('layouts.base')

@section('content')
    <header class="bg-inverse-surface text-inverse-on-surface">
        <div class="max-w-6xl mx-auto px-4 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary text-[18px]">admin_panel_settings</span>
                </div>
                <span class="text-label-lg font-semibold">{{ config('app.name') }} — Platform Admin</span>
            </div>
            @auth('platform')
                <form method="POST" action="{{ route('platform.logout') }}">
                    @csrf
                    <button type="submit" class="text-label-md text-inverse-on-surface/70 hover:text-inverse-on-surface transition-colors">Keluar</button>
                </form>
            @endauth
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 lg:px-8 py-8">
        @yield('platform-content')
    </main>
@endsection
