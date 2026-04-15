@extends('layouts.app')
@section('title', '你的測驗結果 — ' . $resultType->title)

@php
    $isFree        = ($attempt->quiz->price == 0);
    $isPaid        = $isFree || $attempt->hasPaidOrder();
    $order         = $attempt->order;
    $aiAnalysis    = $report->rendered_content['ai_analysis'] ?? '';
    $meta          = $attempt->quiz->meta ?? [];
    $bg            = $meta['card_bg']   ?? 'from-brand-500 to-violet-500';
    $emoji         = $meta['emoji']     ?? '✨';
    $resultEmoji   = $resultType->meta['emoji'] ?? $emoji;

    $totalCompleted = \App\Models\QuizAttempt::whereIn('status', ['completed','paid'])->count();
    $sameTypeCount  = \App\Models\QuizAttempt::where('result_type_id', $resultType->id)->count();
    $sameTypePct    = $totalCompleted > 0 ? round($sameTypeCount / $totalCompleted * 100) : 0;

    $scores         = collect($attempt->score_breakdown ?? [])->sortDesc();
    $secondaryCode  = $scores->keys()->get(1);
    $secondaryType  = $secondaryCode
        ? \App\Models\ResultType::where('quiz_id', $attempt->quiz_id)->where('code', $secondaryCode)->first()
        : null;

    // code → ResultType 對照表（用於分數圖顯示中文）
    $resultTypeMap  = \App\Models\ResultType::where('quiz_id', $attempt->quiz_id)
        ->pluck('title', 'code');
@endphp

@if ($isPaid)
@section('nav-extra')
<div class="flex items-center gap-2 no-print">
    <a href="{{ route('share', ['shareToken' => $report->access_token]) }}" target="_blank"
       class="inline-flex items-center gap-1.5 text-xs text-brand-600 border border-brand-200 bg-brand-50 hover:bg-brand-100 px-3 py-1.5 rounded-full transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
        </svg>
        分享
    </a>
</div>
@endsection
@endif

@section('content')

{{-- Payment success flash --}}
@if (session('payment_success'))
<div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-2xl px-5 py-4 mb-6 text-sm text-green-700 animate-pop-in">
    <svg class="w-5 h-5 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div>
        <span class="font-semibold">付款成功！🎉</span>
        完整報告已解鎖，並寄送至 <strong>{{ $order->email }}</strong>。
    </div>
</div>
@endif

{{-- Social proof --}}
@if ($totalCompleted > 5)
<div class="flex items-center justify-center gap-2 glass border border-white/60 rounded-xl px-4 py-2.5 mb-6 text-xs text-slate-500 animate-fade-in">
    <span>👥</span>
    已有 <strong class="text-slate-700">{{ number_format($totalCompleted) }}</strong> 人完成測驗
</div>
@endif

{{-- Hero --}}
<div class="text-center mb-8 animate-fade-up">
    @if ($isPaid)
    <span class="inline-block bg-gradient-to-r {{ $bg }} text-white text-xs font-semibold px-3 py-1 rounded-full mb-4 shadow-md">
        完整版分析報告 ✦
    </span>
    @endif

    <div class="text-6xl mb-3 animate-pop-in" style="animation-delay:.1s">
        {{ $resultEmoji }}
    </div>

    <p class="text-sm text-slate-400 mb-1">你的結果是</p>
    <h1 class="text-3xl font-black mb-2 animate-fade-up" style="animation-delay:.15s">
        <span class="gradient-text">{{ $resultType->title }}</span>
    </h1>

    @if ($sameTypePct > 0)
    <p class="text-xs text-slate-400 animate-fade-in" style="animation-delay:.3s">
        {{ $sameTypePct }}% 的人和你有相同的結果
    </p>
    @endif
</div>

{{-- Type description --}}
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 mb-5 animate-fade-up" style="animation-delay:.2s">
    <p class="text-slate-600 leading-relaxed text-base">{{ $resultType->description }}</p>
</div>

