@extends('layouts.app')
@section('title', "第 {$questionNumber} 題 — {$attempt->quiz->title}")

@php
    $meta  = $attempt->quiz->meta ?? [];
    $bg    = $meta['card_bg']    ?? 'from-brand-500 to-violet-500';
    $emoji = $meta['emoji']      ?? '🧩';
    $pct   = round(($questionNumber / $total) * 100);
@endphp

@push('head')
<style>
  .option-card {
    transition: all .2s cubic-bezier(.34,1.56,.64,1);
    cursor: pointer;
  }
  .option-card:hover { transform: translateY(-2px) scale(1.01); }
  .option-card:active { transform: scale(.97); }
  .option-card.selected {
    border-color: transparent !important;
    transform: translateY(-2px) scale(1.01);
  }
  @keyframes slideInRight {
    from { opacity:0; transform:translateX(40px); }
    to   { opacity:1; transform:translateX(0); }
  }
  @keyframes slideInLeft {
    from { opacity:0; transform:translateX(-40px); }
    to   { opacity:1; transform:translateX(0); }
  }
  .slide-in { animation: slideInRight .35s ease both; }
</style>
@endpush

@section('nav-extra')
<span class="text-xs text-slate-400 font-medium">{{ $questionNumber }} / {{ $total }}</span>
@endsection

@section('content')

