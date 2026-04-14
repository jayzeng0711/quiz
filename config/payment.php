<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Payment Provider
    |--------------------------------------------------------------------------
    |
    | mock   → 本地開發用，內建確認頁，完全不需外部服務
    | stripe → Stripe Checkout，本地可用測試卡，不需公開 URL
    | ecpay  → 綠界金流，正式環境用，需要公開 Webhook URL
    */
    'provider' => env('PAYMENT_PROVIDER', 'mock'),

    /*
    |--------------------------------------------------------------------------
    | Stripe
    |--------------------------------------------------------------------------
    |
    | 1. 前往 https://dashboard.stripe.com/test/apikeys 取得測試金鑰
    | 2. 設定 STRIPE_SECRET=sk_test_...
    | 3. 本地測試卡：4242 4242 4242 4242，任何未來日期，任何 CVC
    |
    | Webhook（正式環境）：
    |   - Dashboard 設定 endpoint: https://你的網域/webhook/payment
    |   - 事件選擇：checkout.session.completed
    |   - 把 Signing Secret 填入 STRIPE_WEBHOOK_SECRET
    |
    | Webhook（本地測試）：
    |   - 安裝 Stripe CLI: https://stripe.com/docs/stripe-cli
    |   - 執行：stripe listen --forward-to localhost:8000/webhook/payment
    |   - 會自動列印 webhook secret，填入 STRIPE_WEBHOOK_SECRET
    */
    'stripe' => [
        'key'            => env('STRIPE_KEY', ''),
        'secret'         => env('STRIPE_SECRET', ''),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | ECPay 綠界（台灣正式環境）
    |--------------------------------------------------------------------------
    | 申請：https://www.ecpay.com.tw/
    | Sandbox 測試帳號（直接可用）：
    |   ECPAY_MERCHANT_ID=2000132
    |   ECPAY_HASH_KEY=5294y06JbISpM5x9
    |   ECPAY_HASH_IV=v77hoKGq4kWxNNIS
    */
    'ecpay' => [
        'merchant_id' => env('ECPAY_MERCHANT_ID', '2000132'),
        'hash_key'    => env('ECPAY_HASH_KEY', '5294y06JbISpM5x9'),
        'hash_iv'     => env('ECPAY_HASH_IV', 'v77hoKGq4kWxNNIS'),
        'env'         => env('ECPAY_ENV', 'sandbox'),
    ],

];
