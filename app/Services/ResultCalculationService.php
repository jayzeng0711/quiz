<?php

namespace App\Services;

use App\Models\QuizAttempt;
use App\Models\ResultType;
use Illuminate\Support\Collection;

class ResultCalculationService
{
    /**
     * Calculate the result type AND dimension scores for a completed attempt.
     */
    public function calculate(QuizAttempt $attempt): ResultType
    {
        $typeScores = $this->aggregateScores($attempt);
        $winningCode = $this->resolveWinner($typeScores);

        $resultType = ResultType::where('quiz_id', $attempt->quiz_id)
            ->where('code', $winningCode)
            ->firstOrFail();

        $dimensionScores = $this->aggregateDimensionScores($attempt);

        $attempt->update([
            'result_type_id'  => $resultType->id,
            'score_breakdown' => $typeScores,
            'dimension_scores'=> $dimensionScores,
            'status'          => 'completed',
            'completed_at'    => now(),
        ]);

        return $resultType;
    }

    /**
     * Build a map of result_type_code => total_score from all answers.
     *
     * @return array<string, int>
     */
    public function aggregateScores(QuizAttempt $attempt): array
    {
        $scores = [];

        $attempt->answers()->with('question')->each(function ($answer) use (&$scores) {
            $options = collect($answer->question->options)->keyBy('key');

            foreach ($answer->selected_options as $selectedKey) {
                $option = $options->get($selectedKey);
                if (! $option) {
                    continue;
                }

                foreach ($option['scores'] ?? [] as $code => $points) {
                    $scores[$code] = ($scores[$code] ?? 0) + $points;
                }
            }
        });

        return $scores;
    }

    /**
     * Calculate normalised 0-100 scores for each personality dimension.
     *
     * Options store dimension contributions under the `dim_scores` key:
     *   "dim_scores": { "ASSERTIVENESS": 20, "LOGIC": 10 }
     *
     * Normalisation:
     *   - Max theoretical score per dimension = questions_answered × max_dim_per_option
     *   - We use 20 as the default max contribution per option per dimension.
     *   - Final = round(raw / theoretical_max * 100), capped at 100.
     *
     * @return array<string, int>  e.g. ["ASSERTIVENESS" => 75, "LOGIC" => 40]
     */
    public function aggregateDimensionScores(QuizAttempt $attempt): array
    {
        $raw          = [];
        $maxPerOption = 20;   // matches the convention used in question seeders
        $answeredCount = 0;

        $attempt->answers()->with('question')->each(function ($answer) use (&$raw, &$answeredCount) {
            $options = collect($answer->question->options)->keyBy('key');
            $answeredCount++;

            foreach ($answer->selected_options as $selectedKey) {
                $option = $options->get($selectedKey);
                if (! $option) {
                    continue;
                }

                foreach ($option['dim_scores'] ?? [] as $dim => $points) {
                    $raw[$dim] = ($raw[$dim] ?? 0) + $points;
                }
            }
        });

        if (empty($raw) || $answeredCount === 0) {
            return [];
        }

        $theoreticalMax = $answeredCount * $maxPerOption;

        return collect($raw)
            ->map(fn ($v) => min(100, (int) round($v / $theoreticalMax * 100)))
            ->all();
    }

    /**
     * Return the code with the highest score, applying tie-breaking rules.
     *
     * @param  array<string, int>  $scores
     */
    public function resolveWinner(array $scores): string
    {
        if (empty($scores)) {
            throw new \RuntimeException('Score map is empty; cannot determine a winner.');
        }

        arsort($scores);

        return array_key_first($scores);
    }

    /**
     * Return a sorted collection of all codes with their scores (for display).
     *
     * @param  array<string, int>  $scores
     * @return Collection<int, array{code: string, score: int}>
     */
    public function rankScores(array $scores): Collection
    {
        return collect($scores)
            ->map(fn ($score, $code) => ['code' => $code, 'score' => $score])
            ->sortByDesc('score')
            ->values();
    }
}