{{-- ── RADAR CHART — uses same score_breakdown data as bar chart ────── --}}
@php
    // Normalise score_breakdown to 0-100% for the radar axes.
    // The axes are the result type labels, identical to the bar chart below,
    // so all three sections (radar / bars / interaction guide) reference the same types.
    $radarScores = collect($attempt->score_breakdown ?? [])->sortDesc();
    $radarMax    = $radarScores->max() ?: 1;
    $radarData   = $radarScores->map(fn($v) => round($v / $radarMax * 100))->all();
    $hasRadar    = $radarScores->count() >= 3;

    // Assign a distinct colour per result type for the legend dots
    $radarPalette = ['#4f6ef7','#f43f5e','#10b981','#f59e0b','#8b5cf6','#06b6d4','#84cc16','#f97316','#ec4899','#6366f1'];
    $radarColors  = collect($radarScores->keys())->values()
        ->map(fn($code, $i) => $radarPalette[$i % count($radarPalette)])
        ->all();
@endphp
@if ($hasRadar)
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 mb-5 animate-fade-up" style="animation-delay:.22s">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">各風格傾向雷達圖</h3>
        <span class="text-xs text-slate-400">{{ $radarScores->count() }} 種風格</span>
    </div>
    <div class="flex justify-center">
        <canvas id="radar-chart" width="300" height="300" style="max-width:300px;max-height:300px"></canvas>
    </div>
    {{-- Legend: same labels as the bar chart --}}
    <div class="flex flex-wrap justify-center gap-x-4 gap-y-1.5 mt-4">
        @foreach ($radarScores as $code => $score)
        @php $ci = $loop->index; $color = $radarPalette[$ci % count($radarPalette)]; @endphp
        @php
            $fullLabel  = $resultTypeMap[$code] ?? $code;
            $shortLabel = mb_strstr($fullLabel, ' ', true) ?: $fullLabel;
        @endphp
        <div class="flex items-center gap-1.5 text-xs {{ $code === $resultType->code ? 'font-semibold text-slate-700' : 'text-slate-500' }}">
            <span class="w-2.5 h-2.5 rounded-full inline-block shrink-0" style="background:{{ $color }}"></span>
            <span>{{ $fullLabel }}</span>
            <span class="font-semibold">{{ $radarData[$code] ?? 0 }}%</span>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
(function() {
    const ctx = document.getElementById('radar-chart');
    if (!ctx) return;

    // Labels: strip English suffix and emoji so they fit on the radar axes
    // e.g. "親和型 Amiable" → "親和型", "開心果 ☀️" → "開心果"
    const labels  = @json($radarScores->keys()->map(function($c) use ($resultTypeMap) {
        $full = $resultTypeMap[$c] ?? $c;
        // Everything before the first space (removes English / emoji suffix)
        return mb_strstr($full, ' ', true) ?: $full;
    })->values()->all());
    const data    = @json(array_values($radarData));
    const colors  = @json(array_values($radarColors));
    const primary = '{{ $resultType->code }}';
    const primaryIdx = @json($radarScores->keys()->values()->search($resultType->code) ?? 0);

    new Chart(ctx, {
        type: 'radar',
        data: {
            labels,
            datasets: [{
                data,
                backgroundColor: 'rgba(79, 110, 247, 0.12)',
                borderColor:     'rgba(79, 110, 247, 0.75)',
                borderWidth: 2.5,
                pointBackgroundColor: colors,
                pointBorderColor:     '#fff',
                pointBorderWidth: 2,
                pointRadius: (ctx) => ctx.dataIndex === primaryIdx ? 7 : 4,
                pointHoverRadius: 8,
            }],
        },
        options: {
            responsive: false,
            animation: { duration: 900, easing: 'easeOutQuart' },
            scales: {
                r: {
                    min: 0,
                    max: 100,
                    ticks: { stepSize: 25, display: false },
                    grid: { color: 'rgba(148,163,184,.2)' },
                    angleLines: { color: 'rgba(148,163,184,.25)' },
                    pointLabels: {
                        font: { family: "'Noto Sans TC', sans-serif", size: 11, weight: '600' },
                        color: (ctx) => ctx.index === primaryIdx ? '#4f6ef7' : '#475569',
                    },
                },
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => ` ${ctx.raw}%`,
                    },
                },
            },
        },
    });
})();
</script>
@endpush
@endif

