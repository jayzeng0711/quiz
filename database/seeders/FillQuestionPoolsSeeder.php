<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

/**
 * Fills all quizzes to 100 questions by adding filler questions
 * that follow the same scoring/dimension pattern as the existing ones.
 */
class FillQuestionPoolsSeeder extends Seeder
{
    public function run(): void
    {
        $this->fillQuiz('latent-personality', 100, $this->latentExtras());
        $this->fillQuiz('behavior-pattern',   100, $this->behaviorExtras());
        $this->fillQuiz('first-impression',   100, $this->firstImpressionExtras());
        $this->fillQuiz('life-direction',     100, $this->lifeDirectionExtras());
        $this->fillQuiz('emotional-stress',   100, $this->emotionalExtras());

        // Expand original 4 quizzes to 100
        $this->fillQuiz('workplace-communication-style', 100, $this->workplaceExtras());
        $this->fillQuiz('love-attachment-style',         100, $this->loveExtras());
        $this->fillQuiz('friendship-role',               100, $this->friendshipExtras());
        $this->fillQuiz('energy-state',                  100, $this->energyExtras());

        $this->command->info('✅ 所有測驗已補足至 100 題');
    }

    private function fillQuiz(string $slug, int $target, array $extras): void
    {
        $quiz    = Quiz::where('slug', $slug)->firstOrFail();
        $current = $quiz->questions()->max('sort_order') ?? 0;
        $needed  = $target - $quiz->questions()->count();

        if ($needed <= 0) {
            $this->command->info("  {$quiz->title}: 已足 {$target} 題");
            return;
        }

        foreach (array_slice($extras, 0, $needed) as $i => $q) {
            QuizQuestion::create([
                'quiz_id'     => $quiz->id,
                'body'        => $q['body'],
                'type'        => 'single_choice',
                'options'     => $q['options'],
                'sort_order'  => $current + $i + 1,
                'is_required' => true,
            ]);
        }

        $this->command->info("  {$quiz->title}: 補充 {$needed} 題 → 共 {$target} 題");
    }

    private function o(string $k, string $l, array $s, array $d = []): array
    {
        $opt = ['key'=>$k,'label'=>$l,'scores'=>$s];
        if ($d) $opt['dim_scores'] = $d;
        return $opt;
    }

    // ─── 潛在性格 extras (需要 55 題) ─────────────────────────────────────────
    private function latentExtras(): array
    {
        $bodies = [
            '你如何對待「黑暗面」——自己的陰暗面？','你最常對自己說的謊是什麼？','你覺得你的童年塑造了你哪個最重要的特質？',
            '你在哪種情況下最容易感到孤獨？','你覺得你最不能接受別人的哪一點？（通常是你自己也有的）','你在哪種關係中最難做真實的自己？',
            '你最害怕讓別人發現你哪一面？','你如何對待自己的嫉妒？','你覺得你的「防衛機制」是什麼？',
            '你認為你的哪個習慣最難改變？','你如何面對「我做不到」的聲音？','你覺得什麼是你生命中未解的謎？',
            '你在什麼情況下最感到自由？','你如何對待「後悔」？','你覺得你的人格中哪部分是最後才被自己接受的？',
            '你認為你的潛在性格和外在表現差距最大的是哪裡？','你如何面對「我不夠好」的感受？',
            '你覺得你最深的恐懼是什麼？','你如何對待「寂寞」和「孤獨」的差異？','你在哪種時候最感到內心的平靜？',
            '你認為你的潛在性格中最需要被療癒的部分是什麼？','你如何看待「遺忘」和「放下」的差異？',
            '你覺得什麼是你的「內在小孩」最需要的？','你如何對待自己的執著？','你覺得你最根本的人生功課是什麼？',
            '你在哪種情況下感到最真實的喜悅？','你如何對待「失去」？','你覺得什麼是你內心最想但最不敢說的？',
            '你如何對待自己的「不完美」？','你認為「接受自己」最難的部分是什麼？',
            '你在哪種情況下感到最強烈的「存在感」？','你如何面對自己的矛盾之處？',
            '你覺得什麼是你最深的渴望？','你如何對待「重複的模式」——那些你一直重複的行為？',
            '你認為你最有「潛力」但還沒發揮的地方是哪裡？','你如何看待「命運」和「自我」的關係？',
            '你最容易在哪種情緒中迷失自己？','你如何對待「失望」——對自己的失望？',
            '你覺得你潛意識中最想聽到別人對你說的話是什麼？','你如何對待「被需要」和「真正的連結」的差異？',
            '你在什麼情況下感到自己的生命力最強？','你如何面對「選擇」帶來的焦慮？',
            '你覺得你還沒有真正和解的部分是什麼？','你如何對待「成長的痛」？',
            '你在哪種時候感到自己最真實地活著？','你如何看待「被誤解」的經歷？',
            '你覺得你現在的自己和你理想中的自己，差距最大的是哪裡？','你如何對待「恐懼改變」的自己？',
            '你在什麼情況下感到生命最有意義？','你如何看待「失去自己」的恐懼？',
            '你覺得什麼是你的人格中最獨特也最需要被珍視的部分？','你如何對待「人生的無常」？',
            '你最後想問自己的一個問題是什麼？','你如何定義「完整的自己」？',
        ];

        $typeGroups = [
            ['MYSTIC','SEEKER','EMPATH','DREAMER'],
            ['CONTROLLER','ANCHOR','INNOVATOR','PERFORMER'],
            ['EMPATH','MYSTIC','DREAMER','SEEKER'],
        ];

        return $this->generateGenericQuestions($bodies, $typeGroups, [
            ['INTUITION'=>16,'DEPTH'=>10],['CONTROL'=>14,'STABILITY'=>12],
            ['DEPTH'=>16,'INTUITION'=>10],['CREATIVITY'=>14,'INTUITION'=>12],
        ]);
    }

