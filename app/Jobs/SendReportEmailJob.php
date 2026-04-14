<?php

namespace App\Jobs;

use App\Mail\ReportDeliveryMail;
use App\Models\EmailLog;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendReportEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60; // seconds between retries

    public function __construct(
        public readonly Report $report,
        public readonly EmailLog $log,
    ) {}

    public function handle(): void
    {
        $this->report->loadMissing(['attempt', 'order', 'resultType']);

        $email = $this->report->order?->email ?? $this->report->attempt->email;

        Mail::to($email)->send(new ReportDeliveryMail($this->report));

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
