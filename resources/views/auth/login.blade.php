<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — CRAVE ABS Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[#050505] flex items-center justify-center px-4">
    <div class="w-full max-w-sm bg-[#0f0f11] border border-white/10 rounded-2xl p-10 shadow-2xl">
        <h1 class="text-2xl font-black text-white text-center tracking-wide">CRAVE <span class="text-cyan-400">ABS</span></h1>
        <p class="text-center text-[10px] uppercase tracking-[0.25em] text-cyan-400 mt-1 mb-8">Admin Console</p>

        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-300 text-sm font-semibold px-4 py-3 rounded mb-5 text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-white/40 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full bg-transparent border-b border-white/20 focus:border-cyan-400 outline-none text-white py-2 font-mono"
                       placeholder="you@example.com">
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-white/40 mb-2">Password</label>
                <input type="password" name="password" required
                       class="w-full bg-transparent border-b border-white/20 focus:border-cyan-400 outline-none text-white py-2 font-mono"
                       placeholder="••••••••">
            </div>
            <label class="flex items-center gap-2 text-xs text-white/50">
                <input type="checkbox" name="remember" class="accent-cyan-400"> Remember me on this device
            </label>
            <button type="submit"
                    class="w-full py-3 border border-cyan-400 text-cyan-400 rounded font-bold text-xs uppercase tracking-widest hover:bg-cyan-400 hover:text-black transition-colors">
                Access System
            </button>
        </form>
    </div>
</body>
</html>
