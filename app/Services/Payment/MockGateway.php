<?php

namespace App\Services\Payment;

use App\Models\Order;

/**
 * Mock gateway for local development.
 * Shows a simple confirm page instead of redirecting to a real provider.
 */
class MockGateway implements GatewayContract
{
    public function buildCheckoutForm(Order $order): string
    {
        $order->loadMissing('attempt');

        $confirmUrl = route('quiz.attempt.mock-pay', [
            'token' => $order->attempt->session_token,
            'order' => $order->order_number,
        ]);

        // Redirect immediately to the in-app mock pay page
        return <<<HTML
        <!DOCTYPE html>
        <html><body>
        <script>window.location.href = '{$confirmUrl}';</script>
        <noscript><meta http-equiv="refresh" content="0;url={$confirmUrl}"></noscript>
        </body></html>
        HTML;
    }

    public function parseCallback(array $payload): array
    {
        return [
            'order_number' => $payload['order_number'] ?? '',
            'status'       => $payload['status'] ?? 'paid',
            'reference'    => 'MOCK-' . strtoupper(substr(md5(uniqid()), 0, 10)),
            'raw'          => $payload,
        ];
    }
}
