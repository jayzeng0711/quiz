<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\QuizAttemptController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Quiz Flow
|--------------------------------------------------------------------------
| GET  /                              quiz landing page
| POST /quiz/start                    create attempt → redirect to Q1
|
| GET  /quiz/{token}/q/{n}            show question n
| POST /quiz/{token}/q/{n}            save answer, navigate
| GET  /quiz/{token}/result           free result page
|
| GET  /quiz/{token}/unlock           upsell / unlock page
| POST /quiz/{token}/order            create pending order
| GET  /quiz/{token}/pay/{order}      mock payment confirmation
| POST /quiz/{token}/pay/{order}      simulate payment success
| GET  /quiz/{token}/report           full paid report
|
| GET  /share/{shareToken}            public share page (free content only)
*/

Route::get('/', [QuizController::class, 'show'])->name('quiz.show');

Route::post('/quiz/start', [QuizAttemptController::class, 'start'])->name('quiz.start');

Route::prefix('/quiz/{token}')->name('quiz.attempt.')->group(function () {
    // Quiz flow
    Route::get('/q/{question}',  [QuizAttemptController::class, 'showQuestion'])->name('question');
    Route::post('/q/{question}', [QuizAttemptController::class, 'saveAnswer'])->name('answer');
    Route::get('/result',        [QuizAttemptController::class, 'result'])->name('result');

    // Payment flow
    Route::get('/unlock',           [OrderController::class, 'unlock'])->name('unlock');
    Route::post('/order',           [OrderController::class, 'store'])->name('order');
    Route::get('/pay/{order}',      [OrderController::class, 'showMockPay'])->name('mock-pay');
    Route::post('/pay/{order}',     [OrderController::class, 'mockPay'])->name('mock-pay.confirm');
    Route::get('/report',           [OrderController::class, 'fullReport'])->name('report');
    Route::post('/generate-ai',     [OrderController::class, 'generateAi'])->name('generate-ai');
});

Route::get('/share/{shareToken}', [OrderController::class, 'share'])->name('share');

/*
|--------------------------------------------------------------------------
| Payment Webhooks (exclude CSRF)
|--------------------------------------------------------------------------
*/
Route::post('/webhook/payment', [PaymentWebhookController::class, 'handle'])
    ->name('payment.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::match(['GET', 'POST'], '/quiz/{token}/payment/return', [PaymentWebhookController::class, 'returnRedirect'])
    ->name('payment.return')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Stripe success redirect — verifies session directly, no webhook needed locally
Route::get('/quiz/{token}/payment/stripe/success', [PaymentWebhookController::class, 'stripeSuccess'])
    ->name('payment.stripe.success');
