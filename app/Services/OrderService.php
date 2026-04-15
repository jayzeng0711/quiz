<?php

namespace App\Services;

use App\Jobs\GenerateAiAnalysisJob;
use App\Models\Order;
use App\Models\QuizAttempt;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        private readonly ReportGenerationService $reportGenerator,
        private readonly EmailDeliveryService $emailDelivery,
    ) {}

    /**
     * Create a pending order for a completed attempt.
     * Reuses an existing pending order if the user revisits before paying.
     */
    public function createOrder(QuizAttempt $attempt): Order
    {
        if ($attempt->hasPaidOrder()) {
            throw new \RuntimeException('此測驗已有付款完成的訂單。');
        }

        $existing = $attempt->order()->where('status', 'pending')->first();
        if ($existing) {
            return $existing;
        }

        $attempt->loadMissing('quiz');

        return Order::create([
            'order_number'    => $this->generateOrderNumber(),
            'quiz_attempt_id' => $attempt->id,
            'email'           => $attempt->email ?? '',
            'name'            => $attempt->name,
            'amount'          => $attempt->quiz->price,
            'currency'        => 'TWD',
            'status'          => 'pending',
        ]);
    }

    /**
     * Mark an order as paid, generate report, dispatch AI job and emails.
     * AI generation is now a queued job (5 retries, 30s backoff) — never blocks the flow.
     *
     * @param  array<string, mixed>  $meta
     */
    public function processPayment(
        Order $order,
        string $reference = '',
        array $meta = [],
        string $provider = 'mock',
    ): void {
        if ($order->isPaid()) {
            return;
        }

        // 1. Mark order paid
        $order->update([
            'status'            => 'paid',
            'payment_provider'  => $provider,
            'payment_reference' => $reference ?: ('MOCK-' . strtoupper(Str::random(10))),
            'payment_meta'      => $meta,
            'paid_at'           => now(),
        ]);

        // 2. Update attempt status
        $attempt = $order->attempt;
        $attempt->update(['status' => 'paid', 'paid_at' => now()]);
        $attempt->refresh()->loadMissing(['resultType', 'quiz', 'answers.question']);

        // 3. Create report (without AI for now — AI arrives via job below)
        $report = $this->reportGenerator->generate($attempt, $order);
        $this->reportGenerator->markGenerated($report);

        // 4. Dispatch AI generation — must never cause payment to fail
        //    With sync queue: runs immediately, retries up to 4x with sleep
        //    With database/redis queue: runs in background worker
        //    Either way: failure is logged and the schedule auto-fills within 5 min
        try {
            GenerateAiAnalysisJob::dispatch($report->id);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('AI job failed during payment — schedule will retry', [
                'report_id' => $report->id,
                'error'     => $e->getMessage(),
            ]);
        }

        // 5. Send single combined email (report + order confirmation)
        $this->emailDelivery->sendReport($report);
    }

    public function markFailed(Order $order, ?string $reason = null): Order
    {
        $order->update([
            'status'       => 'failed',
            'payment_meta' => array_merge($order->payment_meta ?? [], [
                'failure_reason' => $reason,
                'failed_at'      => now()->toIso8601String(),
            ]),
        ]);

        return $order;
    }

    public function refund(Order $order, ?string $reason = null): Order
    {
        if (! $order->isPaid()) {
            throw new \RuntimeException("Order [{$order->order_number}] is not paid.");
        }

        $order->update([
            'status'       => 'refunded',
            'payment_meta' => array_merge($order->payment_meta ?? [], [
                'refund_reason' => $reason,
                'refunded_at'   => now()->toIso8601String(),
            ]),
        ]);

        return $order;
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-' . strtoupper(Str::random(8));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