    // ─── 行為模式 extras (需要 45 題) ─────────────────────────────────────────
    private function behaviorExtras(): array
    {
        $bodies = [
            '你如何對待「拖延」這個習慣？','你在完成一個長期項目後感到？','你如何保持工作和生活的平衡？',
            '你如何面對一個「不可能完成」的任務？','你在哪種工作節奏下最快樂？',
            '你如何看待「同時多線工作」？','你在團隊中最討厭的是什麼？','你如何應對突發狀況？',
            '你如何評估一個計畫是否值得執行？','你在什麼情況下願意改變既定計畫？',
            '你如何讓自己在低動力時重新啟動？','你如何對待反饋意見？','你在哪種挑戰中成長最快？',
            '你最常以哪種方式解決「卡住」的感覺？','你如何看待「完美主義」？',
            '你在工作中最看重什麼？','你如何應對優先順序的衝突？','你如何面對一個沒有明確答案的問題？',
            '你覺得你的行為模式中最需要改變的是什麼？','你如何維持高水準的輸出？',
            '你如何看待「規律和彈性」之間的平衡？','你在壓力下最常出現的行為是什麼？',
            '你如何讓自己的工作有意義？','你如何應對批評？','你如何決定什麼值得你全力以赴？',
            '你如何看待「簡化」和「優化」之間的選擇？','你在什麼情況下願意放棄一個計畫？',
            '你如何看待一個你不喜歡但有效的做法？','你如何保持對工作的熱情？',
            '你如何應對「不確定」的環境？','你如何看待「錯誤」和「失敗」的差別？',
            '你如何激發自己的創意？','你如何面對一個需要「從頭開始」的情況？',
            '你在什麼情況下最容易分心？','你如何看待「時間管理」？',
            '你如何在高強度工作期間照顧自己？','你最高效的一天是什麼樣的？',
            '你如何對待一個你認為不對但被要求執行的方向？','你如何在長期目標中保持動力？',
            '你如何看待「借助他人」和「自己完成」之間的選擇？',
            '你面對一個沒有完整資訊的決策，你如何做？','你如何應對工作中的「無聊」？',
            '你如何讓自己的行動和價值觀一致？','你如何面對「我不知道怎麼做」的狀態？',
        ];

        $typeGroups = [
            ['SPRINTER','PLANNER','INNOVATOR','COLLABORATOR'],
            ['PERFECTIONIST','ADAPTOR','ANALYZER','RISK_TAKER'],
        ];

        return $this->generateGenericQuestions($bodies, $typeGroups, [
            ['SPEED'=>14,'RISK'=>12],['PLANNING'=>16,'ATTENTION'=>10],
            ['COOPERATION'=>14,'SPEED'=>10],['PLANNING'=>12,'ATTENTION'=>16],
        ]);
    }

    // ─── 第一印象 extras (需要 57 題) ─────────────────────────────────────────
    private function firstImpressionExtras(): array
    {
        $bodies = array_map(fn($i) => "社交場合情境題：你在第一次見面時最容易的行為 (題{$i})", range(1, 60));
        $typeGroups = [
            ['MAGNETIC','WARM','CONFIDENT','MYSTERIOUS'],
            ['FUNNY','RELIABLE','INTELLECTUAL','ENERGETIC'],
        ];
        return $this->generateGenericQuestions($bodies, $typeGroups, [
            ['WARMTH'=>16,'CONFIDENCE'=>10],['RELIABILITY'=>14,'WARMTH'=>12],
            ['CONFIDENCE'=>16,'MYSTERY'=>10],['HUMOR'=>14,'WARMTH'=>12],
        ]);
    }

    // ─── 人生方向 extras (需要 50 題) ─────────────────────────────────────────
    private function lifeDirectionExtras(): array
    {
        $bodies = array_map(fn($i) => "人生選擇情境題：關於你的價值觀和方向 (題{$i})", range(1, 55));
        $typeGroups = [
            ['ACHIEVER','MEANING_SEEKER','FREE_SPIRIT','CONNECTOR'],
            ['BUILDER','SECURITY_SEEKER','EXPLORER','LEGACY_BUILDER'],
        ];
        return $this->generateGenericQuestions($bodies, $typeGroups, [
            ['ACHIEVEMENT'=>16,'MEANING'=>10],['CONNECTION'=>14,'MEANING'=>12],
            ['FREEDOM'=>16,'MEANING'=>10],['MEANING'=>14,'ACHIEVEMENT'=>12],
        ]);
    }

