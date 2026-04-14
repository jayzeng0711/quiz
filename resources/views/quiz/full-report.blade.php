@extends('layouts.app')

@section('title', '完整分析報告 — ' . $resultType->title)

@section('nav-extra')
<a href="{{ route('share', ['shareToken' => $report->access_token]) }}"
   target="_blank"
   class="inline-flex items-center gap-1.5 text-xs text-brand-600 border border-brand-200 bg-brand-50 hover:bg-brand-100 px-3 py-1.5 rounded-full transition-colors">
    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
    </svg>
    分享
</a>
@endsection

@section('content')

{{-- Payment success flash --}}
@if (session('payment_success'))
<div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-2xl px-5 py-4 mb-6 text-sm text-green-700">
    <svg class="w-5 h-5 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div>
        <span class="font-semibold">付款成功！</span>
        完整報告已解鎖，並寄送至 <strong>{{ $order->email }}</strong>。
    </div>
</div>
@endif

{{-- Hero --}}
<div class="text-center mb-8">
    <span class="inline-block bg-brand-100 text-brand-600 text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide">
        完整版分析報告
    </span>
    <h1 class="text-3xl font-bold text-slate-900 mb-1">你的溝通風格是</h1>
    <h2 class="text-3xl font-bold text-brand-600 mb-4">{{ $resultType->title }}</h2>
    <p class="text-slate-500 text-sm max-w-sm mx-auto">{{ $resultType->description }}</p>
</div>

{{-- Score breakdown --}}
@if ($attempt->score_breakdown && count($attempt->score_breakdown) > 0)
@php $scores = collect($attempt->score_breakdown)->sortDesc(); $maxScore = $scores->max() ?: 1; @endphp
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
    <h3 class="font-semibold text-slate-800 mb-4 text-sm uppercase tracking-wide">各維度得分</h3>
    <div class="space-y-3">
        @foreach ($scores as $code => $score)
        <div>
            <div class="flex justify-between text-xs text-slate-500 mb-1">
                <span class="{{ $code === $resultType->code ? 'font-semibold text-brand-600' : '' }}">
                    {{ $code }}@if ($code === $resultType->code) <span class="ml-1">✦</span>@endif
                </span>
                <span>{{ $score }}</span>
            </div>
            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-700 {{ $code === $resultType->code ? 'bg-brand-500' : 'bg-slate-300' }}"
                     style="width:{{ round(($score / $maxScore) * 100) }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- AI personalised analysis (paid-only) --}}
@php $aiAnalysis = $report->rendered_content['ai_analysis'] ?? ''; @endphp
@if ($aiAnalysis)
<div class="bg-gradient-to-br from-brand-50 to-indigo-50 rounded-2xl border border-brand-100 p-6 md:p-8 mb-6">
    <div class="flex items-center gap-2 mb-4">
        <span class="text-brand-500 text-xl">✦</span>
        <h3 class="font-bold text-slate-800 text-base">AI 個人化洞察</h3>
        <span class="text-xs text-brand-400 bg-white border border-brand-200 px-2 py-0.5 rounded-full ml-auto">專屬分析</span>
    </div>
    <div class="text-slate-700 leading-relaxed text-sm whitespace-pre-line">{{ $aiAnalysis }}</div>
</div>
@endif

{{-- Full report content --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 md:p-8 mb-6
            [&_h2]:text-xl [&_h2]:font-bold [&_h2]:text-slate-900 [&_h2]:mb-3 [&_h2]:mt-0
            [&_h3]:text-base [&_h3]:font-semibold [&_h3]:text-slate-800 [&_h3]:mb-2 [&_h3]:mt-6
            [&_h4]:text-sm [&_h4]:font-semibold [&_h4]:text-brand-600 [&_h4]:mb-2 [&_h4]:mt-4
            [&_p]:text-slate-600 [&_p]:leading-relaxed [&_p]:mb-3
            [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-1.5 [&_li]:text-slate-600 [&_li]:text-sm
            [&_blockquote]:border-l-4 [&_blockquote]:border-brand-300 [&_blockquote]:pl-4 [&_blockquote]:italic [&_blockquote]:text-slate-500">
    {!! $resultType->report_content !!}

    {{-- Extended paid-only sections --}}
    <hr class="border-slate-100 my-6">

    <h3>你在職場中的優勢組合</h3>
    <p>身為「{{ $resultType->title }}」，你的溝通優勢在於能夠快速識別情境，並採取最適合當下的溝通策略。這讓你在複雜的職場關係中如魚得水。</p>

    <h3>與不同風格同事相處指南</h3>
    <ul>
        <li><strong>與主導型 Driver 相處：</strong>保持簡潔直接，提前準備好數據和結論，避免繞圈子。</li>
        <li><strong>與表現型 Expressive 相處：</strong>給予他們表達的空間，對他們的想法給予正向回應，再引導聚焦。</li>
        <li><strong>與親和型 Amiable 相處：</strong>先建立關係和信任，避免太快進入議題，給他們感到安全的環境。</li>
        <li><strong>與分析型 Analytical 相處：</strong>提前給予完整資料，給他們充裕的思考時間，不要催促決策。</li>
    </ul>

    <h3>個人成長路線圖</h3>
    <p>每個溝通風格都有其天賦盲點。以下是針對你的類型設計的 90 天成長計畫：</p>
    <ul>
        <li><strong>第 1-30 天：</strong>觀察自己在壓力下的溝通習慣，記錄每天一個溝通時刻。</li>
        <li><strong>第 31-60 天：</strong>刻意練習你較弱的溝通面向，嘗試不同的開場方式。</li>
        <li><strong>第 61-90 天：</strong>找一位信任的同事分享你的成長目標，互相回饋進展。</li>
    </ul>
</div>

{{-- Order info --}}
<div class="bg-slate-50 rounded-2xl border border-slate-100 p-5 mb-6 text-xs text-slate-400 flex justify-between items-center">
    <span>訂單編號：<span class="font-mono text-slate-500">{{ $order?->order_number }}</span></span>
    <span>付款時間：{{ $order?->paid_at?->format('Y/m/d') }}</span>
</div>

{{-- Actions --}}
<div class="flex flex-col gap-3">
    <a href="{{ route('share', ['shareToken' => $report->access_token]) }}"
       target="_blank"
       class="block text-center py-3 rounded-xl border-2 border-brand-500 text-brand-600 text-sm font-semibold hover:bg-brand-50 transition-colors">
        分享免費結果頁
    </a>
    <a href="{{ route('quiz.show') }}"
       class="block text-center py-3 rounded-xl border border-slate-200 text-slate-500 text-sm hover:bg-slate-50 transition-colors">
        回首頁
    </a>
</div>

@endsection
