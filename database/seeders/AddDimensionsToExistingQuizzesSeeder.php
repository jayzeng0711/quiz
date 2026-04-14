<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

/**
 * Adds multi-dimensional scoring (dim_scores) to all existing quiz questions,
 * sets meta.dimensions per quiz, and assigns meta.collection for the homepage.
 *
 * Each option contributes up to 20 points per dimension.
 * With 10 questions answered, theoretical max per dimension = 200 → normalises to 0-100%.
 */
class AddDimensionsToExistingQuizzesSeeder extends Seeder
{
    public function run(): void
    {
        $this->updateWorkplace();
        $this->updateLove();
        $this->updateFriendship();
        $this->updateFortune();

        $this->command->info('✅ 現有 4 套測驗 meta.dimensions + dim_scores 更新完成');
    }

    // =========================================================================
    // 職場溝通風格 → collection: career
    // Dimensions: 主導性 / 表達力 / 同理心 / 邏輯性 / 適應力
    // =========================================================================
    private function updateWorkplace(): void
    {
        $quiz = Quiz::where('slug', 'workplace-communication-style')->firstOrFail();

        $dimensions = [
            ['code' => 'ASSERTIVENESS',  'label' => '主導性', 'color' => '#4f6ef7'],
            ['code' => 'EXPRESSIVENESS', 'label' => '表達力', 'color' => '#f43f5e'],
            ['code' => 'EMPATHY',        'label' => '同理心', 'color' => '#10b981'],
            ['code' => 'LOGIC',          'label' => '邏輯性', 'color' => '#f59e0b'],
            ['code' => 'ADAPTABILITY',   'label' => '適應力', 'color' => '#8b5cf6'],
        ];

        $meta = $quiz->meta ?? [];
        $meta['dimensions']        = $dimensions;
        $meta['collection']        = 'career';
        $meta['collection_order']  = 1;
        $quiz->update(['meta' => $meta]);

        // dim_scores per question: maps option key → dimension contributions
        $dimMap = [
            1 => [ // 新專案方向不一
                'a' => ['ASSERTIVENESS' => 18, 'LOGIC' => 8],
                'b' => ['EXPRESSIVENESS' => 18, 'ADAPTABILITY' => 10],
                'c' => ['LOGIC' => 14, 'ADAPTABILITY' => 8],
                'd' => ['EMPATHY' => 16, 'ADAPTABILITY' => 14],
            ],
            2 => [ // 模糊任務
                'a' => ['ASSERTIVENESS' => 16, 'ADAPTABILITY' => 12],
                'b' => ['LOGIC' => 18, 'ASSERTIVENESS' => 8],
                'c' => ['EMPATHY' => 14, 'ADAPTABILITY' => 10],
                'd' => ['EXPRESSIVENESS' => 14, 'ADAPTABILITY' => 16],
            ],
            3 => [ // 不同意資深同事
                'a' => ['ASSERTIVENESS' => 16, 'LOGIC' => 14],
                'b' => ['EMPATHY' => 16, 'ADAPTABILITY' => 12],
                'c' => ['EMPATHY' => 14, 'ADAPTABILITY' => 10],
                'd' => ['LOGIC' => 18, 'ADAPTABILITY' => 8],
            ],
            4 => [ // 發現更好做法
                'a' => ['ASSERTIVENESS' => 14, 'LOGIC' => 14],
                'b' => ['ADAPTABILITY' => 16, 'ASSERTIVENESS' => 12],
                'c' => ['EMPATHY' => 14, 'ADAPTABILITY' => 14],
                'd' => ['LOGIC' => 18, 'EMPATHY' => 8],
            ],
            5 => [ // 同事表現影響進度
                'a' => ['ASSERTIVENESS' => 18, 'EXPRESSIVENESS' => 8],
                'b' => ['EMPATHY' => 20, 'ADAPTABILITY' => 8],
                'c' => ['LOGIC' => 14, 'ADAPTABILITY' => 10],
                'd' => ['EXPRESSIVENESS' => 14, 'EMPATHY' => 12],
            ],
            6 => [ // 向陌生同事求助
                'a' => ['ASSERTIVENESS' => 16, 'LOGIC' => 10],
                'b' => ['EXPRESSIVENESS' => 18, 'EMPATHY' => 10],
                'c' => ['LOGIC' => 16, 'ADAPTABILITY' => 10],
                'd' => ['EMPATHY' => 16, 'ADAPTABILITY' => 10],
            ],
            7 => [ // 士氣低落
                'a' => ['ASSERTIVENESS' => 16, 'ADAPTABILITY' => 10],
                'b' => ['EXPRESSIVENESS' => 20, 'EMPATHY' => 8],
                'c' => ['EMPATHY' => 20, 'ADAPTABILITY' => 8],
                'd' => ['LOGIC' => 18, 'ASSERTIVENESS' => 8],
            ],
            8 => [ // 臨時被要求發表看法
                'a' => ['ASSERTIVENESS' => 14, 'EXPRESSIVENESS' => 12],
                'b' => ['LOGIC' => 16, 'EMPATHY' => 10],
                'c' => ['LOGIC' => 14, 'ADAPTABILITY' => 12],
                'd' => ['EMPATHY' => 14, 'ADAPTABILITY' => 12],
            ],
            9 => [ // 工作回饋方式
                'a' => ['ASSERTIVENESS' => 14, 'LOGIC' => 12],
                'b' => ['EMPATHY' => 16, 'EXPRESSIVENESS' => 12],
                'c' => ['LOGIC' => 18, 'ADAPTABILITY' => 8],
                'd' => ['EMPATHY' => 14, 'EXPRESSIVENESS' => 12],
            ],
            10 => [ // 最喜歡扮演哪種角色
                'a' => ['ASSERTIVENESS' => 20, 'LOGIC' => 8],
                'b' => ['EXPRESSIVENESS' => 16, 'ADAPTABILITY' => 12],
                'c' => ['LOGIC' => 14, 'ADAPTABILITY' => 12],
                'd' => ['EMPATHY' => 16, 'ADAPTABILITY' => 14],
            ],
        ];

        $this->applyDimScores($quiz, $dimMap);
    }

