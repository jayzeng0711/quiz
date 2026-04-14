<?php

namespace App\Services\Payment;

use App\Models\Order;

/**
 * ECPay (綠界) payment gateway integration.
 *
 * Required .env keys:
 *   ECPAY_MERCHANT_ID=
 *   ECPAY_HASH_KEY=
 *   ECPAY_HASH_IV=
 *   ECPAY_ENV=sandbox        # or "production"
 *
 * Docs: https://developers.ecpay.com.tw/
 */
class EcpayGateway implements GatewayContract
{
    private const SANDBOX_URL    = 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckout/index';
    private const PRODUCTION_URL = 'https://payment.ecpay.com.tw/Cashier/AioCheckout/index';

    private string $merchantId;
    private string $hashKey;
    private string $hashIv;
    private string $actionUrl;

    public function __construct()
    {
        $this->merchantId = (string) config('payment.ecpay.merchant_id');
        $this->hashKey    = (string) config('payment.ecpay.hash_key');
        $this->hashIv     = (string) config('payment.ecpay.hash_iv');
        $this->actionUrl  = config('payment.ecpay.env') === 'production'
            ? self::PRODUCTION_URL
            : self::SANDBOX_URL;
    }

    public function buildCheckoutForm(Order $order): string
    {
        $order->loadMissing(['attempt.quiz']);

        $params = [
            'MerchantID'        => $this->merchantId,
            'MerchantTradeNo'   => $this->sanitizeOrderNumber($order->order_number),
            'MerchantTradeDate' => now()->format('Y/m/d H:i:s'),
            'PaymentType'       => 'aio',
            'TotalAmount'       => (int) round($order->amount / 100),
            'TradeDesc'         => urlencode($order->attempt->quiz->title . ' 完整版報告'),
            'ItemName'          => $order->attempt->quiz->title . ' 完整版報告',
            'ReturnURL'         => route('payment.webhook'),
            'ClientBackURL'     => route('quiz.attempt.result', ['token' => $order->attempt->session_token]),
            'OrderResultURL'    => route('payment.return', ['token' => $order->attempt->session_token]),
            'ChoosePayment'     => 'ALL',
            'EncryptType'       => 1,
            'NeedExtraPaidInfo'  => 'N',
            'CustomField1'      => $order->order_number,
        ];

        $params['CheckMacValue'] = $this->generateCheckMac($params);

        return $this->renderForm($this->actionUrl, $params);
    }

    public function parseCallback(array $payload): array
    {
        $received = $payload['CheckMacValue'] ?? '';
        $computed = $this->generateCheckMac(
            collect($payload)->except('CheckMacValue')->toArray()
        );

        if (! hash_equals(strtolower($received), strtolower($computed))) {
            throw new \RuntimeException('ECPay callback signature mismatch.');
        }

        $orderNumber = $payload['CustomField1'] ?? $payload['MerchantTradeNo'] ?? '';
        $isPaid      = ($payload['RtnCode'] ?? '') === '1';

        return [
            'order_number' => $orderNumber,
            'status'       => $isPaid ? 'paid' : 'failed',
            'reference'    => $payload['TradeNo'] ?? '',
            'raw'          => $payload,
        ];
    }

    // -------------------------------------------------------------------------

    /**
     * ECPay CheckMacValue — SHA256 with URL encoding rules per their spec.
     *
     * @param  array<string, mixed>  $params
     */
    private function generateCheckMac(array $params): string
    {
        ksort($params);

        $query = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        $raw   = "HashKey={$this->hashKey}&{$query}&HashIV={$this->hashIv}";

        // ECPay-specific URL encode: lowercase, replace special chars
        $encoded = strtolower(urlencode($raw));
        $encoded = str_replace(
            ['%2d', '%5f', '%2e', '%21', '%2a', '%28', '%29'],
            ['-',   '_',   '.',   '!',   '*',   '(',   ')'],
            $encoded
        );

        return strtoupper(hash('sha256', $encoded));
    }

    /** ECPay MerchantTradeNo must be <= 20 chars, alphanumeric only */
    private function sanitizeOrderNumber(string $number): string
    {
        return substr(str_replace('-', '', $number), 0, 20);
    }

    /**
     * Build a self-submitting HTML form that POSTs to ECPay.
     *
     * @param  array<string, mixed>  $params
     */
    private function renderForm(string $action, array $params): string
    {
        $fields = collect($params)
            ->map(fn ($v, $k) => '<input type="hidden" name="' . e($k) . '" value="' . e((string) $v) . '">')
            ->implode("\n");

        return <<<HTML
        <!DOCTYPE html>
        <html><body>
        <form id="ecpay-form" action="{$action}" method="POST">{$fields}</form>
        <script>document.getElementById('ecpay-form').submit();</script>
        </body></html>
        HTML;
    }
}
