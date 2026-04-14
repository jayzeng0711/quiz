<?php

use App\Jobs\GenerateAiAnalysisJob;
use App\Models\Report;
use Illuminate\Support\Facades\Schedule;

/*
 * 每 5 分鐘自動找出缺少 AI 分析的付費報告並補上 Job
 * 即使 OpenAI 在付款當下失敗，5 分鐘後會自動重試
 */
Schedule::call(function () {
    Report::whereNotNull('result_type_id')
        ->where(function ($q) {
            $q->whereNull('rendered_content')
              ->orWhereRaw("JSON_EXTRACT(rendered_content, '$.ai_analysis') IS NULL")
              ->orWhereRaw("JSON_EXTRACT(rendered_content, '$.ai_analysis') = ''")
              ->orWhereRaw("JSON_EXTRACT(rendered_content, '$.ai_analysis') = 'null'");
        })
        ->each(fn ($report) => GenerateAiAnalysisJob::dispatch($report->id));
})->everyFiveMinutes()->name('fill-missing-ai')->withoutOverlapping();
