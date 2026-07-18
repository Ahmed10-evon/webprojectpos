<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CRAVE ABS') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&family=Noto+Sans+Bengali:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { ink: '#1C1A17', canvas: '#E7DDC7', indigo: '#2B4570', brass: '#A8763B' },
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

        /* The card is a swing tag hanging from a punched hole + loop of string. */
        .tag-string {
            width: 2px;
            height: 34px;
            background: repeating-linear-gradient(180deg, #B9B190 0 4px, transparent 4px 8px);
            margin: 0 auto;
        }
        .tag-hole {
            width: 16px; height: 16px;
            border-radius: 50%;
            background: #E7DDC7;
            border: 2px solid #B9B190;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);
            margin: 0 auto;
        }
        .tag-barcode {
            height: 16px;
            background-image: repeating-linear-gradient(90deg, #1C1A17 0 2px, transparent 2px 4px, #1C1A17 4px 5px, transparent 5px 9px, #1C1A17 9px 12px, transparent 12px 14px);
            opacity: 0.55;
        }
    </style>
</head>
<body class="min-h-screen bg-canvas flex items-center justify-center px-4">
    <div class="w-full max-w-sm">
        <div class="tag-hole"></div>
        <div class="tag-string"></div>

        <div class="bg-[#F7F5EC] border border-[#DCD6C4] rounded-sm p-9 shadow-[0_12px_28px_-8px_rgba(28,26,23,0.25)]">
            <h1 class="font-display text-3xl font-extrabold text-ink text-center tracking-wide">
                CRAVE <span class="text-indigo">ABS</span>
            </h1>
            <p class="text-center text-[10px] uppercase tracking-[0.3em] text-ink/50 mt-1 mb-8">Admin Console</p>

            {{ $slot }}

            <div class="tag-barcode mt-8"></div>
        </div>
    </div>
</body>
</html>
