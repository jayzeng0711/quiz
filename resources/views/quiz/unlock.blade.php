@extends('layouts.app')

@section('title', '解鎖完整版報告')

@section('content')

{{-- Header --}}
<div class="text-center mb-8">
    <span class="inline-block bg-amber-100 text-amber-700 text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide uppercase">
        完整版報告
    </span>
    <h1 class="text-2xl font-bold text-slate-900 mb-2">深度解析你的溝通風格</h1>
    <p class="text-slate-500 text-sm">解鎖針對「{{ $attempt->resultType->title }}」的專屬深度報告</p>
</div>

{{-- Free vs Paid comparison --}}
<div class="grid grid-cols-2 gap-3 mb-8">
    {{-- Free --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">免費版</div>
        <ul class="space-y-2.5">
            @foreach ([
                ['text' => '溝通風格類型', 'ok' => true],
                ['text' => '類型簡介', 'ok' => true],
                ['text' => '各維度得分', 'ok' => true],
                ['text' => '深度特質分析', 'ok' => false],
                ['text' => '職場應用建議', 'ok' => false],
                ['text' => '與各類型相處指南', 'ok' => false],
                ['text' => '個人成長路線圖', 'ok' => false],
            ] as $item)
            <li class="flex items-center gap-2.5 text-sm {{ $item['ok'] ? 'text-slate-600' : 'text-slate-300' }}">
                @if ($item['ok'])
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                @else
                    <svg class="w-4 h-4 text-slate-200 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                @endif
                {{ $item['text'] }}
            </li>
            @endforeach
        </ul>
    </div>

    {{-- Paid --}}
    <div class="bg-gradient-to-b from-brand-50 to-white rounded-2xl border-2 border-brand-400 p-5 relative">
        <div class="absolute -top-3 left-1/2 -translate-x-1/2">
            <span class="bg-brand-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">完整版</span>
        </div>
        <div class="text-xs font-semibold text-brand-500 uppercase tracking-wide mb-3 mt-1">解鎖後</div>
        <ul class="space-y-2.5">
            @foreach ([
                '溝通風格類型',
                '類型簡介',
                '各維度得分',
                '深度特質分析',
                '職場應用建議',
                '與各類型相處指南',
                '個人成長路線圖',
            ] as $item)
            <li class="flex items-center gap-2.5 text-sm text-slate-700">
                <svg class="w-4 h-4 text-brand-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $item }}
            </li>
            @endforeach
        </ul>
    </div>
</div>

{{-- Price card --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6 text-center">
    <p class="text-slate-400 text-sm mb-1">一次付清，永久保存</p>
    <div class="flex items-end justify-center gap-1 mb-1">
        <span class="text-slate-400 text-lg">NT$</span>
        <span class="text-5xl font-bold text-slate-900">{{ number_format($attempt->quiz->price) }}</span>
    </div>
    <p class="text-slate-400 text-xs">報告連結不會過期</p>
</div>

{{-- Order form --}}
<form action="{{ route('quiz.attempt.order', ['token' => $attempt->session_token]) }}" method="POST">
    @csrf

    <div class="space-y-3 mb-5">
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">
                Email <span class="text-red-400">*</span>
                <span class="text-slate-400 font-normal ml-1">（報告將寄至此信箱）</span>
            </label>
            <input type="email" id="email" name="email"
                   value="{{ old('email', $attempt->email) }}"
                   placeholder="your@email.com"
                   class="w-full px-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-slate-200' }} text-sm focus:outline-none focus:ring-2 focus:ring-brand-400 transition">
            @error('email')
            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">姓名（選填）</label>
            <input type="text" id="name" name="name"
                   value="{{ old('name', $attempt->name) }}"
                   placeholder="你的名字"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-400 transition">
        </div>
    </div>

    <button type="submit"
        class="w-full bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white font-semibold py-4 rounded-2xl text-base transition-colors shadow-lg shadow-brand-500/20">
        前往付款 →
    </button>
</form>

<div class="flex justify-center mt-4">
    <a href="{{ route('quiz.attempt.result', ['token' => $attempt->session_token]) }}"
       class="text-sm text-slate-400 hover:text-slate-600 transition-colors">
        ← 回到免費結果頁
    </a>
</div>

@endsection
