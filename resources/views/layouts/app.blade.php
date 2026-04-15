<!DOCTYPE html>
<html lang="zh-Hant" x-data>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="shortcut icon" href="/favicon.svg">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50:'#f0f4ff',100:'#dde6ff',200:'#c0cffe',300:'#93aafb',400:'#697ef7',500:'#4f6ef7',600:'#3b55e6',700:'#2f45cc' },
                        rose:  { 50:'#fff1f2',100:'#ffe4e6',400:'#fb7185',500:'#f43f5e',600:'#e11d48' },
                        amber: { 50:'#fffbeb',100:'#fef3c7',400:'#fbbf24',500:'#f59e0b',600:'#d97706' },
                        emerald:{ 50:'#ecfdf5',100:'#d1fae5',400:'#34d399',500:'#10b981',600:'#059669' },
                        violet:{ 50:'#f5f3ff',100:'#ede9fe',400:'#a78bfa',500:'#8b5cf6',600:'#7c3aed' },
                    },
                    fontFamily: { sans: ['"Noto Sans TC"','ui-sans-serif','system-ui'] },
                    keyframes: {
                        fadeUp:   { '0%':{ opacity:'0', transform:'translateY(20px)' }, '100%':{ opacity:'1', transform:'translateY(0)' } },
                        fadeIn:   { '0%':{ opacity:'0' }, '100%':{ opacity:'1' } },
                        popIn:    { '0%':{ opacity:'0', transform:'scale(.85)' }, '70%':{ transform:'scale(1.04)' }, '100%':{ opacity:'1', transform:'scale(1)' } },
                        shimmer:  { '0%':{ backgroundPosition:'-200% 0' }, '100%':{ backgroundPosition:'200% 0' } },
                        wiggle:   { '0%,100%':{ transform:'rotate(-2deg)' }, '50%':{ transform:'rotate(2deg)' } },
                        shake:    { '0%,100%':{ transform:'translateX(0)' }, '20%,60%':{ transform:'translateX(-6px)' }, '40%,80%':{ transform:'translateX(6px)' } },
                        pulse2:   { '0%,100%':{ transform:'scale(1)' }, '50%':{ transform:'scale(1.08)' } },
                    },
                    animation: {
                        'fade-up':   'fadeUp .45s ease both',
                        'fade-in':   'fadeIn .35s ease both',
                        'pop-in':    'popIn .4s cubic-bezier(.34,1.56,.64,1) both',
                        'shimmer':   'shimmer 2.5s linear infinite',
                        'wiggle':    'wiggle .6s ease-in-out infinite',
                        'shake':     'shake .4s ease',
                        'pulse2':    'pulse2 2s ease-in-out infinite',
                    },
                }
            }
        }
    </script>

    {{-- Chart.js for radar/spider charts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

    {{-- Alpine.js plugins --}}
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.14.8/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.14.8/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    {{-- canvas-confetti --}}
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;500;600;700;900&display=swap" rel="stylesheet">

    <style>
        [x-cloak]{ display:none !important; }
        .glass{ background:rgba(255,255,255,.72); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px); }
        .gradient-text{ background:linear-gradient(135deg,#4f6ef7,#a78bfa); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

        /* ── Print styles ─────────────────────────────────────────────── */
        @media print {
            /* Reset page background */
            body { background: white !important; }
            /* Hide nav, footer, buttons, action bars */
            header, footer, .no-print,
            [x-show], form, button,
            a[href*="share"], a[href*="quiz.show"],
            a[href*="unlock"], a[href*="start"],
            #ai-card, #ai-generating-card { display: none !important; }
            /* Remove shadows and rounded corners */
            .rounded-3xl, .rounded-2xl, .rounded-xl { border-radius: 8px !important; }
            .shadow-sm, .shadow-md, .shadow-lg { box-shadow: none !important; }
            /* Expand content to full width */
            main { max-width: 100% !important; padding: 0 24px !important; }
            /* Ensure backgrounds print */
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            /* Open all accordion sections */
            [x-collapse] { display: block !important; height: auto !important; overflow: visible !important; }
            /* Page break hints */
            .animate-fade-up, .animate-pop-in { animation: none !important; opacity: 1 !important; }
            /* Hide confetti canvas */
            canvas:not(#radar-chart) { display: none !important; }
        }
    </style>

    @stack('head')
</head>
<body class="min-h-screen font-sans text-slate-800 antialiased"
      style="background: radial-gradient(ellipse at 0% 0%, #e0e7ff 0%, #f8fafc 50%, #fdf2f8 100%)">

    <header class="glass border-b border-white/60 sticky top-0 z-20">
        <div class="max-w-2xl mx-auto px-4 h-14 flex items-center justify-between">
            <a href="{{ route('quiz.show') }}" class="font-bold text-brand-600 tracking-tight text-sm flex items-center gap-1.5">
                <span class="text-lg">✨</span> 心靈測驗
            </a>
            @yield('nav-extra')
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    <footer class="mt-16 border-t border-slate-200/60 py-6 text-center text-xs text-slate-400">
        © {{ date('Y') }} 心靈測驗 &nbsp;·&nbsp; 探索你的內在世界
    </footer>

    @stack('scripts')
</body>
</html>
