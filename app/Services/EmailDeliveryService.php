<?php

namespace App\Services;

use App\Jobs\SendOrderConfirmationJob;
use App\Jobs\SendReportEmailJob;
use App\Models\EmailLog;
use App\Models\Order;
use App\Models\Report;

class EmailDeliveryService
{
    /**
     * Dispatch the report delivery email as a queued job.
     * Returns the EmailLog record immediately; actual sending is async.
     */
    public function sendReport(Report $report): EmailLog
    {
        $report->loadMissing(['attempt', 'order', 'resultType']);

        $email = $report->order?->email ?? $report->attempt->email;
        $name  = $report->order?->name  ?? $report->attempt->name;

        if (blank($email)) {
            throw new \RuntimeException("No recipient email for report [{$report->id}].");
        }

        $log = $this->initLog(
            orderId:        $report->order_id,
            reportId:       $report->id,
            recipientEmail: $email,
            recipientName:  $name,
            subject:        '【職場溝通風格測驗】你的完整分析報告已送達',
            template:       \App\Mail\ReportDeliveryMail::class,
        );

        SendReportEmailJob::dispatch($report, $log);

        return $log;
    }

    /**
     * Dispatch the order confirmation email as a queued job.
     */
    public function sendOrderConfirmation(Order $order): EmailLog
    {
        $log = $this->initLog(
            orderId:        $order->id,
            reportId:       null,
            recipientEmail: $order->email,
            recipientName:  $order->name,
            subject:        '【職場溝通風格測驗】訂單付款確認',
            template:       \App\Mail\OrderConfirmationMail::class,
        );

        SendOrderConfirmationJob::dispatch($order, $log);

        return $log;
    }

    /**
     * Retry a previously failed email.
     */
    public function retry(EmailLog $log): EmailLog
    {
        if ($log->isSent()) {
            throw new \RuntimeException("EmailLog [{$log->id}] already sent.");
        }

        $log->update(['status' => 'pending', 'error_message' => null]);

        if ($log->report_id) {
            return $this->sendReport($log->report);
        }

        if ($log->order_id) {
            return $this->sendOrderConfirmation($log->order);
        }

        throw new \RuntimeException("Cannot determine email type for EmailLog [{$log->id}].");
    }

    private function initLog(
        ?int $orderId,
        ?int $reportId,
        string $recipientEmail,
        ?string $recipientName,
        string $subject,
        string $template,
    ): EmailLog {
        return EmailLog::create([
            'order_id'        => $orderId,
            'report_id'       => $reportId,
            'recipient_email' => $recipientEmail,
            'recipient_name'  => $recipientName,
            'subject'         => $subject,
            'template'        => $template,
            'status'          => 'pending',
        ]);
    }
}