    // =========================================================================
    // 愛情依附風格 → collection: relationship
    // Dimensions: 安全感 / 親密需求 / 獨立性 / 情感表達 / 信任度
    // =========================================================================
    private function updateLove(): void
    {
        $quiz = Quiz::where('slug', 'love-attachment-style')->firstOrFail();

        $dimensions = [
            ['code' => 'SECURITY',    'label' => '安全感',   'color' => '#4f6ef7'],
            ['code' => 'INTIMACY',    'label' => '親密需求', 'color' => '#f43f5e'],
            ['code' => 'INDEPENDENCE','label' => '獨立性',   'color' => '#10b981'],
            ['code' => 'EXPRESSION',  'label' => '情感表達', 'color' => '#f59e0b'],
            ['code' => 'TRUST',       'label' => '信任度',   'color' => '#8b5cf6'],
        ];

        $meta = $quiz->meta ?? [];
        $meta['dimensions']       = $dimensions;
        $meta['collection']       = 'relationship';
        $meta['collection_order'] = 1;
        $quiz->update(['meta' => $meta]);

        $dimMap = [
            1  => ['a'=>['SECURITY'=>16,'TRUST'=>12],'b'=>['INTIMACY'=>16,'EXPRESSION'=>12],'c'=>['INDEPENDENCE'=>18,'SECURITY'=>8],'d'=>['SECURITY'=>14,'TRUST'=>14]],
            2  => ['a'=>['INDEPENDENCE'=>16,'SECURITY'=>10],'b'=>['SECURITY'=>16,'TRUST'=>12],'c'=>['INTIMACY'=>14,'SECURITY'=>10],'d'=>['SECURITY'=>14,'TRUST'=>14]],
            3  => ['a'=>['INTIMACY'=>16,'SECURITY'=>12],'b'=>['EXPRESSION'=>18,'INTIMACY'=>10],'c'=>['INTIMACY'=>18,'EXPRESSION'=>10],'d'=>['INDEPENDENCE'=>16,'EXPRESSION'=>12]],
            4  => ['a'=>['SECURITY'=>16,'TRUST'=>12],'b'=>['INTIMACY'=>16,'EXPRESSION'=>14],'c'=>['INDEPENDENCE'=>16,'SECURITY'=>12],'d'=>['SECURITY'=>16,'TRUST'=>12]],
            5  => ['a'=>['SECURITY'=>16,'TRUST'=>12],'b'=>['INTIMACY'=>16,'SECURITY'=>12],'c'=>['INDEPENDENCE'=>18,'SECURITY'=>8],'d'=>['SECURITY'=>14,'TRUST'=>14]],
            6  => ['a'=>['SECURITY'=>16,'EXPRESSION'=>12],'b'=>['INTIMACY'=>16,'EXPRESSION'=>14],'c'=>['INDEPENDENCE'=>16,'SECURITY'=>10],'d'=>['EXPRESSION'=>18,'INTIMACY'=>10]],
            7  => ['a'=>['INDEPENDENCE'=>18,'SECURITY'=>8],'b'=>['SECURITY'=>16,'TRUST'=>12],'c'=>['INTIMACY'=>18,'EXPRESSION'=>10],'d'=>['SECURITY'=>14,'TRUST'=>14]],
            8  => ['a'=>['INDEPENDENCE'=>16,'INTIMACY'=>12],'b'=>['INDEPENDENCE'=>18,'SECURITY'=>8],'c'=>['INTIMACY'=>18,'EXPRESSION'=>10],'d'=>['SECURITY'=>16,'TRUST'=>12]],
            9  => ['a'=>['INTIMACY'=>18,'SECURITY'=>10],'b'=>['INDEPENDENCE'=>18,'SECURITY'=>8],'c'=>['TRUST'=>16,'SECURITY'=>12],'d'=>['EXPRESSION'=>18,'INTIMACY'=>10]],
            10 => ['a'=>['SECURITY'=>16,'TRUST'=>14],'b'=>['INTIMACY'=>18,'EXPRESSION'=>10],'c'=>['INDEPENDENCE'=>18,'TRUST'=>10],'d'=>['EXPRESSION'=>18,'INTIMACY'=>10]],
        ];

        $this->applyDimScores($quiz, $dimMap);
    }

