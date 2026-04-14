<?php

namespace App\Services\Payment;

use App\Models\Order;

/**
 * Unified payment gateway abstraction.
 *
 * Switch provider via PAYMENT_PROVIDER in .env:
 *   mock   — in-app confirm page (no external service, works offline)
 *   stripe — Stripe Checkout Session (test cards work locally)
 *   ecpay  — ECPay 綠界 (requires public webhook URL)
 */
class PaymentGatewayService
{
    public function __construct(
        private readonly MockGateway   $mock,
        private readonly StripeGateway $stripe,
        private readonly EcpayGateway  $ecpay,
    ) {}

    private function driver(): GatewayContract
    {
        return match (config('payment.provider')) {
            'stripe' => $this->stripe,
            'ecpay'  => $this->ecpay,
            default  => $this->mock,
        };
    }

    public function buildCheckoutForm(Order $order): string
    {
        return $this->driver()->buildCheckoutForm($order);
    }

    /**
     * @param  array<string, string>  $payload
     * @return array{order_number: string, status: string, reference: string, raw: array}
     */
    public function parseCallback(array $payload): array
    {
        return $this->driver()->parseCallback($payload);
    }
}
