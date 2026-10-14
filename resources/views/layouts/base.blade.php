<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    @if (session('status'))
        <div class="bg-green-50 text-green-800 text-sm px-4 py-2 text-center">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 text-red-800 text-sm px-4 py-2 text-center">{{ session('error') }}</div>
    @endif

    @yield('content')
</body>
</html>