    // =========================================================================
    // 友情角色 → collection: relationship
    // Dimensions: 社交能量 / 忠誠度 / 傾聽力 / 組織力 / 冒險精神
    // =========================================================================
    private function updateFriendship(): void
    {
        $quiz = Quiz::where('slug', 'friendship-role')->firstOrFail();

        $dimensions = [
            ['code' => 'SOCIAL',       'label' => '社交能量', 'color' => '#f59e0b'],
            ['code' => 'LOYALTY',      'label' => '忠誠度',   'color' => '#4f6ef7'],
            ['code' => 'LISTENING',    'label' => '傾聽力',   'color' => '#10b981'],
            ['code' => 'ORGANIZATION', 'label' => '組織力',   'color' => '#8b5cf6'],
            ['code' => 'ADVENTURE',    'label' => '冒險精神', 'color' => '#f43f5e'],
        ];

        $meta = $quiz->meta ?? [];
        $meta['dimensions']       = $dimensions;
        $meta['collection']       = 'relationship';
        $meta['collection_order'] = 2;
        $quiz->update(['meta' => $meta]);

        $dimMap = [
            1  => ['a'=>['ORGANIZATION'=>18,'SOCIAL'=>10],'b'=>['SOCIAL'=>16,'ADVENTURE'=>12],'c'=>['LISTENING'=>16,'LOYALTY'=>12],'d'=>['LOYALTY'=>16,'ORGANIZATION'=>12]],
            2  => ['a'=>['LOYALTY'=>18,'SOCIAL'=>10],'b'=>['LISTENING'=>18,'LOYALTY'=>12],'c'=>['SOCIAL'=>16,'ADVENTURE'=>12],'d'=>['LISTENING'=>16,'LOYALTY'=>12]],
            3  => ['a'=>['LOYALTY'=>16,'LISTENING'=>14],'b'=>['LISTENING'=>18,'LOYALTY'=>12],'c'=>['ORGANIZATION'=>16,'SOCIAL'=>12],'d'=>['LOYALTY'=>16,'SOCIAL'=>12]],
            4  => ['a'=>['ORGANIZATION'=>18,'SOCIAL'=>10],'b'=>['SOCIAL'=>20,'ADVENTURE'=>8],'c'=>['ADVENTURE'=>18,'SOCIAL'=>10],'d'=>['LOYALTY'=>16,'ORGANIZATION'=>12]],
            5  => ['a'=>['LOYALTY'=>18,'LISTENING'=>10],'b'=>['ORGANIZATION'=>16,'LOYALTY'=>12],'c'=>['ADVENTURE'=>16,'ORGANIZATION'=>12],'d'=>['LOYALTY'=>20,'SOCIAL'=>8]],
            6  => ['a'=>['SOCIAL'=>18,'ADVENTURE'=>10],'b'=>['LISTENING'=>16,'LOYALTY'=>14],'c'=>['ADVENTURE'=>18,'SOCIAL'=>10],'d'=>['LOYALTY'=>16,'ORGANIZATION'=>12]],
            7  => ['a'=>['LOYALTY'=>18,'LISTENING'=>10],'b'=>['LOYALTY'=>18,'SOCIAL'=>10],'c'=>['ADVENTURE'=>16,'LISTENING'=>12],'d'=>['LISTENING'=>18,'LOYALTY'=>10]],
            8  => ['a'=>['ORGANIZATION'=>16,'LISTENING'=>12],'b'=>['SOCIAL'=>18,'LOYALTY'=>10],'c'=>['LISTENING'=>18,'LOYALTY'=>10],'d'=>['ADVENTURE'=>18,'SOCIAL'=>10]],
            9  => ['a'=>['LOYALTY'=>16,'SOCIAL'=>12],'b'=>['ADVENTURE'=>16,'ORGANIZATION'=>12],'c'=>['LOYALTY'=>18,'LISTENING'=>10],'d'=>['SOCIAL'=>18,'ADVENTURE'=>10]],
            10 => ['a'=>['LOYALTY'=>18,'LISTENING'=>10],'b'=>['LISTENING'=>18,'LOYALTY'=>10],'c'=>['LOYALTY'=>16,'ORGANIZATION'=>12],'d'=>['ADVENTURE'=>18,'SOCIAL'=>10]],
        ];

        $this->applyDimScores($quiz, $dimMap);
    }

