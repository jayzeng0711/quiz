<?php

namespace App\Services;

use App\Models\Order;
use App\Models\QuizAttempt;
use App\Models\Report;
use Illuminate\Support\Str;

class ReportGenerationService
{
    public function __construct(private readonly AiAnalysisService $ai) {}

    /**
     * Create (or retrieve) a Report record for a completed attempt.
     */
    public function generate(QuizAttempt $attempt, ?Order $order = null): Report
    {
        $existingReport = $attempt->report;
        if ($existingReport) {
            // 如果已有報告但 order_id 尚未設定，補上
            if ($order && ! $existingReport->order_id) {
                $existingReport->update(['order_id' => $order->id]);
            }
            return $existingReport;
        }

        $attempt->loadMissing('resultType');

        $report = Report::create([
            'quiz_attempt_id'  => $attempt->id,
            'order_id'         => $order?->id,
            'result_type_id'   => $attempt->result_type_id,
            'access_token'     => Str::uuid()->toString(),
            'status'           => 'draft',
            'rendered_content' => $this->buildRenderedContent($attempt, ''),
        ]);

        return $report;
    }

    public function buildRenderedContent(QuizAttempt $attempt, string $aiAnalysis = ''): array
    {
        $attempt->loadMissing(['resultType', 'quiz', 'answers.question']);

        return [
            'quiz_title'  => $attempt->quiz->title,
            'result_type' => [
                'code'           => $attempt->resultType->code,
                'title'          => $attempt->resultType->title,
                'description'    => $attempt->resultType->description,
                'report_content' => $attempt->resultType->report_content,
            ],
            'score_breakdown' => $attempt->score_breakdown,
            'answer_summary'  => $attempt->answers->map(fn ($answer) => [
                'question' => $answer->question->body,
                'selected' => $answer->selected_options,
            ])->toArray(),
            'ai_analysis'  => $aiAnalysis,
            'generated_at' => now()->toIso8601String(),
        ];
    }

    public function markGenerated(Report $report, ?string $pdfPath = null): Report
    {
        $report->update([
            'status'       => 'generated',
            'pdf_path'     => $pdfPath,
            'generated_at' => now(),
        ]);
        return $report;
    }

    public function markDelivered(Report $report): Report
    {
        $report->update(['status' => 'delivered', 'delivered_at' => now()]);
        return $report;
    }

}