{{-- Score breakdown --}}
@if ($attempt->score_breakdown && count($attempt->score_breakdown) > 0)
@php $scoresSorted = collect($attempt->score_breakdown)->sortDesc(); $maxScore = $scoresSorted->max() ?: 1; @endphp
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 mb-5 animate-fade-up" style="animation-delay:.25s"
     x-data="{ visible: false }" x-intersect="visible = true">
    <h3 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-wide">各風格傾向得分</h3>
    <div class="space-y-3">
        @foreach ($scoresSorted as $code => $score)
        @php $w = round(($score / $maxScore) * 100); @endphp
        <div>
            <div class="flex justify-between text-xs text-slate-500 mb-1.5">
                <span class="{{ $code === $resultType->code ? 'font-bold text-brand-600' : '' }}">
                    <span class="text-slate-400 font-normal mr-1">{{ $code }}</span>{{ $resultTypeMap[$code] ?? '' }}
                    @if ($code === $resultType->code)<span class="ml-1">✦</span>@endif
                </span>
                <span>{{ $score }}</span>
            </div>
            <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-1000 ease-out bg-gradient-to-r {{ $code === $resultType->code ? $bg : 'from-slate-300 to-slate-200' }}"
                     :style="visible ? 'width:{{ $w }}%' : 'width:0%'">
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Base report content — show ~1/3, blur the rest unless paid --}}
@if ($resultType->report_content)
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 mb-5 animate-fade-up relative overflow-hidden"
     style="animation-delay:.3s;">

    <div class="[&_h2]:text-xl [&_h2]:font-bold [&_h2]:text-slate-900 [&_h2]:mb-3
                [&_h3]:text-base [&_h3]:font-semibold [&_h3]:text-slate-800 [&_h3]:mb-2 [&_h3]:mt-4
                [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-1 [&_li]:text-slate-600 [&_p]:text-slate-600 [&_p]:leading-relaxed [&_p]:mb-3">
        {!! $resultType->report_content !!}
    </div>

    @if (! $isPaid)
    {{-- Clean gradient fade + lock pill --}}
    <div class="absolute inset-x-0 bottom-0 flex flex-col items-center justify-end pb-5"
         style="height: 65%;
                background: linear-gradient(
                    to bottom,
                    rgba(255,255,255,0)    0%,
                    rgba(255,255,255,0.75) 35%,
                    rgba(255,255,255,1)    65%,
                    rgba(255,255,255,1)    100%
                );">
        {{-- Lock pill --}}
        <a href="{{ route('quiz.attempt.unlock', ['token' => $attempt->session_token]) }}"
           class="inline-flex items-center gap-2 bg-white border border-slate-200 shadow-md
                  text-slate-600 text-xs font-semibold px-4 py-2.5 rounded-full
                  hover:border-brand-400 hover:text-brand-600 hover:shadow-lg
                  transition-all duration-200 cursor-pointer">
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            解鎖閱讀完整報告
        </a>
    </div>
    @endif
</div>
@endif

{{-- ── PAID ─────────────────────────────────────────────── --}}
@if ($isPaid)

    {{-- AI personalised analysis --}}
    @if ($aiAnalysis)
    <div class="rounded-3xl border border-brand-100 overflow-hidden mb-5 animate-pop-in" style="animation-delay:.35s">
        <div class="bg-gradient-to-r {{ $bg }} px-6 py-4 flex items-center gap-2">
            <span class="text-white text-lg">✦</span>
            <span class="text-white font-bold text-sm">AI 個人化洞察</span>
            <span class="ml-auto text-xs bg-white/20 text-white px-2 py-0.5 rounded-full">專屬分析</span>
        </div>
        <div class="bg-gradient-to-br from-brand-50 to-violet-50 p-6">
            <div class="text-slate-700 leading-relaxed text-sm whitespace-pre-line">{{ $aiAnalysis }}</div>
        </div>
    </div>
    @else
    {{-- AI generating — trigger via AJAX, no full-page refresh loop --}}
    <div class="rounded-3xl border border-brand-100 overflow-hidden mb-5 animate-fade-in"
         id="ai-card">
        <div class="bg-gradient-to-r {{ $bg }} px-6 py-4 flex items-center gap-2">
            <span class="text-white text-lg" id="ai-icon">⏳</span>
            <span class="text-white font-bold text-sm">AI 個人化洞察</span>
            <span class="ml-auto text-xs bg-white/20 text-white px-2 py-0.5 rounded-full" id="ai-status-badge">生成中...</span>
        </div>
        <div class="bg-gradient-to-br from-brand-50 to-violet-50 p-6 text-center" id="ai-body">
            <div class="flex items-center justify-center gap-2 text-slate-400 text-sm mb-2">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                AI 正在分析你的作答模式...
            </div>
            <p class="text-xs text-slate-300">通常在 15 秒內完成</p>
        </div>
    </div>

    @push('scripts')
    <script>
    (function() {
        const endpoint = '{{ route('quiz.attempt.generate-ai', ['token' => $attempt->session_token]) }}';
        const csrf     = document.querySelector('meta[name="csrf-token"]').content;
        let   attempts = 0;
        const maxAttempts = 5;

        function tryGenerate() {
            attempts++;
            fetch(endpoint, {
                method:  'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'done' && data.ai_analysis) {
                    // Show AI content inline, no page reload needed
                    const body   = document.getElementById('ai-body');
                    const badge  = document.getElementById('ai-status-badge');
                    const icon   = document.getElementById('ai-icon');
                    badge.textContent = '專屬分析';
                    icon.textContent  = '✦';
                    body.className    = 'bg-gradient-to-br from-brand-50 to-violet-50 p-6';
                    body.innerHTML    = '<div class="text-slate-700 leading-relaxed text-sm whitespace-pre-line">' +
                                        data.ai_analysis.replace(/</g,'&lt;').replace(/>/g,'&gt;') +
                                        '</div>';
                } else if (attempts < maxAttempts) {
                    // Not ready yet — retry after 8 seconds
                    setTimeout(tryGenerate, 8000);
                } else {
                    // Give up gracefully
                    document.getElementById('ai-body').innerHTML =
                        '<p class="text-xs text-slate-400 text-center">AI 分析暫時無法生成，請稍後重新整理頁面。</p>';
                    document.getElementById('ai-status-badge').textContent = '稍後再試';
                }
            })
            .catch(() => {
                if (attempts < maxAttempts) setTimeout(tryGenerate, 10000);
            });
        }

        // Start immediately
        tryGenerate();
    })();
    </script>
    @endpush
    @endif

    {{-- Extended sections (accordion) — content from DB meta --}}
    @php
        $extSections = $resultType->meta['extended_sections'] ?? null;
        $defaultSections = [
            'strength' => ['icon'=>'💪','title'=>'你的優勢組合'],
            'growth'   => ['icon'=>'🌱','title'=>'個人成長路線圖'],
        ];

        // Dynamic guide: load interaction_tip for each OTHER type in score_breakdown.
        // Only types that actually appeared in this session are shown — perfectly
        // aligned with the radar chart and bar chart above.
        $guideTypes = \App\Models\ResultType::where('quiz_id', $attempt->quiz_id)
            ->whereIn('code', array_keys($attempt->score_breakdown ?? []))
            ->where('code', '!=', $resultType->code)
            ->get()
            ->sortByDesc(fn($t) => $attempt->score_breakdown[$t->code] ?? 0)
            ->values();
    @endphp
    <div class="space-y-3 mb-5 animate-fade-up" style="animation-delay:.4s"
         x-data="{ openSection: null }">

        {{-- Strength section --}}
        @foreach (['strength','growth'] as $skey)
        @php
            $sec     = $extSections[$skey] ?? $defaultSections[$skey] ?? [];
            $icon    = $sec['icon']    ?? '✦';
            $title   = $sec['title']   ?? $skey;
            $content = $sec['content'] ?? '';
        @endphp
        @if ($content)
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
            <button @click="openSection = openSection === '{{ $skey }}' ? null : '{{ $skey }}'"
                    class="w-full flex items-center justify-between px-5 py-4 text-left">
                <div class="flex items-center gap-2.5">
                    <span class="text-xl">{{ $icon }}</span>
                    <span class="font-semibold text-slate-800 text-sm">{{ $title }}</span>
                </div>
                <svg class="w-4 h-4 text-slate-400 transition-transform duration-300"
                     :class="openSection === '{{ $skey }}' ? 'rotate-180' : ''"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="openSection === '{{ $skey }}'" x-collapse
                 class="px-5 pb-5 text-sm text-slate-600 leading-relaxed
                        [&_ul]:space-y-2.5 [&_ul]:mt-1 [&_li]:leading-relaxed
                        [&_p]:mb-2 [&_strong]:text-slate-800 [&_p]:leading-relaxed">
                {!! $content !!}
            </div>
        </div>
        @endif
        @endforeach

        {{-- Dynamic guide: only types that actually appeared in this session --}}
        @if ($guideTypes->isNotEmpty())
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
            <button @click="openSection = openSection === 'guide' ? null : 'guide'"
                    class="w-full flex items-center justify-between px-5 py-4 text-left">
                <div class="flex items-center gap-2.5">
                    <span class="text-xl">🤝</span>
                    <span class="font-semibold text-slate-800 text-sm">
                        與這次測驗中出現的其他類型相處
                    </span>
                </div>
                <svg class="w-4 h-4 text-slate-400 transition-transform duration-300"
                     :class="openSection === 'guide' ? 'rotate-180' : ''"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="openSection === 'guide'" x-collapse class="px-5 pb-5">
                <p class="text-xs text-slate-400 mb-4">
                    根據你這次的作答，以下是你實際遇到的其他類型以及與他們相處的核心提示：
                </p>
                <div class="space-y-3">
                    @foreach ($guideTypes as $gt)
                    @php
                        $gtScore = $attempt->score_breakdown[$gt->code] ?? 0;
                        $gtPct   = $radarMax > 0 ? round($gtScore / $radarMax * 100) : 0;
                        $gtShort = mb_strstr($gt->title, ' ', true) ?: $gt->title;
                        $gtTip   = $gt->meta['interaction_tip'] ?? null;
                        $gtIdx   = $radarScores->keys()->search($gt->code);
                        $gtColor = $radarPalette[$gtIdx % count($radarPalette)] ?? '#94a3b8';
                    @endphp
                    @if ($gtTip)
                    <div class="flex gap-3 items-start">
                        <span class="w-2.5 h-2.5 rounded-full mt-1.5 shrink-0"
                              style="background:{{ $gtColor }}"></span>
                        <div class="text-sm">
                            <span class="font-semibold text-slate-800">{{ $gt->title }}</span>
                            <span class="text-slate-400 text-xs ml-1">({{ $gtPct }}%)</span>
                            <p class="text-slate-600 mt-0.5">{{ $gtTip }}</p>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Order info --}}
    @if ($order)
    <div class="bg-slate-50 rounded-2xl border border-slate-100 p-4 mb-5 text-xs text-slate-400 flex justify-between">
        <span>訂單：<span class="font-mono text-slate-500">{{ $order->order_number }}</span></span>
        <span>{{ $order->paid_at?->format('Y/m/d') }}</span>
    </div>
    @endif

    {{-- Actions --}}
    <div class="flex flex-col gap-3 animate-fade-up no-print" style="animation-delay:.45s">
        <a href="{{ route('share', ['shareToken' => $report->access_token]) }}" target="_blank"
           class="block text-center py-3.5 rounded-2xl border-2 border-brand-400 text-brand-600 text-sm font-bold hover:bg-brand-50 transition-all active:scale-95">
            分享結果頁 🔗
        </a>
        <a href="{{ route('quiz.show') }}"
           class="block text-center py-3 rounded-2xl border border-slate-200 text-slate-400 text-sm hover:bg-white transition-all">
            試試其他測驗
        </a>
    </div>

