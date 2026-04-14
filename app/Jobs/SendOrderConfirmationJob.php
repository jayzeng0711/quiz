<?php

namespace App\Jobs;

use App\Mail\OrderConfirmationMail;
use App\Models\EmailLog;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendOrderConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public readonly Order $order,
        public readonly EmailLog $log,
    ) {}

    public function handle(): void
    {
        Mail::to($this->order->email)->send(new OrderConfirmationMail($this->order));

        $this->log->update([
            'status'  => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function failed(Throwable $e): void
    {
        $this->log->update([
            'status'        => 'failed',
            'error_message' => $e->getMessage(),
        ]);
    }
}