    // =========================================================================
    // 能量狀態 → collection: energy
    // Dimensions: 活力值 / 社交能量 / 創造力 / 穩定性 / 前進動力
    // =========================================================================
    private function updateFortune(): void
    {
        $quiz = Quiz::where('slug', 'energy-state')->firstOrFail();

        $dimensions = [
            ['code' => 'VITALITY',   'label' => '活力值',   'color' => '#f59e0b'],
            ['code' => 'SOCIAL',     'label' => '社交能量', 'color' => '#4f6ef7'],
            ['code' => 'CREATIVITY', 'label' => '創造力',   'color' => '#f43f5e'],
            ['code' => 'STABILITY',  'label' => '穩定性',   'color' => '#10b981'],
            ['code' => 'MOMENTUM',   'label' => '前進動力', 'color' => '#8b5cf6'],
        ];

        $meta = $quiz->meta ?? [];
        $meta['dimensions']       = $dimensions;
        $meta['collection']       = 'energy';
        $meta['collection_order'] = 1;
        $quiz->update(['meta' => $meta]);

        $dimMap = [
            1  => ['a'=>['VITALITY'=>18,'MOMENTUM'=>10],'b'=>['STABILITY'=>16,'MOMENTUM'=>10],'c'=>['CREATIVITY'=>16,'VITALITY'=>12],'d'=>['STABILITY'=>16,'SOCIAL'=>10]],
            2  => ['a'=>['VITALITY'=>16,'MOMENTUM'=>12],'b'=>['MOMENTUM'=>16,'VITALITY'=>12],'c'=>['CREATIVITY'=>16,'VITALITY'=>12],'d'=>['STABILITY'=>16,'SOCIAL'=>12]],
            3  => ['a'=>['SOCIAL'=>18,'VITALITY'=>10],'b'=>['STABILITY'=>16,'SOCIAL'=>12],'c'=>['CREATIVITY'=>16,'MOMENTUM'=>12],'d'=>['SOCIAL'=>16,'VITALITY'=>12]],
            4  => ['a'=>['VITALITY'=>16,'MOMENTUM'=>14],'b'=>['STABILITY'=>16,'MOMENTUM'=>12],'c'=>['CREATIVITY'=>18,'MOMENTUM'=>10],'d'=>['STABILITY'=>16,'MOMENTUM'=>12]],
            5  => ['a'=>['VITALITY'=>16,'MOMENTUM'=>14],'b'=>['MOMENTUM'=>18,'CREATIVITY'=>10],'c'=>['SOCIAL'=>16,'MOMENTUM'=>12],'d'=>['STABILITY'=>16,'SOCIAL'=>12]],
            6  => ['a'=>['STABILITY'=>16,'MOMENTUM'=>12],'b'=>['STABILITY'=>18,'SOCIAL'=>8],'c'=>['CREATIVITY'=>16,'STABILITY'=>12],'d'=>['STABILITY'=>18,'SOCIAL'=>8]],
            7  => ['a'=>['VITALITY'=>18,'MOMENTUM'=>10],'b'=>['STABILITY'=>18,'SOCIAL'=>8],'c'=>['CREATIVITY'=>16,'STABILITY'=>12],'d'=>['STABILITY'=>16,'CREATIVITY'=>12]],
            8  => ['a'=>['VITALITY'=>18,'MOMENTUM'=>10],'b'=>['CREATIVITY'=>16,'STABILITY'=>12],'c'=>['STABILITY'=>18,'SOCIAL'=>8],'d'=>['CREATIVITY'=>18,'VITALITY'=>10]],
            9  => ['a'=>['SOCIAL'=>18,'CREATIVITY'=>10],'b'=>['SOCIAL'=>16,'STABILITY'=>12],'c'=>['STABILITY'=>16,'SOCIAL'=>12],'d'=>['STABILITY'=>16,'MOMENTUM'=>12]],
            10 => ['a'=>['STABILITY'=>16,'MOMENTUM'=>14],'b'=>['STABILITY'=>18,'SOCIAL'=>8],'c'=>['MOMENTUM'=>18,'CREATIVITY'=>10],'d'=>['STABILITY'=>16,'CREATIVITY'=>12]],
        ];

        $this->applyDimScores($quiz, $dimMap);
    }

    // =========================================================================
    // Helper: write dim_scores into existing question options
    // =========================================================================
    private function applyDimScores(Quiz $quiz, array $dimMap): void
    {
        $questions = $quiz->questions()->orderBy('sort_order')->get();

        foreach ($questions as $question) {
            $sortOrder = $question->sort_order;
            $dimForQ   = $dimMap[$sortOrder] ?? null;
            if (! $dimForQ) {
                continue;
            }

            $options = array_map(function (array $option) use ($dimForQ) {
                $key = $option['key'] ?? '';
                if (isset($dimForQ[$key])) {
                    $option['dim_scores'] = $dimForQ[$key];
                }
                return $option;
            }, $question->options ?? []);

            $question->update(['options' => $options]);
        }
    }
}
