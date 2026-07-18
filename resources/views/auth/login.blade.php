<x-guest-layout>
    @if ($errors->any())
        <div class="bg-[#FBF1EF] border border-[#E4C3BC] text-[#A64B3D] text-sm font-semibold px-4 py-3 rounded-sm mb-5 text-center">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('status'))
        <div class="bg-[#F1F3EA] border border-[#C7CEBB] text-[#4F5B44] text-sm font-semibold px-4 py-3 rounded-sm mb-5 text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-[10px] font-bold uppercase tracking-widest text-ink/50 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full bg-transparent border-b-2 border-[#DCD6C4] focus:border-indigo outline-none text-ink py-2 font-mono transition-colors"
                   placeholder="you@example.com">
        </div>

        <div>
            <label class="block text-[10px] font-bold uppercase tracking-widest text-ink/50 mb-2">Password</label>
            <input type="password" name="password" required autocomplete="current-password"
                   class="w-full bg-transparent border-b-2 border-[#DCD6C4] focus:border-indigo outline-none text-ink py-2 font-mono transition-colors"
                   placeholder="••••••••">
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-xs text-ink/60">
                <input type="checkbox" name="remember" class="accent-indigo"> Remember me
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs text-indigo hover:underline">Forgot password?</a>
            @endif
        </div>

        <button type="submit"
                class="w-full py-3 bg-ink text-white rounded-sm font-bold text-xs uppercase tracking-widest hover:bg-indigo transition-colors">
            Access System
        </button>
    </form>
</x-guest-layout>
