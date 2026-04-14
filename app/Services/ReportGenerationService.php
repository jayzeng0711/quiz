<?php

namespace App\Services;

use App\Models\Order;
use App\Models\QuizAttempt;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use HeadlessChromium\BrowserFactory;

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

    /**
     * Render the result page as a PDF using headless Chrome (chrome-php/chrome).
     * Chrome renders with system fonts — Traditional Chinese is perfect.
     * No Node.js required.
     */
    public function generatePdf(Report $report): string
    {
        $report->loadMissing(['attempt', 'resultType', 'order']);

        $token   = $report->attempt->session_token;
        $pageUrl = url("/quiz/{$token}/result");

        $path     = 'reports/' . $report->access_token . '.pdf';
        $fullPath = storage_path('app/private/' . $path);

        if (! is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        $factory = new BrowserFactory(
            '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome'
        );

        $browser = $factory->createBrowser([
            'noSandbox'        => true,
            'ignoreCertErrors' => true,
        ]);

        try {
            $page = $browser->createPage();
            $page->navigate($pageUrl)->waitForNavigation('networkIdle');

            // Wait for fonts and charts to render
            sleep(2);

            // A4: 210mm × 297mm in inches = 8.27 × 11.69
            $pdf = $page->pdf([
                'printBackground' => true,
                'paperWidth'      => 8.27,
                'paperHeight'     => 11.69,
                'marginTop'       => 0.4,
                'marginBottom'    => 0.4,
                'marginLeft'      => 0.4,
                'marginRight'     => 0.4,
            ]);

            $pdf->saveToFile($fullPath);
        } finally {
            $browser->close();
        }

        return $path;
    }
}