{{-- ── NOT PAID ──────────────────────────────────────────── --}}
@else

    {{-- Secondary type teaser --}}
    @if ($secondaryType)
    <div class="relative bg-white rounded-3xl border border-brand-100 shadow-sm overflow-hidden mb-5 animate-fade-up" style="animation-delay:.35s">
        <div class="p-5">
            <p class="text-xs font-bold text-brand-500 uppercase tracking-wide mb-2">次要結果類型</p>
            <p class="font-black text-slate-800 text-lg mb-1">{{ $secondaryType->title }}</p>
            <p class="text-slate-400 text-sm blur-sm select-none">{{ $secondaryType->description }}</p>
        </div>
        <div class="absolute inset-0 flex items-center justify-center bg-white/70 backdrop-blur-[2px]">
            <div class="text-center">
                <div class="text-3xl mb-1">🔒</div>
                <p class="text-xs text-slate-500 font-medium">解鎖完整版查看</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Unlock CTA --}}
    <div class="bg-gradient-to-br from-brand-50 via-violet-50 to-pink-50 rounded-3xl border border-brand-100 p-6 mb-4 text-center animate-pop-in" style="animation-delay:.4s">
        <div class="text-3xl mb-2">✨</div>
        <p class="text-sm font-bold text-slate-700 mb-1">解鎖你的專屬完整報告</p>
        <p class="text-xs text-slate-500 mb-4 leading-relaxed">
            包含 AI 個人化洞察、次要類型分析<br>職場相處指南與 90 天成長路線圖
        </p>
        <a href="{{ route('quiz.attempt.unlock', ['token' => $attempt->session_token]) }}"
           class="inline-block bg-gradient-to-r {{ $bg }} text-white font-bold px-8 py-3.5 rounded-2xl text-sm transition-all active:scale-95 shadow-lg">
            解鎖完整版 NT${{ number_format($attempt->quiz->price) }} →
        </a>
        <p class="text-xs text-slate-400 mt-3">一次付清，報告永久保存</p>
    </div>

    <a href="{{ route('quiz.show') }}"
       class="block text-center py-3 rounded-2xl border border-slate-200 text-slate-400 text-sm hover:bg-white transition-all mb-2">
        試試其他測驗
    </a>

