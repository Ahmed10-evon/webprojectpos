<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — CRAVE ABS Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&family=Noto+Sans+Bengali:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#1C1A17',
                        canvas: '#E7DDC7',
                        indigo: '#2B4570',
                        brass: '#A8763B',
                        sage: '#6B7A5E',
                        rust: '#A64B3D',
                    },
                    fontFamily: {
                        display: ['"Big Shoulders Display"', 'sans-serif'],
                        sans: ['Inter', 'sans-serif'],
                        mono: ['"JetBrains Mono"', '"Noto Sans Bengali"', 'monospace'],
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-image: radial-gradient(rgba(28,26,23,0.035) 1px, transparent 1px);
            background-size: 3px 3px;
        }

        /* Restrained "tag stock" treatment for every card site-wide — a warm
           hairline border, so every page feels like the same material even
           on pages we haven't hand-rebuilt. The full swing-tag signature
           (hole + string + barcode strip) is reserved for the Dashboard. */
        .border { border-color: #DCD6C4 !important; }

        /* Woven-canvas texture on the dark sidebar — a whisper, not a pattern */
        .sidebar-texture {
            background-image: radial-gradient(rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 3px 3px;
        }

        /* Branded scrollbars */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #4A4640; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #635D53; }

        /* Swing-tag signature (Dashboard stat tiles) */
        .swing-tag {
            position: relative;
            background: #F7F5EC;
            border: 1px solid #DCD6C4;
            border-radius: 4px;
        }
        .swing-tag::before {
            content: '';
            position: absolute;
            top: 14px; left: 14px;
            width: 10px; height: 10px;
            border-radius: 50%;
            background: #E7DDC7;
            border: 1.5px solid #B9B190;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.15);
        }
        .swing-tag::after {
            content: '';
            position: absolute;
            top: 6px; left: 11px;
            width: 16px; height: 16px;
            border: 1.5px solid #B9B190;
            border-radius: 50%;
            border-right-color: transparent;
            border-bottom-color: transparent;
            transform: rotate(45deg);
            opacity: 0.6;
        }
        .swing-tag-barcode {
            height: 14px;
            background-image: repeating-linear-gradient(90deg, #1C1A17 0 2px, transparent 2px 4px, #1C1A17 4px 5px, transparent 5px 9px, #1C1A17 9px 12px, transparent 12px 14px);
            opacity: 0.5;
        }

        /* A gentle "breathing" glow on whichever nav item the sliding
           indicator currently rests on — brightens the color/shadow that
           JS already set, without fighting its inline styles. */
        @keyframes glow-breathe {
            0%, 100% { filter: brightness(1) saturate(1); }
            50% { filter: brightness(1.3) saturate(1.2); }
        }
        #nav-liquid-indicator { animation: glow-breathe 2.6s ease-in-out infinite; }

        /* Wordmark: a slow color-cycle through the shop's section palette */
        @keyframes wordmark-glow {
            0%, 100% { text-shadow: 0 0 14px rgba(76,111,165,0.65); color: #7C93B8; }
            25%      { text-shadow: 0 0 14px rgba(217,167,87,0.6);  color: #E0BD82; }
            50%      { text-shadow: 0 0 14px rgba(143,166,126,0.6); color: #AEC29D; }
            75%      { text-shadow: 0 0 14px rgba(155,107,149,0.6); color: #C8A0C3; }
        }
        .wordmark-glow { animation: wordmark-glow 8s ease-in-out infinite; }
    </style>
    @stack('head')
</head>
<body class="min-h-screen bg-canvas text-ink">
<div class="lg:flex">

    {{-- Sidebar --}}
    <aside class="hidden lg:flex lg:flex-col lg:fixed lg:inset-y-0 lg:w-64 bg-ink text-gray-200 print:hidden sidebar-texture">
        <div class="px-6 pt-7 pb-5">
            <h1 class="font-display text-2xl font-extrabold text-white tracking-wide">CRAVE <span class="wordmark-glow">ABS</span></h1>
            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 mt-1">Admin Console</p>
        </div>
        <div class="mx-5 h-px bg-white/10"></div>

        <nav id="sidebar-nav" class="relative flex-1 px-3 py-5 space-y-1 overflow-y-auto text-sm font-semibold">
            <div id="nav-liquid-indicator"
                 class="absolute left-3 right-3 rounded-lg opacity-0 transition-[top,height,opacity,box-shadow,background-color] duration-300 ease-out pointer-events-none"
                 style="top:0; height:0; background:#4C6FA5;"></div>

            @php $user = auth()->user(); @endphp

            <a href="{{ route('dashboard') }}" class="block px-3 py-2.5 rounded-lg relative z-10 {{ request()->routeIs('dashboard') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('dashboard') ? 'true' : 'false' }}" data-color="#4C6FA5">Overview</a>

            <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-widest font-bold" style="color:#4C6FA5">Sell</p>
            <a href="{{ route('pos.index') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('pos.*') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('pos.*') ? 'true' : 'false' }}" data-color="#4C6FA5">POS Terminal</a>
            <a href="{{ route('sales.all') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('sales.all') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('sales.all') ? 'true' : 'false' }}" data-color="#4C6FA5">All Sales</a>
            <a href="{{ route('sales.add') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('sales.add') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('sales.add') ? 'true' : 'false' }}" data-color="#4C6FA5">Add Sale</a>
            <a href="{{ route('sales.orders.index') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('sales.orders.*') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('sales.orders.*') ? 'true' : 'false' }}" data-color="#4C6FA5">Sales Orders</a>
            @if($user->isAdmin())
                <a href="{{ route('refund.index') }}" class="block px-3 py-2 rounded-lg relative z-10 transition-shadow duration-300 {{ request()->routeIs('refund.*') ? 'bg-rust text-white shadow-[0_0_18px_rgba(166,75,61,0.55)]' : 'hover:bg-white/5 hover:shadow-[0_0_14px_rgba(166,75,61,0.35)] text-[#C98D82]' }}">Refunds</a>
            @endif

            <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-widest font-bold" style="color:#D9A757">Products</p>
            <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('products.index') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('products.index') ? 'true' : 'false' }}" data-color="#D9A757">List Products</a>
            @if($user->isAdmin())
                <a href="{{ route('products.create') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('products.create') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('products.create') ? 'true' : 'false' }}" data-color="#D9A757">Add Product</a>
                <a href="{{ route('products.price') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('products.price') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('products.price') ? 'true' : 'false' }}" data-color="#D9A757">Update Price</a>
                <a href="{{ route('categories.index') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('categories.*') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('categories.*') ? 'true' : 'false' }}" data-color="#D9A757">Categories</a>
                <a href="{{ route('units.index') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('units.*') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('units.*') ? 'true' : 'false' }}" data-color="#D9A757">Units</a>
                <a href="{{ route('brands.index') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('brands.*') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('brands.*') ? 'true' : 'false' }}" data-color="#D9A757">Brands</a>
            @endif

            <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-widest font-bold" style="color:#8FA67E">Inventory</p>
            <a href="{{ route('inventory.low-stock') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('inventory.low-stock') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('inventory.low-stock') ? 'true' : 'false' }}" data-color="#8FA67E">Low Stock Alert</a>
            @if($user->isAdmin())
                <a href="{{ route('inventory.adjustments.index') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('inventory.adjustments.*') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('inventory.adjustments.*') ? 'true' : 'false' }}" data-color="#8FA67E">Stock Adjustment</a>
                <a href="{{ route('inventory.valuation') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('inventory.valuation') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('inventory.valuation') ? 'true' : 'false' }}" data-color="#8FA67E">Inventory Valuation</a>
            @endif

            @if($user->isAdmin())
                <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-widest font-bold" style="color:#9B6B95">Purchases</p>
                <a href="{{ route('purchases.requisition.index') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('purchases.requisition.*') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('purchases.requisition.*') ? 'true' : 'false' }}" data-color="#9B6B95">Requisition</a>
                <a href="{{ route('purchases.orders.index') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('purchases.orders.*') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('purchases.orders.*') ? 'true' : 'false' }}" data-color="#9B6B95">Purchase Orders</a>
                <a href="{{ route('purchases.add.index') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('purchases.add.*') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('purchases.add.*') ? 'true' : 'false' }}" data-color="#9B6B95">Add Purchase</a>
                <a href="{{ route('purchases.list') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('purchases.list') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('purchases.list') ? 'true' : 'false' }}" data-color="#9B6B95">List Purchases</a>
                <a href="{{ route('purchases.returns.index') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('purchases.returns.*') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('purchases.returns.*') ? 'true' : 'false' }}" data-color="#9B6B95">Purchase Returns</a>

                <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-widest font-bold" style="color:#4A9098">Money</p>
                <a href="{{ route('reports.index') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('reports.*') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('reports.*') ? 'true' : 'false' }}" data-color="#4A9098">Reports</a>
                <a href="{{ route('daily-cost.index') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('daily-cost.*') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('daily-cost.*') ? 'true' : 'false' }}" data-color="#4A9098">Daily Cost</a>
                <a href="{{ route('survey.index') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('survey.*') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('survey.*') ? 'true' : 'false' }}" data-color="#4A9098">Daily Sales Survey</a>
            @endif

            <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-widest font-bold" style="color:#C97B65">Membership</p>
            <a href="{{ route('membership.index') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('membership.index') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('membership.index') ? 'true' : 'false' }}" data-color="#C97B65">Members List</a>
            <a href="{{ route('membership.create') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('membership.create') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('membership.create') ? 'true' : 'false' }}" data-color="#C97B65">Add Member</a>
            @if($user->isAdmin())
                <a href="{{ route('membership.settings') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('membership.settings') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('membership.settings') ? 'true' : 'false' }}" data-color="#C97B65">Membership Settings</a>
            @endif

            @if($user->isAdmin())
                <p class="px-3 pt-4 pb-1 text-[10px] uppercase tracking-widest font-bold" style="color:#6B7C93">Settings</p>
                <a href="{{ route('settings.business') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('settings.business') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('settings.business') ? 'true' : 'false' }}" data-color="#6B7C93">Business</a>
                <a href="{{ route('settings.invoice') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('settings.invoice') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('settings.invoice') ? 'true' : 'false' }}" data-color="#6B7C93">Invoice</a>
                <a href="{{ route('settings.barcode') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('settings.barcode') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('settings.barcode') ? 'true' : 'false' }}" data-color="#6B7C93">Barcode</a>
                <a href="{{ route('settings.tax') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('settings.tax') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('settings.tax') ? 'true' : 'false' }}" data-color="#6B7C93">Tax Rates</a>
                <a href="{{ route('users.index') }}" class="block px-3 py-2 rounded-lg relative z-10 {{ request()->routeIs('users.*') ? 'text-white' : 'hover:bg-white/5' }}" data-nav-link data-active="{{ request()->routeIs('users.*') ? 'true' : 'false' }}" data-color="#6B7C93">Staff Accounts</a>
            @endif
        </nav>

        <div class="px-4 pt-3 pb-5 border-t border-white/10">
            <p class="px-3 text-xs text-gray-400 mb-1">{{ $user->name }}</p>
            <p class="px-3 text-[10px] uppercase tracking-widest text-brass mb-3">{{ $user->role }}</p>
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold transition-shadow duration-300 {{ request()->routeIs('profile.edit') ? 'bg-indigo text-white shadow-[0_0_18px_rgba(76,111,165,0.5)]' : 'hover:bg-white/5' }}">My Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-3 py-2 rounded-lg text-sm font-semibold text-[#C98D82] hover:bg-white/5">Log Out</button>
            </form>
        </div>
    </aside>

    {{-- Mobile top bar --}}
    <div class="lg:hidden sticky top-0 z-40 bg-ink text-white print:hidden">
        <div class="flex items-center justify-between px-4 h-14">
            <span class="font-display font-bold tracking-wide">CRAVE <span class="text-[#7C93B8]">ABS</span></span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-xs font-bold uppercase text-[#C98D82]">Log Out</button>
            </form>
        </div>
        <div class="flex overflow-x-auto gap-1 px-2 pb-2 text-xs font-bold uppercase">
            <a href="{{ route('dashboard') }}" class="px-3 py-1.5 whitespace-nowrap rounded {{ request()->routeIs('dashboard') ? 'bg-indigo' : 'bg-white/5' }}">Overview</a>
            <a href="{{ route('pos.index') }}" class="px-3 py-1.5 whitespace-nowrap rounded {{ request()->routeIs('pos.*') ? 'bg-indigo' : 'bg-white/5' }}">POS</a>
            <a href="{{ route('products.index') }}" class="px-3 py-1.5 whitespace-nowrap rounded {{ request()->routeIs('products.index') ? 'bg-indigo' : 'bg-white/5' }}">Products</a>
            <a href="{{ route('sales.all') }}" class="px-3 py-1.5 whitespace-nowrap rounded {{ request()->routeIs('sales.all') ? 'bg-indigo' : 'bg-white/5' }}">Sales</a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('purchases.list') }}" class="px-3 py-1.5 whitespace-nowrap rounded {{ request()->routeIs('purchases.*') ? 'bg-indigo' : 'bg-white/5' }}">Purchases</a>
                <a href="{{ route('reports.index') }}" class="px-3 py-1.5 whitespace-nowrap rounded {{ request()->routeIs('reports.*') ? 'bg-indigo' : 'bg-white/5' }}">Reports</a>
            @endif
            <a href="{{ route('membership.index') }}" class="px-3 py-1.5 whitespace-nowrap rounded {{ request()->routeIs('membership.*') ? 'bg-indigo' : 'bg-white/5' }}">Members</a>
        </div>
    </div>

    {{-- Main content --}}
    <div class="lg:pl-64 flex-1">
        <div class="max-w-6xl mx-auto px-5 sm:px-8 py-8">

            <div class="hidden lg:flex items-baseline justify-between mb-8 print:hidden">
                <h2 class="text-2xl font-bold">@yield('heading', 'Dashboard')</h2>
                <p class="text-xs uppercase tracking-wider text-gray-500">{{ now()->format('l, F j, Y') }}</p>
            </div>

            @if(session('success'))
                <div class="px-4 py-3 mb-5 text-sm font-semibold border border-[#C7CEBB] bg-[#F1F3EA] text-[#4F5B44] rounded print:hidden">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="px-4 py-3 mb-5 text-sm font-semibold border border-[#E4C3BC] bg-[#FBF1EF] text-rust rounded print:hidden">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="px-4 py-3 mb-5 text-sm font-semibold border border-[#E4C3BC] bg-[#FBF1EF] text-rust rounded print:hidden">
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

<script>
    (function () {
        const nav = document.querySelector('nav.relative');
        const indicator = document.getElementById('nav-liquid-indicator');
        if (!nav || !indicator) return;

        const links = Array.from(nav.querySelectorAll('[data-nav-link]'));

        function hexToRgb(hex) {
            const num = parseInt(hex.replace('#', ''), 16);
            return [(num >> 16) & 255, (num >> 8) & 255, num & 255];
        }

        function moveIndicatorTo(el) {
            if (!el) {
                indicator.style.opacity = '0';
                return;
            }
            indicator.style.top = el.offsetTop + 'px';
            indicator.style.height = el.offsetHeight + 'px';
            indicator.style.opacity = '1';

            const color = el.dataset.color || '#4C6FA5';
            const [r, g, b] = hexToRgb(color);
            indicator.style.background = color;
            indicator.style.boxShadow = `0 0 20px rgba(${r},${g},${b},0.6), 0 0 4px rgba(${r},${g},${b},0.9)`;
        }

        function activeLink() {
            return links.find(el => el.dataset.active === 'true') || null;
        }

        // Place the indicator instantly (no glide) on first load, on
        // whichever link matches the current page.
        indicator.style.transition = 'none';
        moveIndicatorTo(activeLink());
        // Re-enable the glide transition on the next frame, for every
        // move after this one (hover, or a later reposition).
        requestAnimationFrame(() => {
            indicator.style.transition = '';
        });

        links.forEach(link => {
            link.addEventListener('mouseenter', () => moveIndicatorTo(link));
        });

        nav.addEventListener('mouseleave', () => moveIndicatorTo(activeLink()));

        window.addEventListener('resize', () => {
            const wasTransition = indicator.style.transition;
            indicator.style.transition = 'none';
            moveIndicatorTo(document.querySelector('nav.relative [data-nav-link]:hover') || activeLink());
            requestAnimationFrame(() => {
                indicator.style.transition = wasTransition;
            });
        });
    })();
</script>

<script>
    // Remembers how far the sidebar was scrolled, so clicking a link near
    // the bottom (e.g. Membership, Settings) doesn't jump you back to the
    // top on the next page load — each click is a full page reload in this
    // app, so the browser has no memory of scroll position on its own.
    (function () {
        const nav = document.getElementById('sidebar-nav');
        if (!nav) return;

        const STORAGE_KEY = 'craveabs.sidebarScrollTop';

        const saved = sessionStorage.getItem(STORAGE_KEY);
        if (saved !== null) {
            nav.scrollTop = parseInt(saved, 10);
        }

        nav.addEventListener('scroll', () => {
            sessionStorage.setItem(STORAGE_KEY, nav.scrollTop);
        });
    })();
</script>

@stack('scripts')
</body>
</html>