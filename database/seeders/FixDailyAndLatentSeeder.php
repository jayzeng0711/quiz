<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

/**
 * 修正兩個問題：
 * 1. 今日快速測驗：刪除泛化「情境題 X」題目，替換為 30 題有內容的題目
 * 2. 你的潛在性格：更新 $themes 30 題的通用選項為具體選項
 */
class FixDailyAndLatentSeeder extends Seeder
{
    public function run(): void
    {
        $this->fixDailyQuiz();
        $this->fixLatentPersonality();
        $this->command->info('✅ 今日快速測驗 & 潛在性格 已修正');
    }

    // =========================================================================
    // 今日快速測驗
    // =========================================================================
    private function fixDailyQuiz(): void
    {
        $quiz = Quiz::where('slug', 'daily-quick-quiz')->first();
        if (! $quiz) return;

        // 刪除泛化題目
        QuizQuestion::where('quiz_id', $quiz->id)
            ->where('body', 'like', '今天能量狀態情境題%')
            ->delete();

        $existing = QuizQuestion::where('quiz_id', $quiz->id)->count();

        // 新增 30 題有內容的題目
        $newQuestions = [
            [
                'body' => '今天早上，你的第一個念頭是？',
                'options' => [
                    ['key'=>'a','label'=>'已經在想今天要做什麼了','scores'=>['HIGH_ENERGY'=>3,'PRODUCTIVE'=>1],'dim_scores'=>['ENERGY'=>16,'MOMENTUM'=>12]],
                    ['key'=>'b','label'=>'想再睡五分鐘，但還好','scores'=>['REST_NEEDED'=>2,'FOCUSED'=>2],'dim_scores'=>['MOOD'=>12,'FOCUS'=>12]],
                    ['key'=>'c','label'=>'腦袋空空，慢慢感受今天','scores'=>['REFLECTIVE'=>3],'dim_scores'=>['OPENNESS'=>14,'MOOD'=>14]],
                    ['key'=>'d','label'=>'不想起床，今天感覺很重','scores'=>['REST_NEEDED'=>3],'dim_scores'=>['MOOD'=>8,'ENERGY'=>8]],
                ],
            ],
            [
                'body' => '此刻你的身體感覺是？',
                'options' => [
                    ['key'=>'a','label'=>'輕盈，感覺可以跑起來','scores'=>['HIGH_ENERGY'=>3],'dim_scores'=>['ENERGY'=>18,'MOMENTUM'=>10]],
                    ['key'=>'b','label'=>'穩定，平靜且清醒','scores'=>['FOCUSED'=>3,'PRODUCTIVE'=>1],'dim_scores'=>['FOCUS'=>16,'ENERGY'=>12]],
                    ['key'=>'c','label'=>'有點緊繃，肩膀有些沉','scores'=>['REST_NEEDED'=>2,'REFLECTIVE'=>2],'dim_scores'=>['MOOD'=>10,'FOCUS'=>12]],
                    ['key'=>'d','label'=>'沉重，需要更多休息','scores'=>['REST_NEEDED'=>3],'dim_scores'=>['MOOD'=>8,'ENERGY'=>8]],
                ],
            ],
            [
                'body' => '今天最適合做的一件事是？',
                'options' => [
                    ['key'=>'a','label'=>'推進一個重要的大計畫','scores'=>['PRODUCTIVE'=>3,'HIGH_ENERGY'=>1],'dim_scores'=>['MOMENTUM'=>16,'ENERGY'=>12]],
                    ['key'=>'b','label'=>'深度閱讀或學習新知識','scores'=>['FOCUSED'=>3,'CREATIVE'=>1],'dim_scores'=>['FOCUS'=>16,'OPENNESS'=>12]],
                    ['key'=>'c','label'=>'和重要的人好好聊聊','scores'=>['SOCIAL'=>3],'dim_scores'=>['MOOD'=>14,'OPENNESS'=>14]],
                    ['key'=>'d','label'=>'什麼都不做，好好休息','scores'=>['REST_NEEDED'=>3,'REFLECTIVE'=>1],'dim_scores'=>['MOOD'=>12,'ENERGY'=>10]],
                ],
            ],
            [
                'body' => '你現在對「今天」有什麼感受？',
                'options' => [
                    ['key'=>'a','label'=>'期待，很想看看今天有什麼','scores'=>['HIGH_ENERGY'=>3,'CREATIVE'=>1],'dim_scores'=>['ENERGY'=>16,'OPENNESS'=>12]],
                    ['key'=>'b','label'=>'平靜，按計畫走就好','scores'=>['PRODUCTIVE'=>3],'dim_scores'=>['FOCUS'=>14,'MOMENTUM'=>14]],
                    ['key'=>'c','label'=>'有點模糊，還沒進入狀態','scores'=>['REFLECTIVE'=>3],'dim_scores'=>['MOOD'=>12,'FOCUS'=>10]],
                    ['key'=>'d','label'=>'疲憊，需要被好好對待','scores'=>['REST_NEEDED'=>3],'dim_scores'=>['MOOD'=>8,'ENERGY'=>8]],
                ],
            ],
            [
                'body' => '今天如果只能完成一件事，你會選？',
                'options' => [
                    ['key'=>'a','label'=>'拖很久的重要工作','scores'=>['PRODUCTIVE'=>3,'HIGH_ENERGY'=>1],'dim_scores'=>['MOMENTUM'=>16,'FOCUS'=>12]],
                    ['key'=>'b','label'=>'一個創意或新想法','scores'=>['CREATIVE'=>3,'FLOW_STATE'=>1],'dim_scores'=>['OPENNESS'=>16,'ENERGY'=>12]],
                    ['key'=>'c','label'=>'好好陪伴某個人','scores'=>['SOCIAL'=>3],'dim_scores'=>['MOOD'=>14,'OPENNESS'=>14]],
                    ['key'=>'d','label'=>'讓自己好好充電','scores'=>['REST_NEEDED'=>3],'dim_scores'=>['MOOD'=>14,'ENERGY'=>10]],
                ],
            ],
            [
                'body' => '今天你的注意力最容易被吸引到？',
                'options' => [
                    ['key'=>'a','label'=>'未來的目標和計畫','scores'=>['HIGH_ENERGY'=>3,'PRODUCTIVE'=>1],'dim_scores'=>['MOMENTUM'=>16,'FOCUS'=>12]],
                    ['key'=>'b','label'=>'眼前有趣的細節','scores'=>['FOCUSED'=>3,'CREATIVE'=>1],'dim_scores'=>['FOCUS'=>14,'OPENNESS'=>14]],
                    ['key'=>'c','label'=>'周圍人的情緒和狀態','scores'=>['SOCIAL'=>3],'dim_scores'=>['MOOD'=>14,'OPENNESS'=>14]],
                    ['key'=>'d','label'=>'自己內心的感受','scores'=>['REFLECTIVE'=>3],'dim_scores'=>['MOOD'=>12,'FOCUS'=>12]],
                ],
            ],
            [
                'body' => '遇到一個臨時出現的機會，你今天的反應是？',
                'options' => [
                    ['key'=>'a','label'=>'馬上說好，先試試再說','scores'=>['HIGH_ENERGY'=>3,'FLOW_STATE'=>1],'dim_scores'=>['ENERGY'=>16,'MOMENTUM'=>12]],
                    ['key'=>'b','label'=>'先評估一下是否合適','scores'=>['PRODUCTIVE'=>3,'FOCUSED'=>1],'dim_scores'=>['FOCUS'=>16,'MOMENTUM'=>10]],
                    ['key'=>'c','label'=>'看看別人的反應再決定','scores'=>['SOCIAL'=>2,'REFLECTIVE'=>2],'dim_scores'=>['MOOD'=>12,'OPENNESS'=>14]],
                    ['key'=>'d','label'=>'今天狀態不對，先放著','scores'=>['REST_NEEDED'=>3],'dim_scores'=>['ENERGY'=>8,'MOOD'=>10]],
                ],
            ],
            [
                'body' => '今天最適合你節奏的工作方式是？',
                'options' => [
                    ['key'=>'a','label'=>'快速決策，快速執行','scores'=>['HIGH_ENERGY'=>3,'PRODUCTIVE'=>2],'dim_scores'=>['ENERGY'=>16,'MOMENTUM'=>12]],
                    ['key'=>'b','label'=>'深度專注，一次做好一件事','scores'=>['FOCUSED'=>3],'dim_scores'=>['FOCUS'=>18,'MOMENTUM'=>10]],
                    ['key'=>'c','label'=>'和別人一起討論、協作','scores'=>['SOCIAL'=>3],'dim_scores'=>['MOOD'=>14,'OPENNESS'=>14]],
                    ['key'=>'d','label'=>'隨意流動，跟著感覺走','scores'=>['CREATIVE'=>2,'REFLECTIVE'=>2],'dim_scores'=>['OPENNESS'=>14,'MOOD'=>12]],
                ],
            ],
            [
                'body' => '今天如果有人突然找你說話，你的感覺是？',
                'options' => [
                    ['key'=>'a','label'=>'很好，我正想找人聊聊','scores'=>['SOCIAL'=>3,'HIGH_ENERGY'=>1],'dim_scores'=>['MOOD'=>14,'OPENNESS'=>14]],
                    ['key'=>'b','label'=>'還好，看情況','scores'=>['PRODUCTIVE'=>2,'FOCUSED'=>2],'dim_scores'=>['FOCUS'=>12,'MOOD'=>12]],
                    ['key'=>'c','label'=>'有點不想被打擾','scores'=>['FOCUSED'=>3],'dim_scores'=>['FOCUS'=>14,'ENERGY'=>12]],
                    ['key'=>'d','label'=>'今天真的很需要空間','scores'=>['REST_NEEDED'=>3,'REFLECTIVE'=>1],'dim_scores'=>['MOOD'=>10,'ENERGY'=>8]],
                ],
            ],
            [
                'body' => '現在你的腦袋裡最多的是？',
                'options' => [
                    ['key'=>'a','label'=>'各種計畫和想法','scores'=>['HIGH_ENERGY'=>3,'CREATIVE'=>1],'dim_scores'=>['ENERGY'=>14,'MOMENTUM'=>14]],
                    ['key'=>'b','label'=>'一個問題，反覆在想','scores'=>['FOCUSED'=>3,'REFLECTIVE'=>1],'dim_scores'=>['FOCUS'=>16,'MOOD'=>10]],
                    ['key'=>'c','label'=>'對某個人的牽掛','scores'=>['SOCIAL'=>3],'dim_scores'=>['MOOD'=>14,'OPENNESS'=>12]],
                    ['key'=>'d','label'=>'一片空白或混亂','scores'=>['REST_NEEDED'=>3],'dim_scores'=>['MOOD'=>8,'ENERGY'=>8]],
                ],
            ],
            [
                'body' => '今天的天氣讓你有什麼感受？',
                'options' => [
                    ['key'=>'a','label'=>'很好，讓我想出門活動','scores'=>['HIGH_ENERGY'=>3],'dim_scores'=>['ENERGY'=>16,'MOOD'=>12]],
                    ['key'=>'b','label'=>'剛好適合窩在家工作','scores'=>['FOCUSED'=>3,'PRODUCTIVE'=>1],'dim_scores'=>['FOCUS'=>14,'MOOD'=>14]],
                    ['key'=>'c','label'=>'想找個人一起感受','scores'=>['SOCIAL'=>3],'dim_scores'=>['MOOD'=>14,'OPENNESS'=>14]],
                    ['key'=>'d','label'=>'讓我想躺著什麼都不做','scores'=>['REST_NEEDED'=>3],'dim_scores'=>['MOOD'=>10,'ENERGY'=>8]],
                ],
            ],
            [
                'body' => '今天你對「錯誤」的容忍度是？',
                'options' => [
                    ['key'=>'a','label'=>'高，今天願意嘗試和犯錯','scores'=>['HIGH_ENERGY'=>3,'CREATIVE'=>1],'dim_scores'=>['OPENNESS'=>16,'ENERGY'=>12]],
                    ['key'=>'b','label'=>'中，但希望不要犯大錯','scores'=>['PRODUCTIVE'=>2,'FOCUSED'=>2],'dim_scores'=>['FOCUS'=>12,'MOMENTUM'=>14]],
                    ['key'=>'c','label'=>'低，今天想要確定和穩定','scores'=>['FOCUSED'=>3],'dim_scores'=>['FOCUS'=>14,'MOMENTUM'=>12]],
                    ['key'=>'d','label'=>'很低，今天很脆弱','scores'=>['REST_NEEDED'=>3,'REFLECTIVE'=>1],'dim_scores'=>['MOOD'=>8,'ENERGY'=>8]],
                ],
            ],
            [
                'body' => '今天的午餐，你最想？',
                'options' => [
                    ['key'=>'a','label'=>'快速解決，節省時間去做事','scores'=>['PRODUCTIVE'=>3,'HIGH_ENERGY'=>1],'dim_scores'=>['MOMENTUM'=>16,'ENERGY'=>12]],
                    ['key'=>'b','label'=>'好好吃一頓，享受當下','scores'=>['FLOW_STATE'=>3,'SOCIAL'=>1],'dim_scores'=>['MOOD'=>16,'OPENNESS'=>12]],
                    ['key'=>'c','label'=>'找人一起吃，說說話','scores'=>['SOCIAL'=>3],'dim_scores'=>['MOOD'=>14,'OPENNESS'=>14]],
                    ['key'=>'d','label'=>'一個人靜靜吃，不想說話','scores'=>['REST_NEEDED'=>2,'REFLECTIVE'=>2],'dim_scores'=>['MOOD'=>12,'FOCUS'=>12]],
                ],
            ],
            [
                'body' => '今天你對「意外插入的事」的反應是？',
                'options' => [
                    ['key'=>'a','label'=>'好啊，彈性調整就好','scores'=>['HIGH_ENERGY'=>3,'CREATIVE'=>1],'dim_scores'=>['OPENNESS'=>16,'ENERGY'=>12]],
                    ['key'=>'b','label'=>'有點煩，但還是會處理','scores'=>['PRODUCTIVE'=>3],'dim_scores'=>['FOCUS'=>14,'MOMENTUM'=>12]],
                    ['key'=>'c','label'=>'很煩，今天想按計畫走','scores'=>['FOCUSED'=>3],'dim_scores'=>['FOCUS'=>16,'MOMENTUM'=>12]],
                    ['key'=>'d','label'=>'今天真的承受不住突發狀況','scores'=>['REST_NEEDED'=>3],'dim_scores'=>['MOOD'=>8,'ENERGY'=>6]],
                ],
            ],
            [
                'body' => '今天結束時，你希望能說的一句話是？',
                'options' => [
                    ['key'=>'a','label'=>'今天做了很多有意義的事','scores'=>['PRODUCTIVE'=>3,'HIGH_ENERGY'=>1],'dim_scores'=>['MOMENTUM'=>16,'ENERGY'=>12]],
                    ['key'=>'b','label'=>'今天學到了什麼新的東西','scores'=>['FOCUSED'=>3,'CREATIVE'=>1],'dim_scores'=>['FOCUS'=>14,'OPENNESS'=>14]],
                    ['key'=>'c','label'=>'今天和某個人有了真實的連結','scores'=>['SOCIAL'=>3,'FLOW_STATE'=>1],'dim_scores'=>['MOOD'=>16,'OPENNESS'=>12]],
                    ['key'=>'d','label'=>'今天好好照顧了自己','scores'=>['REST_NEEDED'=>3,'REFLECTIVE'=>1],'dim_scores'=>['MOOD'=>16,'ENERGY'=>10]],
                ],
            ],
            [
                'body' => '今天你最想聽到別人對你說？',
                'options' => [
                    ['key'=>'a','label'=>'你今天很有精神，狀態很好','scores'=>['HIGH_ENERGY'=>3],'dim_scores'=>['ENERGY'=>16,'MOOD'=>12]],
                    ['key'=>'b','label'=>'謝謝你今天的付出','scores'=>['PRODUCTIVE'=>3],'dim_scores'=>['MOMENTUM'=>14,'MOOD'=>14]],
                    ['key'=>'c','label'=>'我很想和你多說說話','scores'=>['SOCIAL'=>3],'dim_scores'=>['MOOD'=>16,'OPENNESS'=>12]],
                    ['key'=>'d','label'=>'你今天可以好好休息了','scores'=>['REST_NEEDED'=>3],'dim_scores'=>['MOOD'=>14,'ENERGY'=>8]],
                ],
            ],
            [
                'body' => '如果今天可以暫停一切，你最想做什麼？',
                'options' => [
                    ['key'=>'a','label'=>'衝出去探索什麼新地方','scores'=>['HIGH_ENERGY'=>3,'CREATIVE'=>1],'dim_scores'=>['ENERGY'=>16,'OPENNESS'=>12]],
                    ['key'=>'b','label'=>'窩在一個地方深度創作','scores'=>['FOCUSED'=>3,'FLOW_STATE'=>1],'dim_scores'=>['FOCUS'=>16,'OPENNESS'=>12]],
                    ['key'=>'c','label'=>'和最重要的人花時間','scores'=>['SOCIAL'=>3],'dim_scores'=>['MOOD'=>16,'OPENNESS'=>12]],
                    ['key'=>'d','label'=>'什麼都不做，完全空白','scores'=>['REST_NEEDED'=>3,'REFLECTIVE'=>1],'dim_scores'=>['MOOD'=>14,'ENERGY'=>10]],
                ],
            ],
            [
                'body' => '今天你的創意感受是？',
                'options' => [
                    ['key'=>'a','label'=>'靈感源源不絕，想創作','scores'=>['CREATIVE'=>3,'FLOW_STATE'=>1],'dim_scores'=>['OPENNESS'=>18,'ENERGY'=>10]],
                    ['key'=>'b','label'=>'有一些想法，但需要整理','scores'=>['FOCUSED'=>2,'CREATIVE'=>2],'dim_scores'=>['FOCUS'=>12,'OPENNESS'=>14]],
                    ['key'=>'c','label'=>'今天比較適合執行，不太有靈感','scores'=>['PRODUCTIVE'=>3],'dim_scores'=>['MOMENTUM'=>14,'FOCUS'=>14]],
                    ['key'=>'d','label'=>'腦袋一片空，什麼都想不到','scores'=>['REST_NEEDED'=>3],'dim_scores'=>['ENERGY'=>8,'MOOD'=>8]],
                ],
            ],
            [
                'body' => '今天你對未來的感覺是？',
                'options' => [
                    ['key'=>'a','label'=>'充滿可能性，很期待','scores'=>['HIGH_ENERGY'=>3,'CREATIVE'=>1],'dim_scores'=>['ENERGY'=>16,'OPENNESS'=>12]],
                    ['key'=>'b','label'=>'穩定，按計畫走就好','scores'=>['PRODUCTIVE'=>3],'dim_scores'=>['MOMENTUM'=>14,'FOCUS'=>14]],
                    ['key'=>'c','label'=>'有些模糊，需要多想想','scores'=>['REFLECTIVE'=>3],'dim_scores'=>['MOOD'=>12,'FOCUS'=>12]],
                    ['key'=>'d','label'=>'今天只想專注在當下','scores'=>['REST_NEEDED'=>2,'FLOW_STATE'=>2],'dim_scores'=>['MOOD'=>14,'OPENNESS'=>12]],
                ],
            ],
            [
                'body' => '今天你的情緒最需要的是？',
                'options' => [
                    ['key'=>'a','label'=>'出口——想表達和分享','scores'=>['SOCIAL'=>3,'HIGH_ENERGY'=>1],'dim_scores'=>['MOOD'=>14,'OPENNESS'=>14]],
                    ['key'=>'b','label'=>'空間——讓自己安靜思考','scores'=>['REFLECTIVE'=>3,'FOCUSED'=>1],'dim_scores'=>['MOOD'=>12,'FOCUS'=>14]],
                    ['key'=>'c','label'=>'行動——做點什麼讓自己好起來','scores'=>['HIGH_ENERGY'=>2,'PRODUCTIVE'=>2],'dim_scores'=>['ENERGY'=>14,'MOMENTUM'=>14]],
                    ['key'=>'d','label'=>'被接納——不需要努力，就被看見','scores'=>['REST_NEEDED'=>3],'dim_scores'=>['MOOD'=>10,'ENERGY'=>8]],
                ],
            ],
            [
                'body' => '今天你和「周圍的人」的關係感受是？',
                'options' => [
                    ['key'=>'a','label'=>'想靠近，想多互動','scores'=>['SOCIAL'=>3],'dim_scores'=>['MOOD'=>14,'OPENNESS'=>14]],
                    ['key'=>'b','label'=>'還好，維持正常就好','scores'=>['PRODUCTIVE'=>2,'FOCUSED'=>2],'dim_scores'=>['FOCUS'=>12,'MOOD'=>12]],
                    ['key'=>'c','label'=>'想保持距離，需要一個人','scores'=>['REFLECTIVE'=>3,'REST_NEEDED'=>1],'dim_scores'=>['MOOD'=>10,'FOCUS'=>14]],
                    ['key'=>'d','label'=>'有點煩躁，需要更多空間','scores'=>['REST_NEEDED'=>3],'dim_scores'=>['MOOD'=>8,'ENERGY'=>8]],
                ],
            ],
        ];

        $sortOrder = $existing + 1;
        foreach ($newQuestions as $q) {
            QuizQuestion::create([
                'quiz_id'     => $quiz->id,
                'body'        => $q['body'],
                'type'        => 'single_choice',
                'options'     => $q['options'],
                'sort_order'  => $sortOrder++,
                'is_required' => true,
            ]);
        }

        $this->command->info('今日快速測驗：已新增 ' . count($newQuestions) . ' 題，並刪除泛化題目');
    }

