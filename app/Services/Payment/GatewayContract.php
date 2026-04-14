<?php

namespace App\Services\Payment;

use App\Models\Order;

interface GatewayContract
{
    /**
     * Return an HTML string (self-submitting form or redirect JS) that sends
     * the user to the payment provider's checkout page.
     */
    public function buildCheckoutForm(Order $order): string;

    /**
     * Verify the incoming webhook/callback payload signature and normalise it.
     *
     * @param  array<string, string>  $payload
     * @return array{order_number: string, status: string, reference: string, raw: array}
     *
     * @throws \RuntimeException  on signature mismatch
     */
    public function parseCallback(array $payload): array;
}