{{-- 過場動畫 overlay（只在最後一題提交時顯示）--}}
@if ($questionNumber === $total)
<div id="generating-overlay"
     style="display:none; position:fixed; inset:0; z-index:9999;
            background: linear-gradient(135deg, #4f6ef7 0%, #7c3aed 50%, #ec4899 100%);"
     class="flex flex-col items-center justify-center">

    {{-- 動態粒子背景 --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        @for ($p = 0; $p < 12; $p++)
        <div class="absolute rounded-full bg-white/10 animate-pulse"
             style="width: {{ rand(40,120) }}px; height: {{ rand(40,120) }}px;
                    top: {{ rand(0,90) }}%; left: {{ rand(0,90) }}%;
                    animation-delay: {{ $p * 0.3 }}s; animation-duration: {{ rand(2,4) }}s;">
        </div>
        @endfor
    </div>

    {{-- 主體內容 --}}
    <div class="relative text-center px-8">
        {{-- 旋轉星星 --}}
        <div class="text-6xl mb-6 inline-block" style="animation: spin 3s linear infinite;">✨</div>

        <h2 class="text-white font-black text-2xl mb-3">正在分析你的答案</h2>
        <p class="text-white/80 text-sm leading-relaxed mb-8">
            AI 正在為你生成專屬洞察<br>
            這通常需要 10–20 秒，請稍候...
        </p>

        {{-- 進度條動畫 --}}
        <div class="w-64 h-1.5 bg-white/20 rounded-full overflow-hidden mx-auto mb-6">
            <div id="progress-bar"
                 class="h-full bg-white rounded-full"
                 style="width:0%; transition: width 18s cubic-bezier(0.1,0.4,0.8,1);">
            </div>
        </div>

        {{-- 輪播文字 --}}
        <div class="text-white/70 text-xs" id="tip-text">分析你的性格模式中...</div>
    </div>
</div>

<style>
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>

<script>
(function() {
    var tips = [
        '分析你的性格模式中...',
        '比對你的行為傾向...',
        '理解你的內在動力...',
        '生成專屬洞察報告...',
        '整理你的優勢與盲點...',
        '即將完成，再等一下...',
    ];
    var tipIdx = 0;

    window.__showGeneratingOverlay = function() {
        var overlay = document.getElementById('generating-overlay');
        overlay.style.display = 'flex';

        // 啟動進度條
        setTimeout(function() {
            document.getElementById('progress-bar').style.width = '92%';
        }, 100);

        // 輪播提示文字
        setInterval(function() {
            tipIdx = (tipIdx + 1) % tips.length;
            var el = document.getElementById('tip-text');
            el.style.opacity = '0';
            setTimeout(function() {
                el.textContent = tips[tipIdx];
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '1';
            }, 300);
        }, 3000);
    };
})();
</script>
@endif

<div x-data="{
    selected: '{{ $existingAnswer ? ($existingAnswer->selected_options[0] ?? '') : '' }}',
    direction: 'next',
    submitting: false,
    shake: false,
    isLast: {{ $questionNumber === $total ? 'true' : 'false' }},
    submit(dir) {
        if (!this.selected) {
            this.shake = true;
            setTimeout(() => this.shake = false, 450);
            return;
        }
        this.direction = dir;
        this.submitting = true;
        this.$refs.dirInput.value = dir;
        if (this.isLast && dir === 'next' && typeof window.__showGeneratingOverlay === 'function') {
            window.__showGeneratingOverlay();
        }
        this.$refs.form.submit();
    }
}" x-cloak>

{{-- Progress bar --}}
<div class="mb-6">
    <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
        <span class="flex items-center gap-1.5">
            <span>{{ $emoji }}</span>
            <span class="font-medium text-slate-600">{{ $attempt->quiz->title }}</span>
        </span>
        <span>{{ $pct }}%</span>
    </div>
    <div class="h-2.5 bg-slate-200 rounded-full overflow-hidden">
        <div class="h-full bg-gradient-to-r {{ $bg }} rounded-full transition-all duration-700 ease-out"
             style="width: {{ $pct }}%">
        </div>
    </div>
    {{-- Step dots --}}
    <div class="flex justify-between mt-2">
        @for ($i = 1; $i <= $total; $i++)
        <div class="w-1.5 h-1.5 rounded-full transition-all duration-300
                    {{ $i < $questionNumber ? 'bg-brand-400' : ($i === $questionNumber ? 'bg-brand-500 scale-150' : 'bg-slate-200') }}">
        </div>
        @endfor
    </div>
</div>

{{-- Question card --}}
<div class="slide-in">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-5">
        {{-- Card header --}}
        <div class="bg-gradient-to-r {{ $bg }} px-6 pt-6 pb-5 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full bg-white/10"></div>
            <div class="flex items-center gap-2 mb-3">
                <span class="bg-white/20 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                    情境 {{ $questionNumber }}
                </span>
            </div>
            <p class="text-white font-semibold text-base leading-relaxed relative">
                {{ $question->body }}
            </p>
        </div>

        {{-- Options --}}
        <div class="p-4 space-y-2.5" :class="shake ? 'animate-shake' : ''">

            @if ($errors->has('selected_options'))
            <div class="text-xs text-red-500 bg-red-50 border border-red-200 rounded-xl px-4 py-2 mb-1">
                {{ $errors->first('selected_options') }}
            </div>
            @endif

            <form id="answer-form" x-ref="form"
                  action="{{ route('quiz.attempt.answer', ['token' => $attempt->session_token, 'question' => $questionNumber]) }}"
                  method="POST">
                @csrf
                <input type="hidden" name="direction" x-ref="dirInput" value="next">

                <div class="space-y-2.5">
                    @foreach ($question->options as $idx => $option)
                    @php $letter = chr(65 + $idx); @endphp
                    <label
                        @click="selected = '{{ $option['key'] }}'"
                        :class="selected === '{{ $option['key'] }}'
                            ? 'selected bg-gradient-to-r {{ $bg }} shadow-md'
                            : 'bg-slate-50 border-2 border-slate-100 hover:border-slate-300'"
                        class="option-card flex items-center gap-3 p-4 rounded-2xl"
                        style="animation-delay: {{ $idx * 60 }}ms"
                    >
                        <input type="radio"
                               name="selected_options[]"
                               value="{{ $option['key'] }}"
                               class="sr-only"
                               x-model="selected"
                               {{ ($existingAnswer && in_array($option['key'], $existingAnswer->selected_options ?? [])) ? 'checked' : '' }}>

                        {{-- Letter badge --}}
                        <span class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black shrink-0 transition-all"
                              :class="selected === '{{ $option['key'] }}' ? 'bg-white/25 text-white' : 'bg-white text-slate-400 border border-slate-200'">
                            {{ $letter }}
                        </span>

                        <span class="text-sm leading-relaxed transition-colors"
                              :class="selected === '{{ $option['key'] }}' ? 'text-white font-medium' : 'text-slate-700'">
                            {{ $option['label'] }}
                        </span>

                        {{-- Check icon --}}
                        <span class="ml-auto shrink-0" x-show="selected === '{{ $option['key'] }}'">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                    </label>
                    @endforeach
                </div>
            </form>

            {{-- Error hint --}}
            <p x-show="shake" x-cloak class="text-xs text-red-400 text-center pt-1">
                請先選擇一個選項再繼續 👆
            </p>
        </div>
    </div>

    {{-- Navigation --}}
    <div class="flex gap-3">
        @if ($questionNumber > 1)
        <button @click="submit('prev')"
                type="button"
                class="flex-1 py-3.5 rounded-2xl border-2 border-slate-200 text-slate-500 text-sm font-semibold hover:border-slate-300 hover:bg-white transition-all active:scale-95">
            ← 上一題
        </button>
        @endif

        <button @click="submit('next')"
                type="button"
                :disabled="submitting"
                class="flex-1 py-3.5 rounded-2xl bg-gradient-to-r {{ $bg }} text-white text-sm font-bold shadow-lg transition-all active:scale-95 disabled:opacity-60 relative overflow-hidden">
            <span x-show="!submitting">
                {{ $questionNumber === $total ? '查看結果 🎉' : '下一題 →' }}
            </span>
            <span x-show="submitting" x-cloak class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                處理中...
            </span>
        </button>
    </div>

</div><!-- slide-in -->
</div><!-- x-data -->

@endsection
