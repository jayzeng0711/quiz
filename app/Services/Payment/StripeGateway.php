<?php

namespace App\Services\Payment;

use App\Models\Order;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;

/**
 * Stripe Checkout Session gateway.
 *
 * Required .env keys:
 *   STRIPE_KEY=pk_test_...       (publishable key — not used server-side)
 *   STRIPE_SECRET=sk_test_...    (secret key)
 *   STRIPE_WEBHOOK_SECRET=whsec_...  (from `stripe listen` or dashboard)
 *
 * Local testing:
 *   - Use test card: 4242 4242 4242 4242, any future date, any CVC
 *   - Run `stripe listen --forward-to localhost:8000/webhook/payment`
 *     to forward webhooks locally (requires Stripe CLI)
 *
 * Docs: https://stripe.com/docs/checkout/quickstart
 */
class StripeGateway implements GatewayContract
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient((string) config('payment.stripe.secret'));
    }

    /**
     * Create a Stripe Checkout Session and redirect the user to it.
     */
    public function buildCheckoutForm(Order $order): string
    {
        $order->loadMissing(['attempt.quiz']);

        $session = $this->stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'mode'                 => 'payment',
            'line_items'           => [[
                'price_data' => [
                    'currency'     => strtolower($order->currency),
                    'unit_amount'  => $order->amount * 100,
                    'product_data' => [
                        'name'        => $order->attempt->quiz->title . ' — 完整版報告',
                        'description' => '個人化職場溝通風格完整分析報告',
                    ],
                ],
                'quantity' => 1,
            ]],
            'customer_email'   => $order->email,
            'client_reference_id' => $order->order_number,
            'success_url'      => route('payment.stripe.success', [
                'token'       => $order->attempt->session_token,
                'order'       => $order->order_number,
                'session_id'  => '{CHECKOUT_SESSION_ID}',
            ]),
            'cancel_url'       => route('quiz.attempt.unlock', [
                'token' => $order->attempt->session_token,
            ]),
            'metadata' => [
                'order_number'  => $order->order_number,
                'quiz_attempt'  => $order->quiz_attempt_id,
            ],
        ]);

        // Redirect immediately to Stripe hosted checkout page
        return <<<HTML
        <!DOCTYPE html>
        <html><body>
        <script>window.location.href = '{$session->url}';</script>
        <noscript><meta http-equiv="refresh" content="0;url={$session->url}"></noscript>
        </body></html>
        HTML;
    }

    /**
     * Verify and parse a Stripe webhook event.
     * Stripe sends `checkout.session.completed` on successful payment.
     *
     * @param  array<string, mixed>  $payload
     */
    public function parseCallback(array $payload): array
    {
        $sigHeader = request()->header('Stripe-Signature', '');
        $secret    = (string) config('payment.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                request()->getContent(),
                $sigHeader,
                $secret
            );
        } catch (SignatureVerificationException $e) {
            throw new \RuntimeException('Stripe webhook signature mismatch: ' . $e->getMessage());
        }

        if ($event->type !== 'checkout.session.completed') {
            return [
                'order_number' => '',
                'status'       => 'ignored',
                'reference'    => $event->id,
                'raw'          => $payload,
            ];
        }

        /** @var Session $session */
        $session = $event->data->object;

        return [
            'order_number' => $session->client_reference_id ?? $session->metadata->order_number ?? '',
            'status'       => $session->payment_status === 'paid' ? 'paid' : 'pending',
            'reference'    => $session->payment_intent ?? $session->id,
            'raw'          => $payload,
        ];
    }

    /**
     * Verify a Checkout Session directly (used in success URL handler).
     * This allows local testing without webhooks.
     */
    public function verifyCheckoutSession(string $sessionId): array
    {
        $session = $this->stripe->checkout->sessions->retrieve($sessionId);

        return [
            'order_number' => $session->client_reference_id ?? $session->metadata->order_number ?? '',
            'status'       => $session->payment_status === 'paid' ? 'paid' : 'pending',
            'reference'    => $session->payment_intent ?? $session->id,
            'raw'          => $session->toArray(),
        ];
    }
}
