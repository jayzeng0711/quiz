<?php

namespace App\Services;

use App\Models\QuizAttempt;
use App\Models\ResultType;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class AiAnalysisService
{
    /**
     * Generate a personalised analysis paragraph.
     * Throws exceptions so callers (e.g. GenerateAiAnalysisJob) can retry.
     * Returns '' only when API key is not configured.
     */
    public function generatePersonalizedInsight(QuizAttempt $attempt): string
    {
        if (blank(config('openai.api_key'))) {
            return '';
        }

        // Let exceptions propagate — the Job handles retry logic
        $prompt = $this->buildPrompt($attempt);

        $response = OpenAI::chat()->create([
            'model'    => config('openai.model', 'gpt-4o-mini'),
            'messages' => [
                [
                    'role'    => 'system',
                    'content' => <<<PROMPT
你是一位深度心靈與人際關係分析師，擅長以溫暖、洞察深刻的方式，根據個人的實際答題行為進行個人化深度分析。

請嚴格使用繁體中文，語氣溫暖但有深度，像一位真正了解你的老朋友在說話——不是在描述一個類型，而是在說這個人。

【格式要求】
- 總長度：至少 1000 字
- 分成 5 段，每段有一個主題，但不要加標題，直接連貫書寫
- 不要使用條列式、不要用「•」或「-」
- 每段約 200 字，段落之間有自然的過渡
- 要有深度、要打動人心，要說到別人沒說過的那一層

【五段結構】
第一段：根據具體答題行為，描述這個人在日常生活中的真實樣子，要具體到某些行為細節，讓他讀了說「就是我」。
第二段：說到他的核心優勢，以及這個優勢在什麼具體情境下最閃耀——用他答題的具體行為佐證。
第三段：說到他的次要風格如何補足或影響他，描述那個內在的張力或互補，讓他感受到被真正理解了。
第四段：說到他可能還沒完全意識到的一個盲點或成長方向，溫和但誠實，帶著愛意說出來。
第五段：給他一個具體的、他今天就可以做的行動建議，帶著你對他的了解說出來，讓他感到「這個建議是真的為我而寫的」。
PROMPT,
                ],
                [
                    'role'    => 'user',
                    'content' => $prompt,
                ],
            ],
            'max_tokens'  => 1600,
            'temperature' => 0.8,
        ]);

        $text = trim($response->choices[0]->message->content ?? '');

        Log::info('AiAnalysisService success', [
            'attempt_id' => $attempt->id,
            'chars'      => strlen($text),
        ]);

        return $text;

    }

    /**
     * Build the prompt that describes this specific user's answer pattern.
     */
    private function buildPrompt(QuizAttempt $attempt): string
    {
        $attempt->loadMissing(['resultType', 'answers.question', 'quiz']);

        $resultType = $attempt->resultType;
        $scores     = collect($attempt->score_breakdown ?? [])->sortDesc();

        $secondaryCode  = $scores->keys()->get(1, '');
        $secondaryScore = $scores->get($secondaryCode, 0);
        $primaryScore   = $scores->first(default: 0);

        // Build answer narrative
        $answerLines = $attempt->answers
            ->sortBy(fn ($a) => $a->question->sort_order)
            ->map(function ($answer) {
                $question = $answer->question;
                $options  = collect($question->options)->keyBy('key');
                $labels   = collect($answer->selected_options)
                    ->map(fn ($key) => $options->get($key)['label'] ?? $key)
                    ->implode('、');

                return "Q{$question->sort_order}（{$question->body}）→ 選了「{$labels}」";
            })
            ->implode("\n");

        $dominanceGap = $primaryScore - $secondaryScore;

        return <<<PROMPT
以下是這位使用者在「{$attempt->quiz->title}」的完整作答記錄：

{$answerLines}

主要風格：{$resultType->title}（{$resultType->code}，得分 {$primaryScore}）
次要風格：{$secondaryCode}（得分 {$secondaryScore}）
主次差距：{$dominanceGap} 分（差距越小代表越兩面兼具）

請根據以上具體答題行為和測驗主題，寫出一篇至少 1000 字的深度個人化分析，遵照 system prompt 的五段結構。
分析要具體、要有溫度、要讓他讀了感到「這是真的在說我，不是在說一個類型」。
PROMPT;
    }
}
