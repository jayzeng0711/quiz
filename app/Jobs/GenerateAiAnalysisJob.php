<?php

namespace App\Jobs;

use App\Models\Report;
use App\Services\AiAnalysisService;
use App\Services\ReportGenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateAiAnalysisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * When using a real queue driver (database/redis), the framework retries
     * automatically with exponential backoff.
     * In sync mode, the job runs once inside the web request — no sleep.
     */
    public int $tries   = 5;
    public int $backoff = 30;

    public function __construct(public readonly int $reportId) {}

    public function handle(AiAnalysisService $ai, ReportGenerationService $reports): void
    {
        $report = Report::with([
            'attempt.quiz',
            'attempt.answers.question',
            'attempt.resultType',
            'resultType',
            'order',
        ])->findOrFail($this->reportId);

        if (! blank($report->rendered_content['ai_analysis'] ?? '')) {
            return; // already done
        }

        // Single attempt — no sleep.
        // In sync mode: one try; failure is caught by OrderService and
        //   the result page auto-refreshes every 5s until the schedule fills it.
        // In async mode: the queue framework retries via $tries/$backoff above.
        $text = $ai->generatePersonalizedInsight($report->attempt);

        if (blank($text)) {
            throw new \RuntimeException(
                "AI returned empty for report #{$this->reportId}"
            );
        }

        $content                = $report->rendered_content ?? [];
        $content['ai_analysis'] = $text;
        $report->update(['rendered_content' => $content]);

        // Regenerate PDF with AI content
        if ($report->pdf_path) {
            Storage::disk('local')->delete($report->pdf_path);
            $report->update(['pdf_path' => null]);
        }

        $path = $reports->generatePdf($report);
        $reports->markGenerated($report, $path);

        Log::info("AI analysis generated for report #{$this->reportId}", [
            'result_type' => $report->resultType->title,
            'chars'       => strlen($text),
        ]);
    }

    public function failed(\Throwable $e): void
    {
        Log::error("GenerateAiAnalysisJob exhausted all retries for report #{$this->reportId}", [
            'error' => $e->getMessage(),
            'hint'  => 'Run: php artisan quiz:fill-ai',
        ]);
    }
}
