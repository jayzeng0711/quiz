@extends('layouts.app')
@section('title', '心靈測驗 — 探索你的內在世界')

@section('content')

{{-- Hero --}}
<div class="text-center mb-10 animate-fade-up">
    <div class="text-5xl mb-4 animate-wiggle inline-block">✨</div>
    <h1 class="text-3xl font-black text-slate-900 mb-3 leading-tight">
        探索你的<span class="gradient-text">內在世界</span>
    </h1>
    <p class="text-slate-500 text-sm leading-relaxed max-w-xs mx-auto">
        透過隨機情境題，發現最真實的自己
    </p>
</div>

{{-- Collection cards --}}
<div class="space-y-4" x-data="{ open: null }">
    @foreach ($collections as $col)
    @php $i = $loop->index; @endphp

    <div class="animate-fade-up" style="animation-delay:{{ $i * 80 }}ms">

        {{-- Collection card header (click to toggle) --}}
        <div class="rounded-3xl overflow-hidden shadow-sm cursor-pointer
                    transition-all duration-300 hover:shadow-lg"
             @click="open = (open === '{{ $col['slug'] }}') ? null : '{{ $col['slug'] }}'">

            <div class="bg-gradient-to-r {{ $col['bg'] }} p-5 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-white/10"></div>
                <div class="absolute right-6 -bottom-4 w-14 h-14 rounded-full bg-white/10"></div>

                <div class="flex items-center justify-between relative">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">{{ $col['emoji'] }}</span>
                        <div>
                            <h2 class="text-white font-black text-lg leading-tight">{{ $col['title'] }}</h2>
                            <p class="text-white/75 text-xs mt-0.5">{{ $col['desc'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-xs text-white/70 bg-white/15 px-2.5 py-1 rounded-full">
                            {{ $col['quizzes']->count() }} 套
                        </span>
                        <svg class="w-5 h-5 text-white transition-transform duration-300"
                             :class="open === '{{ $col['slug'] }}' ? 'rotate-180' : ''"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Expanded sub-quizzes --}}
        <div x-show="open === '{{ $col['slug'] }}'"
             x-collapse
             class="mt-2 space-y-2">
            @foreach ($col['quizzes'] as $quiz)
            @php
                $qMeta  = $quiz->meta ?? [];
                $emoji  = $qMeta['emoji'] ?? '🧩';
                $tag    = $qMeta['tag'] ?? '測驗';
                $mins   = $qMeta['estimated_minutes'] ?? 5;
                $dims   = count($qMeta['dimensions'] ?? []);
            @endphp
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden
                        transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                 x-data="{ hov: false }" @mouseenter="hov=true" @mouseleave="hov=false">
                <div class="flex items-center gap-4 px-5 py-4">
                    <span class="text-3xl shrink-0" :class="hov ? 'animate-pulse2' : ''">{{ $emoji }}</span>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">{{ $tag }}</span>
                        </div>
                        <h3 class="font-bold text-slate-800 text-sm leading-tight">{{ $quiz->title }}</h3>
                    </div>
                    <form action="{{ route('quiz.start') }}" method="POST" class="shrink-0">
                        @csrf
                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                        <button type="submit"
                            class="bg-gradient-to-r {{ $col['bg'] }} text-white text-xs font-bold
                                   px-4 py-2 rounded-full shadow transition-transform active:scale-95"
                            :class="hov ? 'scale-105' : ''">
                            開始 →
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

    </div>
    @endforeach
</div>

<p class="text-center text-xs text-slate-400 mt-8">
    所有測驗答案僅供自我探索參考 🌱
</p>

@endsection
