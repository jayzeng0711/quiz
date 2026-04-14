@extends('layouts.app')

@section('title', $resultType->title . ' — 職場溝通風格測驗結果')

@section('content')

{{-- Public share badge --}}
<div class="flex items-center justify-center gap-2 mb-6 text-xs text-slate-400">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
    </svg>
    <span>這是他人分享的測驗結果（公開頁面）</span>
</div>

{{-- Hero --}}
<div class="text-center mb-8">
    <span class="inline-block bg-brand-100 text-brand-600 text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide uppercase">
        職場溝通風格測驗
    </span>
    <h1 class="text-3xl font-bold text-slate-900 mb-1">溝通風格是</h1>
    <h2 class="text-3xl font-bold text-brand-600 mb-5">{{ $resultType->title }}</h2>
</div>

{{-- Type description --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 md:p-8 mb-6">
    <p class="text-slate-600 leading-relaxed text-base">
        {{ $resultType->description }}
    </p>
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
                    {{ $code }}@if ($code === $resultType->code) <span class="ml-1 text-brand-500">✦</span>@endif
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

{{-- Free report content --}}
@if ($resultType->report_content)
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 md:p-8 mb-8
            [&_h2]:text-xl [&_h2]:font-bold [&_h2]:text-slate-900 [&_h2]:mb-3
            [&_h3]:text-base [&_h3]:font-semibold [&_h3]:text-slate-800 [&_h3]:mb-2 [&_h3]:mt-4
            [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-1 [&_li]:text-slate-600">
    {!! $resultType->report_content !!}
</div>
@endif

{{-- Locked paid content teaser --}}
<div class="relative bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-8">
    {{-- Blurred mock content --}}
    <div class="p-6 blur-sm select-none pointer-events-none" aria-hidden="true">
        <h3 class="text-base font-semibold text-slate-800 mb-2">與不同風格同事相處指南</h3>
        <ul class="space-y-1 text-sm text-slate-600 list-disc pl-5">
            <li>與主導型 Driver 相處的技巧...</li>
            <li>與表現型 Expressive 相處的方式...</li>
            <li>個人成長 90 天路線圖...</li>
        </ul>
    </div>
    {{-- Lock overlay --}}
    <div class="absolute inset-0 flex flex-col items-center justify-center bg-white/80 backdrop-blur-[1px]">
        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <p class="text-sm text-slate-500 mb-1">完整版內容</p>
        <p class="text-xs text-slate-400">僅限付費用戶查看</p>
    </div>
</div>

{{-- CTA to take quiz --}}
<div class="bg-gradient-to-r from-brand-500 to-indigo-500 rounded-2xl p-6 text-center text-white">
    <p class="font-semibold text-lg mb-1">想知道你的溝通風格？</p>
    <p class="text-white/80 text-sm mb-4">完成 10 道情境題，立即獲得你的專屬分析</p>
    <a href="{{ route('quiz.show') }}"
       class="inline-block bg-white text-brand-600 font-semibold px-6 py-3 rounded-xl text-sm hover:bg-brand-50 transition-colors">
        立即免費測驗 →
    </a>
</div>

@endsection
