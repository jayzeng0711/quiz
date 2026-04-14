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

<div x-data="{
    selected: '{{ $existingAnswer ? ($existingAnswer->selected_options[0] ?? '') : '' }}',
    direction: 'next',
    submitting: false,
    shake: false,
    submit(dir) {
        if (!this.selected) {
            this.shake = true;
            setTimeout(() => this.shake = false, 450);
            return;
        }
        this.direction = dir;
        this.submitting = true;
        this.$refs.dirInput.value = dir;
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