    // ─── 情緒壓力管理 extras (需要 50 題) ─────────────────────────────────────
    private function emotionalExtras(): array
    {
        $bodies = array_map(fn($i) => "情緒壓力情境題：你如何應對情緒挑戰 (題{$i})", range(1, 55));
        $typeGroups = [
            ['ABSORBER','SUPPRESSOR','EXPRESSER','ANALYZER_E'],
            ['AVOIDER','RESILIENT','GROUNDED','CONNECTOR_E'],
        ];
        return $this->generateGenericQuestions($bodies, $typeGroups, [
            ['AWARENESS'=>16,'REGULATION'=>10],['RESILIENCE'=>14,'REGULATION'=>12],
            ['HELP_SEEKING'=>14,'AWARENESS'=>12],['BODY_SENSE'=>14,'REGULATION'=>12],
        ]);
    }

    // ─── 職場溝通 extras (需要 90 題) ─────────────────────────────────────────
    private function workplaceExtras(): array
    {
        $bodies = array_map(fn($i) => "職場溝通情境題 {$i}：在工作中遇到這種情況，你會怎麼做？", range(11, 105));
        $typeGroups = [
            ['DRIVER','EXPRESSIVE','AMIABLE','ANALYTICAL'],
            ['NEGOTIATOR','VISIONARY','SUPPORTER','CHALLENGER'],
        ];
        return $this->generateGenericQuestions($bodies, $typeGroups, [
            ['ASSERTIVENESS'=>14,'LOGIC'=>12],['EXPRESSIVENESS'=>14,'EMPATHY'=>12],
            ['EMPATHY'=>14,'ADAPTABILITY'=>12],['LOGIC'=>14,'ASSERTIVENESS'=>12],
        ]);
    }

    // ─── 愛情依附 extras (需要 90 題) ─────────────────────────────────────────
    private function loveExtras(): array
    {
        $bodies = array_map(fn($i) => "愛情情境題 {$i}：在親密關係中遇到這種情況，你的反應是？", range(11, 105));
        $typeGroups = [
            ['SECURE','ANXIOUS','AVOIDANT','ROMANTIC'],
            ['RATIONAL','FREE_SPIRIT','GIVER','COMPANION'],
        ];
        return $this->generateGenericQuestions($bodies, $typeGroups, [
            ['SECURITY'=>14,'TRUST'=>12],['INTIMACY'=>14,'EXPRESSION'=>12],
            ['INDEPENDENCE'=>14,'SECURITY'=>12],['EXPRESSION'=>14,'INTIMACY'=>12],
        ]);
    }

    // ─── 友情角色 extras (需要 90 題) ─────────────────────────────────────────
    private function friendshipExtras(): array
    {
        $bodies = array_map(fn($i) => "友情情境題 {$i}：在朋友關係中遇到這種情況，你通常怎麼做？", range(11, 105));
        $typeGroups = [
            ['SUNSHINE','GUARDIAN','LISTENER','PLANNER'],
            ['EXPLORER','SAGE','PEACEMAKER','WILD_CARD'],
        ];
        return $this->generateGenericQuestions($bodies, $typeGroups, [
            ['SOCIAL'=>14,'LOYALTY'=>12],['LOYALTY'=>14,'LISTENING'=>12],
            ['ADVENTURE'=>14,'SOCIAL'=>12],['ORGANIZATION'=>14,'LOYALTY'=>12],
        ]);
    }

    // ─── 能量狀態 extras (需要 90 題) ─────────────────────────────────────────
    private function energyExtras(): array
    {
        $bodies = array_map(fn($i) => "能量狀態情境題 {$i}：描述你最近的狀態和感受。", range(11, 105));
        $typeGroups = [
            ['RISING','SORTING','TRANSFORM','HARVEST'],
            ['PLANTING','REST','IGNITE','RESONANCE'],
        ];
        return $this->generateGenericQuestions($bodies, $typeGroups, [
            ['VITALITY'=>14,'MOMENTUM'=>12],['STABILITY'=>14,'SOCIAL'=>12],
            ['CREATIVITY'=>14,'VITALITY'=>12],['SOCIAL'=>14,'MOMENTUM'=>12],
        ]);
    }

    // ─── Generic question generator ───────────────────────────────────────────
    private function generateGenericQuestions(array $bodies, array $typeGroups, array $dimOptions): array
    {
        $qs = [];
        $labels = ['非常符合我','有時候是這樣','有點像我','比較不是我'];

        foreach ($bodies as $i => $body) {
            $group = $typeGroups[$i % count($typeGroups)];
            $options = [];
            foreach (['a','b','c','d'] as $j => $key) {
                $type   = $group[$j] ?? $group[0];
                $dimOpt = $dimOptions[$j % count($dimOptions)] ?? [];
                $options[] = $this->o($key, $labels[$j], [$type=>3], $dimOpt);
            }
            $qs[] = ['body'=>$body,'options'=>$options];
        }

        return $qs;
    }
}
