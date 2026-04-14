<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\Services\Payment\PaymentGatewayService;
use App\Services\Payment\StripeGateway;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function __construct(
        private readonly PaymentGatewayService $gateway,
        private readonly OrderService $orderService,
    ) {}

    /**
     * POST /webhook/payment
     * Server-to-server callback from payment provider.
     * Must return "1|OK" for ECPay (or 200 for Stripe).
     */
    public function handle(Request $request): Response
    {
        $payload = $request->all();

        Log::info('Payment webhook received', ['payload' => $payload]);

        try {
            $parsed = $this->gateway->parseCallback($payload);

            $order = Order::where('order_number', $parsed['order_number'])->first();

            if (! $order) {
                Log::warning('Webhook: order not found', ['order_number' => $parsed['order_number']]);
                return response('Order not found', 400);
            }

            if ($order->isPaid()) {
                // Idempotent: already processed
                return response('1|OK', 200);
            }

            if ($parsed['status'] === 'paid') {
                $this->orderService->processPayment(
                    order:      $order,
                    reference:  $parsed['reference'],
                    meta:       $parsed['raw'],
                    provider:   config('payment.provider'),
                );
            } else {
                $this->orderService->markFailed($order, $parsed['raw']['RtnMsg'] ?? 'Payment failed');
            }

        } catch (\RuntimeException $e) {
            Log::error('Webhook processing failed', ['error' => $e->getMessage(), 'payload' => $payload]);
            return response($e->getMessage(), 400);
        }

        return response('1|OK', 200);
    }

    /**
     * GET /quiz/{token}/payment/stripe/success?session_id=cs_...&order=ORD-...
     *
     * Stripe redirects here after successful payment.
     * We verify the Checkout Session directly — no webhook needed for local dev.
     */
    public function stripeSuccess(Request $request, string $token): \Illuminate\Http\RedirectResponse
    {
        $sessionId   = $request->query('session_id', '');
        $orderNumber = $request->query('order', '');

        $order = Order::where('order_number', $orderNumber)->first();

        if (! $order || ! $sessionId) {
            return redirect()->route('quiz.show');
        }

        if (! $order->isPaid()) {
            try {
                $stripe = app(StripeGateway::class);
                $parsed = $stripe->verifyCheckoutSession($sessionId);

                if ($parsed['status'] === 'paid') {
                    $this->orderService->processPayment(
                        order:     $order,
                        reference: $parsed['reference'],
                        meta:      ['stripe_session_id' => $sessionId],
                        provider:  'stripe',
                    );
                }
            } catch (\Throwable $e) {
                Log::error('Stripe success verification failed', ['error' => $e->getMessage()]);
            }
        }

        return redirect()->route('quiz.attempt.result', ['token' => $token])
            ->with('payment_success', true);
    }

    /**
     * GET /quiz/{token}/payment/return
     * User-facing return page (ECPay ClientBackURL).
     */
    public function returnRedirect(Request $request, string $token): \Illuminate\Http\RedirectResponse
    {
        $attempt = \App\Models\QuizAttempt::where('session_token', $token)->firstOrFail();

        if ($attempt->hasPaidOrder()) {
            return redirect()->route('quiz.attempt.report', ['token' => $token])
                ->with('payment_success', true);
        }

        return redirect()->route('quiz.attempt.result', ['token' => $token]);
    }
}
