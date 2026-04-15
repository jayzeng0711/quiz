<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportDeliveryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Report $report) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【心靈測驗】你的完整分析報告已送達',
        );
    }

    public function content(): Content
    {
        $this->report->loadMissing(['order', 'attempt.quiz', 'resultType']);

        return new Content(
            view: 'mail.report-delivery',
        );
    }
}
