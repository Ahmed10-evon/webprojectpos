<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CRAVE ABS') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { brass: '#a8763b', ink: '#1c1c1c' } } } };
    </script>
</head>
<body class="min-h-screen bg-[#050505] flex items-center justify-center px-4">
    <div class="w-full max-w-sm bg-[#0f0f11] border border-white/10 rounded-2xl p-10 shadow-2xl">
        <h1 class="text-2xl font-black text-white text-center tracking-wide">
            CRAVE <span class="text-cyan-400">ABS</span>
        </h1>
        <p class="text-center text-[10px] uppercase tracking-[0.25em] text-cyan-400 mt-1 mb-8">Admin Console</p>

        {{ $slot }}
    </div>
</body>
</html>
