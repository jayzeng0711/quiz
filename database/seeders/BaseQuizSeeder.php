<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\ResultType;
use Illuminate\Database\Seeder;

/**
 * Base class for all quiz seeders.
 * Subclasses implement quizConfig(), resultTypes(), and questions().
 */
abstract class BaseQuizSeeder extends Seeder
{
    public function run(): void
    {
        $config = $this->quizConfig();

        $quiz = Quiz::create([
            'title'       => $config['title'],
            'description' => $config['description'],
            'slug'        => $config['slug'],
            'price'       => $config['price'] ?? 4900,
            'is_active'   => true,
            'meta'        => $config['meta'],
        ]);

        foreach ($this->resultTypes() as $i => $type) {
            ResultType::create(array_merge(['quiz_id' => $quiz->id, 'sort_order' => $i + 1], $type));
        }

        foreach ($this->questions() as $i => $q) {
            QuizQuestion::create([
                'quiz_id'     => $quiz->id,
                'body'        => $q['body'],
                'type'        => 'single_choice',
                'options'     => $q['options'],
                'sort_order'  => $i + 1,
                'is_required' => true,
            ]);
        }

        $this->command->info("✅ {$config['title']} — " . count($this->questions()) . " 題建立完成");
    }

    abstract protected function quizConfig(): array;
    abstract protected function resultTypes(): array;
    abstract protected function questions(): array;

    /**
     * Build a standard option array.
     *
     * @param  string  $key      a / b / c / d
     * @param  string  $label    option text
     * @param  array   $scores   result_type_code => points
     * @param  array   $dim      dimension_code => 0-20 contribution
     */
    protected function opt(string $key, string $label, array $scores, array $dim = []): array
    {
        $option = ['key' => $key, 'label' => $label, 'scores' => $scores];
        if (! empty($dim)) {
            $option['dim_scores'] = $dim;
        }
        return $option;
    }
}