@endif

@endsection

@push('scripts')
<script>
// Confetti on result reveal (only once per visit)
document.addEventListener('DOMContentLoaded', function() {
    if (!sessionStorage.getItem('confetti_{{ $attempt->session_token }}')) {
        sessionStorage.setItem('confetti_{{ $attempt->session_token }}', '1');
        setTimeout(() => {
            confetti({
                particleCount: 120,
                spread: 80,
                origin: { y: 0.55 },
                colors: ['#4f6ef7','#a78bfa','#f43f5e','#fbbf24','#34d399'],
                scalar: 1.1,
            });
            setTimeout(() => {
                confetti({ particleCount: 60, spread: 50, origin: { y: 0.5, x: 0.1 } });
                confetti({ particleCount: 60, spread: 50, origin: { y: 0.5, x: 0.9 } });
            }, 300);
        }, 400);
    }
});

// Before printing: open all accordion sections, fix heights
window.addEventListener('beforeprint', function() {
    // Force-open all x-collapse sections
    document.querySelectorAll('[x-collapse], [x-show]').forEach(el => {
        el.style.display = 'block';
        el.style.height  = 'auto';
        el.style.overflow = 'visible';
        el.style.opacity = '1';
    });
    // Remove animations
    document.querySelectorAll('.animate-fade-up, .animate-pop-in, .animate-fade-in').forEach(el => {
        el.style.animation = 'none';
        el.style.opacity = '1';
        el.style.transform = 'none';
    });
});
</script>
@endpush
