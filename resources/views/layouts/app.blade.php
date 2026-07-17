<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — CRAVE ABS Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { brass: '#a8763b', ink: '#1c1c1c' } } } };
    </script>
    @stack('head')
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
<div class="lg:flex">

    {{-- Sidebar --}}
    <aside class="hidden lg:flex lg:flex-col lg:fixed lg:inset-y-0 lg:w-64 bg-ink text-gray-200">
        <div class="px-6 pt-7 pb-5">
            <h1 class="text-xl font-bold text-white tracking-tight">CRAVE <span class="text-brass">ABS</span></h1>
            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 mt-1">Admin Console</p>
        </div>
        <div class="mx-5 h-px bg-white/10"></div>

        <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto text-sm font-semibold">
            @php $user = auth()->user(); @endphp

            <a href="{{ route('dashboard') }}" class="block px-3 py-2.5 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Overview</a>

            <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-widest text-gray-500">Sell</p>
            <a href="{{ route('pos.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('pos.*') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">POS Terminal</a>
            <a href="{{ route('sales.all') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('sales.all') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">All Sales</a>
            <a href="{{ route('sales.add') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('sales.add') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Add Sale</a>
            <a href="{{ route('sales.orders.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('sales.orders.*') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Sales Orders</a>
            @if($user->isAdmin())
                <a href="{{ route('refund.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('refund.*') ? 'bg-red-700 text-white' : 'hover:bg-white/5 text-red-300' }}">Refunds</a>
            @endif

            <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-widest text-gray-500">Products</p>
            <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('products.index') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">List Products</a>
            @if($user->isAdmin())
                <a href="{{ route('products.create') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('products.create') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Add Product</a>
                <a href="{{ route('products.price') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('products.price') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Update Price</a>
                <a href="{{ route('categories.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('categories.*') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Categories</a>
                <a href="{{ route('units.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('units.*') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Units</a>
                <a href="{{ route('brands.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('brands.*') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Brands</a>
            @endif

            @if($user->isAdmin())
                <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-widest text-gray-500">Purchases</p>
                <a href="{{ route('purchases.requisition.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('purchases.requisition.*') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Requisition</a>
                <a href="{{ route('purchases.orders.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('purchases.orders.*') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Purchase Orders</a>
                <a href="{{ route('purchases.add.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('purchases.add.*') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Add Purchase</a>
                <a href="{{ route('purchases.list') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('purchases.list') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">List Purchases</a>
                <a href="{{ route('purchases.returns.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('purchases.returns.*') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Purchase Returns</a>

                <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-widest text-gray-500">Money</p>
                <a href="{{ route('reports.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('reports.*') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Reports</a>
                <a href="{{ route('daily-cost.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('daily-cost.*') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Daily Cost</a>
                <a href="{{ route('survey.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('survey.*') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Daily Sales Survey</a>
            @endif

            <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-widest text-gray-500">Membership</p>
            <a href="{{ route('membership.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('membership.index') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Members List</a>
            <a href="{{ route('membership.create') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('membership.create') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Add Member</a>
            @if($user->isAdmin())
                <a href="{{ route('membership.settings') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('membership.settings') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Membership Settings</a>
            @endif

            @if($user->isAdmin())
                <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-widest text-gray-500">Settings</p>
                <a href="{{ route('settings.business') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('settings.business') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Business</a>
                <a href="{{ route('settings.invoice') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('settings.invoice') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Invoice</a>
                <a href="{{ route('settings.barcode') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('settings.barcode') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Barcode</a>
                <a href="{{ route('settings.tax') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('settings.tax') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Tax Rates</a>
                <a href="{{ route('users.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('users.*') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">Staff Accounts</a>
            @endif
        </nav>

        <div class="px-4 pt-3 pb-5 border-t border-white/10">
            <p class="px-3 text-xs text-gray-400 mb-1">{{ $user->name }}</p>
            <p class="px-3 text-[10px] uppercase tracking-widest text-brass mb-3">{{ $user->role }}</p>
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('profile.edit') ? 'bg-brass text-white' : 'hover:bg-white/5' }}">My Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-3 py-2 rounded-lg text-sm font-semibold text-red-300 hover:bg-white/5">Log Out</button>
            </form>
        </div>
    </aside>

    {{-- Mobile top bar --}}
    <div class="lg:hidden sticky top-0 z-40 bg-ink text-white">
        <div class="flex items-center justify-between px-4 h-14">
            <span class="font-bold">CRAVE <span class="text-brass">ABS</span></span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-xs font-bold uppercase text-red-300">Log Out</button>
            </form>
        </div>
        <div class="flex overflow-x-auto gap-1 px-2 pb-2 text-xs font-bold uppercase">
            <a href="{{ route('dashboard') }}" class="px-3 py-1.5 whitespace-nowrap rounded {{ request()->routeIs('dashboard') ? 'bg-brass' : 'bg-white/5' }}">Overview</a>
            <a href="{{ route('pos.index') }}" class="px-3 py-1.5 whitespace-nowrap rounded {{ request()->routeIs('pos.*') ? 'bg-brass' : 'bg-white/5' }}">POS</a>
            <a href="{{ route('products.index') }}" class="px-3 py-1.5 whitespace-nowrap rounded {{ request()->routeIs('products.index') ? 'bg-brass' : 'bg-white/5' }}">Products</a>
            <a href="{{ route('sales.all') }}" class="px-3 py-1.5 whitespace-nowrap rounded {{ request()->routeIs('sales.all') ? 'bg-brass' : 'bg-white/5' }}">Sales</a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('purchases.list') }}" class="px-3 py-1.5 whitespace-nowrap rounded {{ request()->routeIs('purchases.*') ? 'bg-brass' : 'bg-white/5' }}">Purchases</a>
                <a href="{{ route('reports.index') }}" class="px-3 py-1.5 whitespace-nowrap rounded {{ request()->routeIs('reports.*') ? 'bg-brass' : 'bg-white/5' }}">Reports</a>
            @endif
            <a href="{{ route('membership.index') }}" class="px-3 py-1.5 whitespace-nowrap rounded {{ request()->routeIs('membership.*') ? 'bg-brass' : 'bg-white/5' }}">Members</a>
        </div>
    </div>

    {{-- Main content --}}
    <div class="lg:pl-64 flex-1">
        <div class="max-w-6xl mx-auto px-5 sm:px-8 py-8">

            <div class="hidden lg:flex items-baseline justify-between mb-8">
                <h2 class="text-2xl font-bold">@yield('heading', 'Dashboard')</h2>
                <p class="text-xs uppercase tracking-wider text-gray-500">{{ now()->format('l, F j, Y') }}</p>
            </div>

            @if(session('success'))
                <div class="px-4 py-3 mb-5 text-sm font-semibold border border-green-200 bg-green-50 text-green-700 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="px-4 py-3 mb-5 text-sm font-semibold border border-red-200 bg-red-50 text-red-700 rounded">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="px-4 py-3 mb-5 text-sm font-semibold border border-red-200 bg-red-50 text-red-700 rounded">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>
@stack('scripts')
</body>
</html>