    // =========================================================================
    // 你的潛在性格 — 修正 $themes 30 題的通用選項
    // =========================================================================
    private function fixLatentPersonality(): void
    {
        $quiz = Quiz::where('slug', 'latent-personality')->first();
        if (! $quiz) return;

        $fixes = [
            '你覺得自己最根本的渴望是什麼？' => [
                ['key'=>'a','label'=>'被真正理解，有人懂我'],
                ['key'=>'b','label'=>'找到屬於自己的意義和方向'],
                ['key'=>'c','label'=>'在關係中感到安全和被愛'],
                ['key'=>'d','label'=>'創造出讓自己驕傲的東西'],
            ],
            '你在哪種時刻最感到迷失？' => [
                ['key'=>'a','label'=>'做的事失去了意義的時候'],
                ['key'=>'b','label'=>'不知道自己想要什麼的時候'],
                ['key'=>'c','label'=>'感到孤立、沒有連結的時候'],
                ['key'=>'d','label'=>'被迫停止創造的時候'],
            ],
            '你最深的快樂來自哪裡？' => [
                ['key'=>'a','label'=>'洞察到某個深刻的真相'],
                ['key'=>'b','label'=>'完成了一件有挑戰性的事'],
                ['key'=>'c','label'=>'和人有真實、深度的連結'],
                ['key'=>'d','label'=>'創造出讓自己驚喜的東西'],
            ],
            '你如何知道一個人值得信任？' => [
                ['key'=>'a','label'=>'他的言行一致，沒有矛盾'],
                ['key'=>'b','label'=>'直覺告訴我，說不清楚為什麼'],
                ['key'=>'c','label'=>'他在我脆弱時的反應'],
                ['key'=>'d','label'=>'他是否真正好奇你，而不只是分享自己'],
            ],
            '你在關係中最害怕的是什麼？' => [
                ['key'=>'a','label'=>'被誤解，無法真正溝通'],
                ['key'=>'b','label'=>'失去自我，消失在關係裡'],
                ['key'=>'c','label'=>'被拋棄或突然的疏遠'],
                ['key'=>'d','label'=>'關係變得無聊，失去深度'],
            ],
            '你認為自己的哪個特質是「雙面刃」？' => [
                ['key'=>'a','label'=>'想太多——深刻但消耗'],
                ['key'=>'b','label'=>'太在乎結果——有效但焦慮'],
                ['key'=>'c','label'=>'太在乎他人感受——溫暖但自我消耗'],
                ['key'=>'d','label'=>'太追求新鮮——有活力但難以安定'],
            ],
            '你如何面對自己的缺點？' => [
                ['key'=>'a','label'=>'深入分析，理解它從哪裡來'],
                ['key'=>'b','label'=>'直接行動，努力改掉它'],
                ['key'=>'c','label'=>'先接受，然後慢慢改變'],
                ['key'=>'d','label'=>'把它轉化成一種獨特的優勢'],
            ],
            '你覺得「真實的自己」在什麼時候最容易出現？' => [
                ['key'=>'a','label'=>'深夜，獨自一人的時候'],
                ['key'=>'b','label'=>'在完全信任的人面前'],
                ['key'=>'c','label'=>'全神貫注做某件事的時候'],
                ['key'=>'d','label'=>'當沒有人在評判我的時候'],
            ],
            '你相信改變一個人是可能的嗎？' => [
                ['key'=>'a','label'=>'可能，但需要極大的內在動力'],
                ['key'=>'b','label'=>'可能，但改的通常是行為，不是本質'],
                ['key'=>'c','label'=>'難，本質很難真正改變'],
                ['key'=>'d','label'=>'可能，而且愛是最大的催化劑'],
            ],
            '你最不願意承認的自己的一面是什麼？' => [
                ['key'=>'a','label'=>'有時候我其實很害怕被看見'],
                ['key'=>'b','label'=>'有時候我其實很需要控制'],
                ['key'=>'c','label'=>'有時候我其實很害怕孤獨'],
                ['key'=>'d','label'=>'有時候我其實很在意別人的評價'],
            ],
            '你在什麼情況下會感到嫉妒？' => [
                ['key'=>'a','label'=>'別人得到了我渴望的認可'],
                ['key'=>'b','label'=>'別人比我更有成就或效率'],
                ['key'=>'c','label'=>'別人和我在乎的人更親密'],
                ['key'=>'d','label'=>'別人比我更自由、更無拘束'],
            ],
            '你如何應對被拒絕的感受？' => [
                ['key'=>'a','label'=>'反覆想，試圖理解原因'],
                ['key'=>'b','label'=>'行動，證明那個拒絕是錯的'],
                ['key'=>'c','label'=>'需要很長時間才能平復'],
                ['key'=>'d','label'=>'轉身，去找新的可能性'],
            ],
            '你覺得自己最常誤解的地方是什麼？' => [
                ['key'=>'a','label'=>'別人以為我冷漠，但其實我感受很深'],
                ['key'=>'b','label'=>'別人以為我強，但我其實也需要支持'],
                ['key'=>'c','label'=>'別人以為我外向，但我其實很需要獨處'],
                ['key'=>'d','label'=>'別人以為我隨性，但我其實想很多'],
            ],
            '你在什麼時候最容易妥協？' => [
                ['key'=>'a','label'=>'當對方比我更在乎這件事的時候'],
                ['key'=>'b','label'=>'當維持關係比贏更重要的時候'],
                ['key'=>'c','label'=>'當我太累，沒有力氣堅持的時候'],
                ['key'=>'d','label'=>'當我開始懷疑自己是否正確的時候'],
            ],
            '你如何定義「內心的平靜」？' => [
                ['key'=>'a','label'=>'沒有未解決的問題在心裡'],
                ['key'=>'b','label'=>'對自己做的選擇感到坦然'],
                ['key'=>'c','label'=>'和重要的人之間沒有未說的話'],
                ['key'=>'d','label'=>'生命有方向，每天在往那裡走'],
            ],
            '你最抗拒哪種類型的人？為什麼？' => [
                ['key'=>'a','label'=>'表面的人——讓我感到空洞'],
                ['key'=>'b','label'=>'情緒化的人——讓我覺得不可預測'],
                ['key'=>'c','label'=>'冷漠的人——讓我感到被拒絕'],
                ['key'=>'d','label'=>'墨守成規的人——讓我感到窒息'],
            ],
            '你對死亡的態度是？' => [
                ['key'=>'a','label'=>'讓我更在意活著的每一刻是否有意義'],
                ['key'=>'b','label'=>'讓我想完成重要的事，留下一些東西'],
                ['key'=>'c','label'=>'讓我更珍視和重要的人的每一刻'],
                ['key'=>'d','label'=>'它提醒我，生命不需要那麼嚴肅'],
            ],
            '你覺得愛是什麼？' => [
                ['key'=>'a','label'=>'深刻地看見一個人，並被看見'],
                ['key'=>'b','label'=>'願意為某人改變和成長'],
                ['key'=>'c','label'=>'一種讓人感到安全的歸屬感'],
                ['key'=>'d','label'=>'和另一個人一起探索生命的好奇'],
            ],
            '你認為自己最深的傷是什麼？' => [
                ['key'=>'a','label'=>'曾經相信的東西崩塌了'],
                ['key'=>'b','label'=>'努力了很久，卻沒有被看見'],
                ['key'=>'c','label'=>'重要的人讓我感到被拋棄'],
                ['key'=>'d','label'=>'必須壓抑自己才能被接受'],
            ],
            '你如何對待自己的陰暗面？' => [
                ['key'=>'a','label'=>'試著理解它，它有它存在的原因'],
                ['key'=>'b','label'=>'努力控制它，不讓它影響別人'],
                ['key'=>'c','label'=>'它嚇到我，我盡量不去看它'],
                ['key'=>'d','label'=>'把它轉化成創作或能量'],
            ],
            '你覺得「接受」和「放棄」的界線在哪裡？' => [
                ['key'=>'a','label'=>'接受帶來平靜，放棄帶來逃避'],
                ['key'=>'b','label'=>'接受是內心的，放棄是行動的'],
                ['key'=>'c','label'=>'繼續傷害自己的才叫放棄，其他都是接受'],
                ['key'=>'d','label'=>'我也搞不清楚，這是我的功課'],
            ],
            '你最容易在哪種情況下感到憤怒？' => [
                ['key'=>'a','label'=>'被誤解，解釋了對方也不聽'],
                ['key'=>'b','label'=>'努力被忽視，付出沒有被看見'],
                ['key'=>'c','label'=>'重要的人讓我感到被背叛'],
                ['key'=>'d','label'=>'被限制，無法按自己的方式行動'],
            ],
            '你認為自己還沒有完全開發的潛力是什麼？' => [
                ['key'=>'a','label'=>'深度表達自己的能力'],
                ['key'=>'b','label'=>'更大膽採取行動的能力'],
                ['key'=>'c','label'=>'在關係中更真實的能力'],
                ['key'=>'d','label'=>'跳脫框架、創造新可能的能力'],
            ],
            '你在夜深人靜時最常想什麼？' => [
                ['key'=>'a','label'=>'這一切的意義是什麼'],
                ['key'=>'b','label'=>'那些還沒完成的事'],
                ['key'=>'c','label'=>'那些還沒說出口的話'],
                ['key'=>'d','label'=>'如果換一條路，會是什麼樣'],
            ],
            '如果你能改變自己的一件事，會是什麼？' => [
                ['key'=>'a','label'=>'少想多做，不被腦袋困住'],
                ['key'=>'b','label'=>'對自己更溫柔，少一些批判'],
                ['key'=>'c','label'=>'更容易表達自己的感受'],
                ['key'=>'d','label'=>'對未知更少恐懼，更多好奇'],
            ],
            '你認為你現在的生活方式反映了真正的你嗎？' => [
                ['key'=>'a','label'=>'大部分是，還在微調'],
                ['key'=>'b','label'=>'有一段距離，在努力靠近'],
                ['key'=>'c','label'=>'還差很多，但不知道怎麼改'],
                ['key'=>'d','label'=>'不確定「真正的我」是什麼'],
            ],
            '你對「傷害我的人」最常有的感受是什麼？' => [
                ['key'=>'a','label'=>'想理解他們為何這樣做'],
                ['key'=>'b','label'=>'生氣，想讓他們知道後果'],
                ['key'=>'c','label'=>'傷心，很難放下'],
                ['key'=>'d','label'=>'想遠離，不想再有交集'],
            ],
            '你覺得自己最深的愛是給了什麼或誰？' => [
                ['key'=>'a','label'=>'給了一個我深刻理解的人'],
                ['key'=>'b','label'=>'給了一件我全心投入的事'],
                ['key'=>'c','label'=>'給了一個需要我的人'],
                ['key'=>'d','label'=>'給了一個讓我不斷成長的夢想'],
            ],
            '如果你的童年可以重來，你想改變什麼？' => [
                ['key'=>'a','label'=>'更多被真正傾聽的時刻'],
                ['key'=>'b','label'=>'更多被鼓勵嘗試和犯錯的空間'],
                ['key'=>'c','label'=>'更多安全感和穩定的愛'],
                ['key'=>'d','label'=>'更多讓我自由探索的空間'],
            ],
            '你認為你的靈魂是什麼顏色？' => [
                ['key'=>'a','label'=>'深藍或紫色——深邃、神秘'],
                ['key'=>'b','label'=>'白色或金色——清澈、有力'],
                ['key'=>'c','label'=>'溫暖的橘或紅色——充滿情感'],
                ['key'=>'d','label'=>'多色——不斷變化，難以定義'],
            ],
        ];

        $scoreMap = [
            'a' => [['MYSTIC'=>3,'SEEKER'=>1],['INTUITION'=>16,'DEPTH'=>10]],
            'b' => [['CONTROLLER'=>3,'ANCHOR'=>1],['CONTROL'=>14,'STABILITY'=>12]],
            'c' => [['EMPATH'=>3],['DEPTH'=>16,'INTUITION'=>10]],
            'd' => [['DREAMER'=>3,'INNOVATOR'=>1],['CREATIVITY'=>16,'INTUITION'=>10]],
        ];

        foreach ($fixes as $body => $optionLabels) {
            $question = QuizQuestion::where('quiz_id', $quiz->id)
                ->where('body', $body)
                ->first();

            if (! $question) continue;

            $options = array_map(function ($opt) use ($scoreMap) {
                $key = $opt['key'];
                return [
                    'key'        => $key,
                    'label'      => $opt['label'],
                    'scores'     => $scoreMap[$key][0],
                    'dim_scores' => $scoreMap[$key][1],
                ];
            }, $optionLabels);

            $question->update(['options' => $options]);
        }

        $this->command->info('你的潛在性格：已修正 ' . count($fixes) . ' 題的選項');
    }
}
