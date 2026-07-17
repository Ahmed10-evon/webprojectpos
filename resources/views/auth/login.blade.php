<x-guest-layout>
    @if ($errors->any())
        <div class="bg-red-500/10 border border-red-500/30 text-red-300 text-sm font-semibold px-4 py-3 rounded mb-5 text-center">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('status'))
        <div class="bg-cyan-500/10 border border-cyan-500/30 text-cyan-300 text-sm font-semibold px-4 py-3 rounded mb-5 text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-[10px] font-bold uppercase tracking-widest text-white/40 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full bg-transparent border-b border-white/20 focus:border-cyan-400 outline-none text-white py-2 font-mono"
                   placeholder="you@example.com">
        </div>

        <div>
            <label class="block text-[10px] font-bold uppercase tracking-widest text-white/40 mb-2">Password</label>
            <input type="password" name="password" required autocomplete="current-password"
                   class="w-full bg-transparent border-b border-white/20 focus:border-cyan-400 outline-none text-white py-2 font-mono"
                   placeholder="••••••••">
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-xs text-white/50">
                <input type="checkbox" name="remember" class="accent-cyan-400"> Remember me
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs text-cyan-400 hover:underline">Forgot password?</a>
            @endif
        </div>

        <button type="submit"
                class="w-full py-3 border border-cyan-400 text-cyan-400 rounded font-bold text-xs uppercase tracking-widest hover:bg-cyan-400 hover:text-black transition-colors">
            Access System
        </button>
    </form>
</x-guest-layout>
