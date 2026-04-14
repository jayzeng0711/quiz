<?php

namespace App\Console\Commands;

use App\Models\Report;
use App\Services\AiAnalysisService;
use App\Services\ReportGenerationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FillMissingAiAnalysis extends Command
{
    protected $signature   = 'quiz:fill-ai {--force : Re-generate even if AI already exists}';
    protected $description = '補齊所有缺少 AI 個人化分析的付費報告';

    public function __construct(
        private readonly AiAnalysisService $ai,
        private readonly ReportGenerationService $reports,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $force = $this->option('force');

        $query = Report::with([
            'attempt.quiz',
            'attempt.answers.question',
            'attempt.resultType',
            'resultType',
            'order',
        ])->whereNotNull('result_type_id');

        if (! $force) {
            $query->where(function ($q) {
                $q->whereNull('rendered_content')
                  ->orWhereRaw("JSON_EXTRACT(rendered_content, '$.ai_analysis') IS NULL")
                  ->orWhereRaw("JSON_EXTRACT(rendered_content, '$.ai_analysis') = ''")
                  ->orWhereRaw("JSON_EXTRACT(rendered_content, '$.ai_analysis') = 'null'");
            });
        }

        $reports = $query->get();

        if ($reports->isEmpty()) {
            $this->info('✅ 所有報告都已有 AI 分析，無需補齊。');
            return 0;
        }

        $this->info("找到 {$reports->count()} 份報告需要補齊 AI 分析...");
        $bar = $this->output->createProgressBar($reports->count());
        $bar->start();

        $success = 0;
        $failed  = 0;

        foreach ($reports as $report) {
            $text = $this->ai->generatePersonalizedInsight($report->attempt);

            if (blank($text)) {
                $this->newLine();
                $this->warn("Report #{$report->id} ({$report->resultType->title}) AI 生成失敗，跳過。");
                $failed++;
                $bar->advance();
                continue;
            }

            $content                = $report->rendered_content ?? [];
            $content['ai_analysis'] = $text;
            $report->update(['rendered_content' => $content]);

            // Regenerate PDF with AI content
            if ($report->pdf_path) {
                Storage::disk('local')->delete($report->pdf_path);
                $report->update(['pdf_path' => null]);
            }

            try {
                $path = $this->reports->generatePdf($report);
                $this->reports->markGenerated($report, $path);
            } catch (\Throwable $e) {
                $this->newLine();
                $this->warn("Report #{$report->id} PDF 重生成失敗：{$e->getMessage()}");
            }

            $success++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("完成！成功：{$success} 份，失敗：{$failed} 份。");

        return $failed > 0 ? 1 : 0;
    }
}
