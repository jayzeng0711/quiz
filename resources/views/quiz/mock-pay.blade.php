@extends('layouts.app')

@section('title', '確認付款')

@section('content')

{{-- Environment warning --}}
<div class="flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-6 text-sm text-amber-700">
    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
    </svg>
    <span>這是測試環境的模擬付款頁，實際上不會扣款。</span>
</div>

<div class="text-center mb-8">
    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-brand-100 mb-4">
        <svg class="w-7 h-7 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
        </svg>
    </div>
    <h1 class="text-2xl font-bold text-slate-900 mb-1">確認付款</h1>
    <p class="text-slate-400 text-sm">請確認訂單資訊無誤後點擊「確認付款」</p>
</div>

{{-- Order summary --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm divide-y divide-slate-100 mb-6">
    <div class="px-6 py-4 flex justify-between items-center text-sm">
        <span class="text-slate-500">訂單編號</span>
        <span class="font-mono font-semibold text-slate-800">{{ $order->order_number }}</span>
    </div>
    <div class="px-6 py-4 flex justify-between items-center text-sm">
        <span class="text-slate-500">商品</span>
        <span class="text-slate-800">{{ $attempt->quiz->title }} — 完整版報告</span>
    </div>
    <div class="px-6 py-4 flex justify-between items-center text-sm">
        <span class="text-slate-500">收件 Email</span>
        <span class="text-slate-800">{{ $order->email }}</span>
    </div>
    <div class="px-6 py-5 flex justify-between items-center">
        <span class="font-semibold text-slate-700">付款金額</span>
        <span class="text-2xl font-bold text-slate-900">NT$ {{ number_format($order->amount) }}</span>
    </div>
</div>

{{-- Confirm pay form --}}
<form action="{{ route('quiz.attempt.mock-pay', ['token' => $attempt->session_token, 'order' => $order->order_number]) }}" method="POST">
    @csrf
    <button type="submit"
        class="w-full bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white font-semibold py-4 rounded-2xl text-base transition-colors shadow-lg shadow-brand-500/20 mb-3">
        確認付款 NT$ {{ number_format($order->amount) }}
    </button>
</form>

<a href="{{ route('quiz.attempt.unlock', ['token' => $attempt->session_token]) }}"
   class="block text-center text-sm text-slate-400 hover:text-slate-600 transition-colors">
    ← 返回
</a>

@endsection
