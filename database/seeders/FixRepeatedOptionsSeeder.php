<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

/**
 * 修正 5 個測驗中重複選項的問題：
 * 1. 刪除選項組合重複出現的題目
 * 2. 替換為每題都有獨特、情境對應選項的新題目
 */
class FixRepeatedOptionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->fixCharisma();
        $this->fixBehavior();
        $this->fixFirstImpression();
        $this->fixLifeDirection();
        $this->fixEmotionalStress();
        $this->command->info('✅ 5 個測驗的重複選項已修正');
    }

    private function deleteRepeatedQuestions(Quiz $quiz): void
    {
        $seen = [];
        $toDelete = [];
        foreach ($quiz->questions as $q) {
            $labels = implode('|', array_column($q->options ?? [], 'label'));
            if (isset($seen[$labels])) {
                $toDelete[] = $q->id;
            } else {
                $seen[$labels] = true;
            }
        }
        if ($toDelete) {
            QuizQuestion::whereIn('id', $toDelete)->delete();
        }
        $this->command->info("  刪除重複題目: " . count($toDelete) . " 題");
    }

    private function addQuestions(Quiz $quiz, array $questions): void
    {
        $maxOrder = QuizQuestion::where('quiz_id', $quiz->id)->max('sort_order') ?? 0;
        foreach ($questions as $i => $q) {
            QuizQuestion::create([
                'quiz_id'     => $quiz->id,
                'body'        => $q['body'],
                'type'        => 'single_choice',
                'options'     => $q['options'],
                'sort_order'  => $maxOrder + $i + 1,
                'is_required' => true,
            ]);
        }
        $this->command->info("  新增題目: " . count($questions) . " 題");
    }

    // =========================================================================
    // 魅力吸引力類型
    // =========================================================================
    private function fixCharisma(): void
    {
        $quiz = Quiz::where('slug', 'charisma-attraction')->with('questions')->first();
        if (!$quiz) return;
        $this->command->info("修正：魅力吸引力類型");
        $this->deleteRepeatedQuestions($quiz);

        $this->addQuestions($quiz, [
            ['body' => '你走進一個陌生的派對，最先注意到你的人通常會說？',
             'options' => [
                ['key'=>'a','label'=>'「你一進來我就注意到你了」','scores'=>['MAGNETIC_C'=>3],'dim_scores'=>['PRESENCE'=>18,'EMOTIONAL'=>8]],
                ['key'=>'b','label'=>'「你說的那個故事真的很有趣」','scores'=>['VERBAL_C'=>3],'dim_scores'=>['VERBAL'=>16,'PRESENCE'=>10]],
                ['key'=>'c','label'=>'「你讓我覺得很放鬆，很好說話」','scores'=>['WARM_C'=>3],'dim_scores'=>['EMOTIONAL'=>16,'TRUST_BASED'=>10]],
                ['key'=>'d','label'=>'「我一直想知道你在想什麼」','scores'=>['MYSTERIOUS_C'=>3],'dim_scores'=>['MYSTERY_C'=>16,'PRESENCE'=>10]],
            ]],
            ['body' => '你和一個新認識的人說話，對方最常有什麼感受？',
             'options' => [
                ['key'=>'a','label'=>'覺得你充滿能量，讓人精神一振','scores'=>['MAGNETIC_C'=>3],'dim_scores'=>['PRESENCE'=>16,'EMOTIONAL'=>10]],
                ['key'=>'b','label'=>'覺得你很有見解，想繼續聽你說','scores'=>['INTELLECTUAL_C'=>3],'dim_scores'=>['VERBAL'=>14,'PRESENCE'=>12]],
                ['key'=>'c','label'=>'覺得你很真誠，值得信任','scores'=>['RELIABLE_C'=>3],'dim_scores'=>['TRUST_BASED'=>16,'EMOTIONAL'=>10]],
                ['key'=>'d','label'=>'覺得你有點難懂，但想要了解你','scores'=>['MYSTERIOUS_C'=>3],'dim_scores'=>['MYSTERY_C'=>18,'PRESENCE'=>8]],
            ]],
            ['body' => '你最自然展現魅力的方式是？',
             'options' => [
                ['key'=>'a','label'=>'用我的眼神和存在感讓人感受到我','scores'=>['MAGNETIC_C'=>3],'dim_scores'=>['PRESENCE'=>18,'EMOTIONAL'=>8]],
                ['key'=>'b','label'=>'用語言和故事讓對話充滿活力','scores'=>['VERBAL_C'=>3],'dim_scores'=>['VERBAL'=>18,'PRESENCE'=>8]],
                ['key'=>'c','label'=>'真心關注對方，讓他感到被重視','scores'=>['WARM_C'=>3],'dim_scores'=>['EMOTIONAL'=>16,'TRUST_BASED'=>10]],
                ['key'=>'d','label'=>'保持一點距離，讓對方充滿好奇','scores'=>['MYSTERIOUS_C'=>3],'dim_scores'=>['MYSTERY_C'=>18,'PRESENCE'=>8]],
            ]],
            ['body' => '你認為自己最吸引人的一刻是？',
             'options' => [
                ['key'=>'a','label'=>'全心投入某件事、渾然忘我的時候','scores'=>['MAGNETIC_C'=>3],'dim_scores'=>['PRESENCE'=>16,'EMOTIONAL'=>10]],
                ['key'=>'b','label'=>'說出一句讓全場安靜或大笑的話','scores'=>['VERBAL_C'=>3],'dim_scores'=>['VERBAL'=>18,'PRESENCE'=>8]],
                ['key'=>'c','label'=>'察覺到某人需要幫助並主動伸出手','scores'=>['WARM_C'=>3],'dim_scores'=>['EMOTIONAL'=>18,'TRUST_BASED'=>8]],
                ['key'=>'d','label'=>'說出一個沒人想到的角度','scores'=>['INTELLECTUAL_C'=>3],'dim_scores'=>['VERBAL'=>14,'MYSTERY_C'=>12]],
            ]],
            ['body' => '朋友說你讓他們印象最深的是什麼？',
             'options' => [
                ['key'=>'a','label'=>'你的自信，你總是知道自己是誰','scores'=>['CONFIDENT_C'=>3],'dim_scores'=>['PRESENCE'=>18,'TRUST_BASED'=>8]],
                ['key'=>'b','label'=>'你的幽默和說話方式','scores'=>['VERBAL_C'=>3],'dim_scores'=>['VERBAL'=>18,'PRESENCE'=>8]],
                ['key'=>'c','label'=>'你的溫暖，在你身邊很有安全感','scores'=>['WARM_C'=>3],'dim_scores'=>['EMOTIONAL'=>18,'TRUST_BASED'=>8]],
                ['key'=>'d','label'=>'你永遠不按牌理出牌','scores'=>['FREE_C'=>3],'dim_scores'=>['MYSTERY_C'=>14,'PRESENCE'=>12]],
            ]],
            ['body' => '你在社交場合最常的角色是？',
             'options' => [
                ['key'=>'a','label'=>'自然成為焦點，不太知道怎麼發生的','scores'=>['MAGNETIC_C'=>3],'dim_scores'=>['PRESENCE'=>18,'EMOTIONAL'=>8]],
                ['key'=>'b','label'=>'活躍的說話者，帶動氣氛','scores'=>['VERBAL_C'=>3],'dim_scores'=>['VERBAL'=>16,'PRESENCE'=>10]],
                ['key'=>'c','label'=>'安靜但可靠的支柱，讓人安心','scores'=>['RELIABLE_C'=>3],'dim_scores'=>['TRUST_BASED'=>16,'EMOTIONAL'=>10]],
                ['key'=>'d','label'=>'旁觀者，有時突然說出一句話讓大家轉頭','scores'=>['MYSTERIOUS_C'=>3],'dim_scores'=>['MYSTERY_C'=>16,'PRESENCE'=>10]],
            ]],
            ['body' => '如果你的魅力是一種天氣，會是？',
             'options' => [
                ['key'=>'a','label'=>'晴天——讓人精神大振，感到活力','scores'=>['MAGNETIC_C'=>3],'dim_scores'=>['PRESENCE'=>16,'EMOTIONAL'=>10]],
                ['key'=>'b','label'=>'微風——讓人輕鬆，帶來舒服的感覺','scores'=>['WARM_C'=>3],'dim_scores'=>['EMOTIONAL'=>18,'TRUST_BASED'=>8]],
                ['key'=>'c','label'=>'夜空——深邃，讓人想一直看','scores'=>['MYSTERIOUS_C'=>3],'dim_scores'=>['MYSTERY_C'=>18,'PRESENCE'=>8]],
                ['key'=>'d','label'=>'閃電——不預期出現，讓人印象深刻','scores'=>['FREE_C'=>3],'dim_scores'=>['PRESENCE'=>14,'MYSTERY_C'=>12]],
            ]],
            ['body' => '你覺得什麼讓你有別於多數人？',
             'options' => [
                ['key'=>'a','label'=>'我說話和做事都很直接，沒有廢話','scores'=>['CONFIDENT_C'=>3],'dim_scores'=>['PRESENCE'=>16,'TRUST_BASED'=>10]],
                ['key'=>'b','label'=>'我有辦法讓任何對話變得有趣','scores'=>['VERBAL_C'=>3],'dim_scores'=>['VERBAL'=>18,'PRESENCE'=>8]],
                ['key'=>'c','label'=>'我記得別人說過的小事','scores'=>['WARM_C'=>3],'dim_scores'=>['EMOTIONAL'=>18,'TRUST_BASED'=>8]],
                ['key'=>'d','label'=>'我的思維不按常理，讓人意外','scores'=>['INTELLECTUAL_C'=>3],'dim_scores'=>['MYSTERY_C'=>12,'VERBAL'=>14]],
            ]],
            ['body' => '你被喜歡的原因，通常是因為？',
             'options' => [
                ['key'=>'a','label'=>'人們說和我在一起有種特別的感覺','scores'=>['MAGNETIC_C'=>3],'dim_scores'=>['PRESENCE'=>18,'EMOTIONAL'=>8]],
                ['key'=>'b','label'=>'我說的話讓他們感到被理解','scores'=>['VERBAL_C'=>2,'WARM_C'=>2],'dim_scores'=>['VERBAL'=>12,'EMOTIONAL'=>14]],
                ['key'=>'c','label'=>'我說到做到，讓人覺得可以依賴我','scores'=>['RELIABLE_C'=>3],'dim_scores'=>['TRUST_BASED'=>18,'EMOTIONAL'=>8]],
                ['key'=>'d','label'=>'我讓他們感到不受拘束，可以做自己','scores'=>['FREE_C'=>3],'dim_scores'=>['EMOTIONAL'=>12,'MYSTERY_C'=>14]],
            ]],
            ['body' => '你在一段對話結束後，對方最可能對別人說的是？',
             'options' => [
                ['key'=>'a','label'=>'「他/她整個人就是很有魅力」','scores'=>['MAGNETIC_C'=>3],'dim_scores'=>['PRESENCE'=>18,'EMOTIONAL'=>8]],
                ['key'=>'b','label'=>'「他/她說話真的很有趣，很懂得表達」','scores'=>['VERBAL_C'=>3],'dim_scores'=>['VERBAL'=>18,'PRESENCE'=>8]],
                ['key'=>'c','label'=>'「他/她很溫暖，讓我覺得被接受」','scores'=>['WARM_C'=>3],'dim_scores'=>['EMOTIONAL'=>16,'TRUST_BASED'=>10]],
                ['key'=>'d','label'=>'「他/她很有深度，讓我想再了解」','scores'=>['MYSTERIOUS_C'=>2,'INTELLECTUAL_C'=>2],'dim_scores'=>['MYSTERY_C'=>12,'VERBAL'=>12]],
            ]],
            ['body' => '你最容易被哪種情境消耗？',
             'options' => [
                ['key'=>'a','label'=>'被要求成為眾人焦點卻沒準備好','scores'=>['MAGNETIC_C'=>3],'dim_scores'=>['PRESENCE'=>14,'EMOTIONAL'=>12]],
                ['key'=>'b','label'=>'被要求沉默，沒辦法表達','scores'=>['VERBAL_C'=>3],'dim_scores'=>['VERBAL'=>16,'PRESENCE'=>10]],
                ['key'=>'c','label'=>'在冷漠、沒有連結感的環境裡','scores'=>['WARM_C'=>3],'dim_scores'=>['EMOTIONAL'=>16,'TRUST_BASED'=>10]],
                ['key'=>'d','label'=>'被人完全看穿，沒有隱私的空間','scores'=>['MYSTERIOUS_C'=>3],'dim_scores'=>['MYSTERY_C'=>18,'PRESENCE'=>8]],
            ]],
            ['body' => '你展現自信的方式是？',
             'options' => [
                ['key'=>'a','label'=>'不解釋，直接行動就是答案','scores'=>['CONFIDENT_C'=>3],'dim_scores'=>['PRESENCE'=>18,'TRUST_BASED'=>8]],
                ['key'=>'b','label'=>'用清晰有力的語言表達我的立場','scores'=>['VERBAL_C'=>3],'dim_scores'=>['VERBAL'=>16,'PRESENCE'=>10]],
                ['key'=>'c','label'=>'用我長期的一致性讓人看見我','scores'=>['RELIABLE_C'=>3],'dim_scores'=>['TRUST_BASED'=>18,'EMOTIONAL'=>8]],
                ['key'=>'d','label'=>'用我的獨特讓人知道我不需要證明自己','scores'=>['FREE_C'=>3],'dim_scores'=>['MYSTERY_C'=>14,'PRESENCE'=>12]],
            ]],
            ['body' => '你認為最深刻的吸引力來自？',
             'options' => [
                ['key'=>'a','label'=>'一個人的能量場和存在感','scores'=>['MAGNETIC_C'=>3],'dim_scores'=>['PRESENCE'=>18,'EMOTIONAL'=>8]],
                ['key'=>'b','label'=>'一個人說話的方式和深度','scores'=>['VERBAL_C'=>2,'INTELLECTUAL_C'=>2],'dim_scores'=>['VERBAL'=>14,'MYSTERY_C'=>12]],
                ['key'=>'c','label'=>'一個人讓你感到安全的能力','scores'=>['WARM_C'=>2,'RELIABLE_C'=>2],'dim_scores'=>['EMOTIONAL'=>14,'TRUST_BASED'=>12]],
                ['key'=>'d','label'=>'一個人身上那種說不清楚的神秘感','scores'=>['MYSTERIOUS_C'=>3],'dim_scores'=>['MYSTERY_C'=>18,'PRESENCE'=>8]],
            ]],
            ['body' => '你在和陌生人互動時，最自然的開場是？',
             'options' => [
                ['key'=>'a','label'=>'直接上前，用我的自信打破沉默','scores'=>['CONFIDENT_C'=>3],'dim_scores'=>['PRESENCE'=>16,'TRUST_BASED'=>10]],
                ['key'=>'b','label'=>'說一個笑話或有趣的觀察','scores'=>['VERBAL_C'=>3],'dim_scores'=>['VERBAL'=>18,'PRESENCE'=>8]],
                ['key'=>'c','label'=>'注意到對方需要什麼，從那裡開始','scores'=>['WARM_C'=>3],'dim_scores'=>['EMOTIONAL'=>16,'TRUST_BASED'=>10]],
                ['key'=>'d','label'=>'讓對方先注意到我，再慢慢回應','scores'=>['MYSTERIOUS_C'=>3],'dim_scores'=>['MYSTERY_C'=>16,'PRESENCE'=>10]],
            ]],
            ['body' => '你在一個重要場合的表現，讓人最常評論的是？',
             'options' => [
                ['key'=>'a','label'=>'「你真的很有氣場」','scores'=>['MAGNETIC_C'=>3],'dim_scores'=>['PRESENCE'=>18,'EMOTIONAL'=>8]],
                ['key'=>'b','label'=>'「你說話很有說服力」','scores'=>['VERBAL_C'=>3],'dim_scores'=>['VERBAL'=>18,'PRESENCE'=>8]],
                ['key'=>'c','label'=>'「你讓整個氣氛變得更舒服」','scores'=>['WARM_C'=>3],'dim_scores'=>['EMOTIONAL'=>16,'TRUST_BASED'=>10]],
                ['key'=>'d','label'=>'「你讓人覺得你很有內涵」','scores'=>['INTELLECTUAL_C'=>2,'MYSTERIOUS_C'=>2],'dim_scores'=>['MYSTERY_C'=>12,'VERBAL'=>12]],
            ]],
            ['body' => '如果要提升你的吸引力，你最願意投資在？',
             'options' => [
                ['key'=>'a','label'=>'打磨我的存在感和整體氣場','scores'=>['MAGNETIC_C'=>3],'dim_scores'=>['PRESENCE'=>18,'EMOTIONAL'=>8]],
                ['key'=>'b','label'=>'練習說故事和表達的技巧','scores'=>['VERBAL_C'=>3],'dim_scores'=>['VERBAL'=>18,'PRESENCE'=>8]],
                ['key'=>'c','label'=>'深化我對他人感受的理解','scores'=>['WARM_C'=>3],'dim_scores'=>['EMOTIONAL'=>18,'TRUST_BASED'=>8]],
                ['key'=>'d','label'=>'讓自己更有深度和獨特性','scores'=>['INTELLECTUAL_C'=>2,'FREE_C'=>2],'dim_scores'=>['MYSTERY_C'=>12,'VERBAL'=>12]],
            ]],
            ['body' => '你認為讓人長期被你吸引的原因是？',
             'options' => [
                ['key'=>'a','label'=>'我一直有辦法讓人感到驚喜','scores'=>['MAGNETIC_C'=>3],'dim_scores'=>['PRESENCE'=>16,'EMOTIONAL'=>10]],
                ['key'=>'b','label'=>'我的話讓他們在不同時刻還會想到','scores'=>['VERBAL_C'=>3],'dim_scores'=>['VERBAL'=>18,'PRESENCE'=>8]],
                ['key'=>'c','label'=>'他們知道我是真的在乎他們','scores'=>['WARM_C'=>3],'dim_scores'=>['EMOTIONAL'=>16,'TRUST_BASED'=>10]],
                ['key'=>'d','label'=>'我總是有沒有被完全了解的部分','scores'=>['MYSTERIOUS_C'=>3],'dim_scores'=>['MYSTERY_C'=>18,'PRESENCE'=>8]],
            ]],
            ['body' => '你在意的人說他們被你吸引，最可能的理由是？',
             'options' => [
                ['key'=>'a','label'=>'「和你在一起我感覺整個世界都不一樣」','scores'=>['MAGNETIC_C'=>3],'dim_scores'=>['PRESENCE'=>18,'EMOTIONAL'=>8]],
                ['key'=>'b','label'=>'「你讓對話變得真正有意義」','scores'=>['VERBAL_C'=>2,'INTELLECTUAL_C'=>2],'dim_scores'=>['VERBAL'=>14,'MYSTERY_C'=>12]],
                ['key'=>'c','label'=>'「你讓我覺得我可以做真實的自己」','scores'=>['WARM_C'=>3],'dim_scores'=>['EMOTIONAL'=>16,'TRUST_BASED'=>10]],
                ['key'=>'d','label'=>'「你讓我一直想更了解你」','scores'=>['MYSTERIOUS_C'=>3],'dim_scores'=>['MYSTERY_C'=>18,'PRESENCE'=>8]],
            ]],
        ]);
    }

    // =========================================================================
    // 行為模式
    // =========================================================================
    private function fixBehavior(): void
    {
        $quiz = Quiz::where('slug', 'behavior-pattern')->with('questions')->first();
        if (!$quiz) return;
        $this->command->info("修正：行為模式");
        $this->deleteRepeatedQuestions($quiz);

        $this->addQuestions($quiz, [
            ['body' => '接到一個新任務，你的第一反應是？',
             'options' => [
                ['key'=>'a','label'=>'立刻想好執行步驟和時間表','scores'=>['PLANNER'=>3],'dim_scores'=>['PLANNING'=>18,'ATTENTION'=>8]],
                ['key'=>'b','label'=>'馬上開始做，邊做邊調整','scores'=>['IMPULSE'=>3],'dim_scores'=>['SPEED'=>18,'RISK'=>8]],
                ['key'=>'c','label'=>'先收集足夠的資訊再決定怎麼做','scores'=>['ANALYZER'=>3],'dim_scores'=>['PLANNING'=>14,'ATTENTION'=>12]],
                ['key'=>'d','label'=>'評估可能遇到的障礙和風險','scores'=>['RISK_TAKER'=>2,'ANALYZER'=>2],'dim_scores'=>['RISK'=>14,'PLANNING'=>12]],
            ]],
            ['body' => '在一個需要快速決策的情況，你通常？',
             'options' => [
                ['key'=>'a','label'=>'靠直覺，先行動再說','scores'=>['IMPULSE'=>3],'dim_scores'=>['SPEED'=>18,'RISK'=>8]],
                ['key'=>'b','label'=>'快速評估最關鍵的幾個因素後決定','scores'=>['ANALYZER'=>3],'dim_scores'=>['PLANNING'=>14,'ATTENTION'=>12]],
                ['key'=>'c','label'=>'問問周圍的人怎麼看','scores'=>['COLLABORATOR'=>3],'dim_scores'=>['COOPERATION'=>18,'SPEED'=>8]],
                ['key'=>'d','label'=>'選一個還說得過去的選項，避免拖太久','scores'=>['PLANNER'=>2,'IMPULSE'=>2],'dim_scores'=>['SPEED'=>12,'PLANNING'=>14]],
            ]],
            ['body' => '面對一個困難的問題，你怎麼處理？',
             'options' => [
                ['key'=>'a','label'=>'把它拆解成小步驟，逐一解決','scores'=>['PLANNER'=>3],'dim_scores'=>['PLANNING'=>16,'ATTENTION'=>10]],
                ['key'=>'b','label'=>'腦力激盪，想出所有可能的方法','scores'=>['RISK_TAKER'=>2,'ANALYZER'=>2],'dim_scores'=>['RISK'=>12,'PLANNING'=>14]],
                ['key'=>'c','label'=>'找有經驗的人討論，集思廣益','scores'=>['COLLABORATOR'=>3],'dim_scores'=>['COOPERATION'=>18,'SPEED'=>8]],
                ['key'=>'d','label'=>'深入研究問題背後的根本原因','scores'=>['ANALYZER'=>3],'dim_scores'=>['ATTENTION'=>16,'PLANNING'=>10]],
            ]],
            ['body' => '你在完成一項工作時，最在意的是？',
             'options' => [
                ['key'=>'a','label'=>'做得快，效率高','scores'=>['IMPULSE'=>3],'dim_scores'=>['SPEED'=>18,'RISK'=>8]],
                ['key'=>'b','label'=>'做得好，沒有細節被忽略','scores'=>['DETAIL'=>3],'dim_scores'=>['ATTENTION'=>18,'PLANNING'=>8]],
                ['key'=>'c','label'=>'做到整體方向是對的','scores'=>['BIG_PICTURE'=>3],'dim_scores'=>['PLANNING'=>14,'RISK'=>12]],
                ['key'=>'d','label'=>'做得讓相關的人都滿意','scores'=>['COLLABORATOR'=>3],'dim_scores'=>['COOPERATION'=>18,'SPEED'=>8]],
            ]],
            ['body' => '你在一個團隊中通常扮演的角色是？',
             'options' => [
                ['key'=>'a','label'=>'制定計畫和追蹤進度的人','scores'=>['PLANNER'=>3],'dim_scores'=>['PLANNING'=>18,'ATTENTION'=>8]],
                ['key'=>'b','label'=>'衝鋒陷陣，把事情推進的人','scores'=>['IMPULSE'=>3],'dim_scores'=>['SPEED'=>16,'RISK'=>10]],
                ['key'=>'c','label'=>'確保細節都做到位的人','scores'=>['DETAIL'=>3],'dim_scores'=>['ATTENTION'=>18,'PLANNING'=>8]],
                ['key'=>'d','label'=>'協調各方，維繫合作的人','scores'=>['COLLABORATOR'=>3],'dim_scores'=>['COOPERATION'=>18,'SPEED'=>8]],
            ]],
            ['body' => '你在執行計畫時，最讓你不安的情況是？',
             'options' => [
                ['key'=>'a','label'=>'計畫被突然改變，必須即時調整','scores'=>['PLANNER'=>3],'dim_scores'=>['PLANNING'=>16,'ATTENTION'=>10]],
                ['key'=>'b','label'=>'事情進展太慢，卡在某個地方','scores'=>['IMPULSE'=>3],'dim_scores'=>['SPEED'=>18,'RISK'=>8]],
                ['key'=>'c','label'=>'沒有足夠的資訊就必須做決定','scores'=>['ANALYZER'=>3],'dim_scores'=>['ATTENTION'=>16,'PLANNING'=>10]],
                ['key'=>'d','label'=>'沒有人在意細節，事情做得很粗糙','scores'=>['DETAIL'=>3],'dim_scores'=>['ATTENTION'=>18,'PLANNING'=>8]],
            ]],
            ['body' => '你習慣在什麼時候做重要決定？',
             'options' => [
                ['key'=>'a','label'=>'想清楚所有面向再做','scores'=>['ANALYZER'=>3],'dim_scores'=>['PLANNING'=>14,'ATTENTION'=>12]],
                ['key'=>'b','label'=>'感覺對的時候就做','scores'=>['IMPULSE'=>3],'dim_scores'=>['SPEED'=>16,'RISK'=>10]],
                ['key'=>'c','label'=>'收集足夠資訊後，按計畫做','scores'=>['PLANNER'=>3],'dim_scores'=>['PLANNING'=>18,'ATTENTION'=>8]],
                ['key'=>'d','label'=>'討論過後，有共識才做','scores'=>['COLLABORATOR'=>3],'dim_scores'=>['COOPERATION'=>18,'SPEED'=>8]],
            ]],
            ['body' => '你認為好的工作成果最重要的是？',
             'options' => [
                ['key'=>'a','label'=>'有清晰的邏輯和結構','scores'=>['ANALYZER'=>2,'PLANNER'=>2],'dim_scores'=>['PLANNING'=>14,'ATTENTION'=>12]],
                ['key'=>'b','label'=>'在時間內高效率完成','scores'=>['IMPULSE'=>3],'dim_scores'=>['SPEED'=>18,'RISK'=>8]],
                ['key'=>'c','label'=>'每個細節都無懈可擊','scores'=>['DETAIL'=>3],'dim_scores'=>['ATTENTION'=>18,'PLANNING'=>8]],
                ['key'=>'d','label'=>'整體方向和格局是對的','scores'=>['BIG_PICTURE'=>3],'dim_scores'=>['PLANNING'=>12,'RISK'=>14]],
            ]],
            ['body' => '當你需要說服別人接受你的想法時，你通常用什麼方式？',
             'options' => [
                ['key'=>'a','label'=>'提出清晰的數據和邏輯論證','scores'=>['ANALYZER'=>3],'dim_scores'=>['PLANNING'=>14,'ATTENTION'=>12]],
                ['key'=>'b','label'=>'直接示範，讓結果說話','scores'=>['IMPULSE'=>3],'dim_scores'=>['SPEED'=>16,'RISK'=>10]],
                ['key'=>'c','label'=>'先了解對方的顧慮，再找共識','scores'=>['COLLABORATOR'=>3],'dim_scores'=>['COOPERATION'=>18,'SPEED'=>8]],
                ['key'=>'d','label'=>'描繪大圖景，讓對方看到可能性','scores'=>['BIG_PICTURE'=>3],'dim_scores'=>['PLANNING'=>12,'RISK'=>14]],
            ]],
            ['body' => '你對「犯錯」的態度是？',
             'options' => [
                ['key'=>'a','label'=>'盡量避免，事前做好充分準備','scores'=>['PLANNER'=>3],'dim_scores'=>['PLANNING'=>16,'ATTENTION'=>10]],
                ['key'=>'b','label'=>'犯錯是正常的，快速修正就好','scores'=>['IMPULSE'=>3],'dim_scores'=>['SPEED'=>16,'RISK'=>10]],
                ['key'=>'c','label'=>'深入分析，確保同樣的錯不再發生','scores'=>['ANALYZER'=>3],'dim_scores'=>['ATTENTION'=>16,'PLANNING'=>10]],
                ['key'=>'d','label'=>'看是哪種錯，有些值得冒險嘗試','scores'=>['RISK_TAKER'=>3],'dim_scores'=>['RISK'=>18,'SPEED'=>8]],
            ]],
            ['body' => '在一個創新的項目裡，你最擅長什麼？',
             'options' => [
                ['key'=>'a','label'=>'建立可執行的計畫和流程','scores'=>['PLANNER'=>3],'dim_scores'=>['PLANNING'=>18,'ATTENTION'=>8]],
                ['key'=>'b','label'=>'快速迭代和嘗試','scores'=>['IMPULSE'=>2,'RISK_TAKER'=>2],'dim_scores'=>['SPEED'=>14,'RISK'=>12]],
                ['key'=>'c','label'=>'確保所有細節都被考慮到','scores'=>['DETAIL'=>3],'dim_scores'=>['ATTENTION'=>18,'PLANNING'=>8]],
                ['key'=>'d','label'=>'看到整體趨勢和未來方向','scores'=>['BIG_PICTURE'=>3],'dim_scores'=>['PLANNING'=>10,'RISK'=>16]],
            ]],
            ['body' => '你在壓力下最典型的行為模式是？',
             'options' => [
                ['key'=>'a','label'=>'更努力規劃，把每件事都列出來','scores'=>['PLANNER'=>3],'dim_scores'=>['PLANNING'=>16,'ATTENTION'=>10]],
                ['key'=>'b','label'=>'加快節奏，用行動消耗壓力','scores'=>['IMPULSE'=>3],'dim_scores'=>['SPEED'=>18,'RISK'=>8]],
                ['key'=>'c','label'=>'找人討論，分擔壓力','scores'=>['COLLABORATOR'=>3],'dim_scores'=>['COOPERATION'=>18,'SPEED'=>8]],
                ['key'=>'d','label'=>'找出問題的根本，從源頭解決','scores'=>['ANALYZER'=>3],'dim_scores'=>['ATTENTION'=>16,'PLANNING'=>10]],
            ]],
            ['body' => '你覺得什麼讓你在工作上最有效率？',
             'options' => [
                ['key'=>'a','label'=>'清晰的目標和詳細的計畫','scores'=>['PLANNER'=>3],'dim_scores'=>['PLANNING'=>18,'ATTENTION'=>8]],
                ['key'=>'b','label'=>'充足的自主空間，自己決定怎麼做','scores'=>['SOLO_PERFORMER'=>3],'dim_scores'=>['SPEED'=>14,'RISK'=>12]],
                ['key'=>'c','label'=>'有好的夥伴一起協作','scores'=>['COLLABORATOR'=>3],'dim_scores'=>['COOPERATION'=>18,'SPEED'=>8]],
                ['key'=>'d','label'=>'對細節的高度掌握','scores'=>['DETAIL'=>3],'dim_scores'=>['ATTENTION'=>18,'PLANNING'=>8]],
            ]],
            ['body' => '你在開始一個新項目前，最先做什麼？',
             'options' => [
                ['key'=>'a','label'=>'畫出整個計畫的架構','scores'=>['PLANNER'=>3],'dim_scores'=>['PLANNING'=>18,'ATTENTION'=>8]],
                ['key'=>'b','label'=>'直接開始，從做中學','scores'=>['IMPULSE'=>3],'dim_scores'=>['SPEED'=>18,'RISK'=>8]],
                ['key'=>'c','label'=>'研究相關案例和資料','scores'=>['ANALYZER'=>3],'dim_scores'=>['ATTENTION'=>16,'PLANNING'=>10]],
                ['key'=>'d','label'=>'和相關人員對齊期望','scores'=>['COLLABORATOR'=>3],'dim_scores'=>['COOPERATION'=>18,'SPEED'=>8]],
            ]],
        ]);
    }

    // =========================================================================
    // 第一印象類型
    // =========================================================================
    private function fixFirstImpression(): void
    {
        $quiz = Quiz::where('slug', 'first-impression')->with('questions')->first();
        if (!$quiz) return;
        $this->command->info("修正：第一印象類型");
        $this->deleteRepeatedQuestions($quiz);

        $this->addQuestions($quiz, [
            ['body' => '第一次見到陌生人，你最先注意到的是？',
             'options' => [
                ['key'=>'a','label'=>'他們的眼神和整體能量','scores'=>['INTUITIVE_FI'=>3],'dim_scores'=>['INTUITION'=>18,'WARMTH'=>8]],
                ['key'=>'b','label'=>'他們的穿著和外在形象','scores'=>['VISUAL_FI'=>3],'dim_scores'=>['OBSERVATION'=>16,'INTUITION'=>10]],
                ['key'=>'c','label'=>'他們說話的方式和內容','scores'=>['VERBAL_FI'=>3],'dim_scores'=>['ANALYSIS'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'他們對我和他人的態度','scores'=>['SOCIAL_FI'=>3],'dim_scores'=>['WARMTH'=>16,'OBSERVATION'=>10]],
            ]],
            ['body' => '你給別人留下的第一印象，朋友說通常是？',
             'options' => [
                ['key'=>'a','label'=>'看起來很有自信，很有氣場','scores'=>['CONFIDENT_FI'=>3],'dim_scores'=>['CONFIDENCE'=>18,'WARMTH'=>8]],
                ['key'=>'b','label'=>'感覺很親切，容易接近','scores'=>['WARM_FI'=>3],'dim_scores'=>['WARMTH'=>18,'OBSERVATION'=>8]],
                ['key'=>'c','label'=>'有點難以捉摸，讓人好奇','scores'=>['MYSTERIOUS_FI'=>3],'dim_scores'=>['MYSTERY'=>18,'CONFIDENCE'=>8]],
                ['key'=>'d','label'=>'思路清晰，很有見地','scores'=>['ANALYTICAL_FI'=>3],'dim_scores'=>['ANALYSIS'=>18,'CONFIDENCE'=>8]],
            ]],
            ['body' => '在第一次見面時，你更傾向？',
             'options' => [
                ['key'=>'a','label'=>'主動出擊，控制整個互動的節奏','scores'=>['CONFIDENT_FI'=>3],'dim_scores'=>['CONFIDENCE'=>18,'WARMTH'=>8]],
                ['key'=>'b','label'=>'讓對方先說，我來接話','scores'=>['WARM_FI'=>2,'SOCIAL_FI'=>2],'dim_scores'=>['WARMTH'=>14,'OBSERVATION'=>12]],
                ['key'=>'c','label'=>'靜靜觀察，決定何時介入','scores'=>['MYSTERIOUS_FI'=>3],'dim_scores'=>['MYSTERY'=>16,'OBSERVATION'=>10]],
                ['key'=>'d','label'=>'找一個有深度的話題快速建立連結','scores'=>['ANALYTICAL_FI'=>3],'dim_scores'=>['ANALYSIS'=>16,'CONFIDENCE'=>10]],
            ]],
            ['body' => '你認為第一印象最重要的元素是？',
             'options' => [
                ['key'=>'a','label'=>'自信的姿態和眼神','scores'=>['CONFIDENT_FI'=>3],'dim_scores'=>['CONFIDENCE'=>18,'WARMTH'=>8]],
                ['key'=>'b','label'=>'真誠的微笑和溫暖','scores'=>['WARM_FI'=>3],'dim_scores'=>['WARMTH'=>18,'OBSERVATION'=>8]],
                ['key'=>'c','label'=>'說話的深度和獨特觀點','scores'=>['ANALYTICAL_FI'=>3],'dim_scores'=>['ANALYSIS'=>16,'CONFIDENCE'=>10]],
                ['key'=>'d','label'=>'整體的外在形象和風格','scores'=>['VISUAL_FI'=>3],'dim_scores'=>['OBSERVATION'=>16,'CONFIDENCE'=>10]],
            ]],
            ['body' => '面試官或重要人物第一次見到你，最可能的第一印象是？',
             'options' => [
                ['key'=>'a','label'=>'這個人很有主見，知道自己要什麼','scores'=>['CONFIDENT_FI'=>3],'dim_scores'=>['CONFIDENCE'=>18,'WARMTH'=>8]],
                ['key'=>'b','label'=>'這個人很好相處，有合作的可能','scores'=>['WARM_FI'=>3],'dim_scores'=>['WARMTH'=>18,'OBSERVATION'=>8]],
                ['key'=>'c','label'=>'這個人很有深度，值得深入了解','scores'=>['ANALYTICAL_FI'=>2,'MYSTERIOUS_FI'=>2],'dim_scores'=>['ANALYSIS'=>12,'MYSTERY'=>14]],
                ['key'=>'d','label'=>'這個人很有活力，帶來好的能量','scores'=>['SOCIAL_FI'=>3],'dim_scores'=>['WARMTH'=>14,'CONFIDENCE'=>12]],
            ]],
            ['body' => '你在第一次見面後，對方對你的記憶最常是？',
             'options' => [
                ['key'=>'a','label'=>'記得你說過的某句讓他們印象深刻的話','scores'=>['VERBAL_FI'=>3],'dim_scores'=>['ANALYSIS'=>16,'CONFIDENCE'=>10]],
                ['key'=>'b','label'=>'記得你給他們的感覺，很溫暖','scores'=>['WARM_FI'=>3],'dim_scores'=>['WARMTH'=>18,'OBSERVATION'=>8]],
                ['key'=>'c','label'=>'記得你的形象，有種特別的風格','scores'=>['VISUAL_FI'=>3],'dim_scores'=>['OBSERVATION'=>16,'CONFIDENCE'=>10]],
                ['key'=>'d','label'=>'記得你有點難以看穿，想再了解','scores'=>['MYSTERIOUS_FI'=>3],'dim_scores'=>['MYSTERY'=>18,'CONFIDENCE'=>8]],
            ]],
            ['body' => '你在社交場合中，最常被評為？',
             'options' => [
                ['key'=>'a','label'=>'很有存在感，難以被忽略','scores'=>['CONFIDENT_FI'=>3],'dim_scores'=>['CONFIDENCE'=>18,'WARMTH'=>8]],
                ['key'=>'b','label'=>'很親和，讓人放鬆','scores'=>['WARM_FI'=>3],'dim_scores'=>['WARMTH'=>18,'OBSERVATION'=>8]],
                ['key'=>'c','label'=>'有點特別，和別人不一樣','scores'=>['MYSTERIOUS_FI'=>3],'dim_scores'=>['MYSTERY'=>16,'OBSERVATION'=>10]],
                ['key'=>'d','label'=>'有思想，說話有料','scores'=>['ANALYTICAL_FI'=>3],'dim_scores'=>['ANALYSIS'=>18,'CONFIDENCE'=>8]],
            ]],
            ['body' => '如果你只有30秒讓人記住你，你會怎麼做？',
             'options' => [
                ['key'=>'a','label'=>'說一句讓人印象深刻的話','scores'=>['VERBAL_FI'=>3],'dim_scores'=>['ANALYSIS'=>14,'CONFIDENCE'=>12]],
                ['key'=>'b','label'=>'展現我最真實、最自然的一面','scores'=>['WARM_FI'=>3],'dim_scores'=>['WARMTH'=>16,'OBSERVATION'=>10]],
                ['key'=>'c','label'=>'讓我的整體形象和風格說話','scores'=>['VISUAL_FI'=>3],'dim_scores'=>['OBSERVATION'=>16,'CONFIDENCE'=>10]],
                ['key'=>'d','label'=>'問一個讓對方思考的問題','scores'=>['ANALYTICAL_FI'=>3],'dim_scores'=>['ANALYSIS'=>18,'CONFIDENCE'=>8]],
            ]],
            ['body' => '你最不喜歡被人第一眼誤解為？',
             'options' => [
                ['key'=>'a','label'=>'傲慢或難以接近','scores'=>['CONFIDENT_FI'=>3],'dim_scores'=>['CONFIDENCE'=>16,'WARMTH'=>10]],
                ['key'=>'b','label'=>'軟弱或沒有主見','scores'=>['WARM_FI'=>3],'dim_scores'=>['WARMTH'=>14,'CONFIDENCE'=>12]],
                ['key'=>'c','label'=>'太普通，沒有記憶點','scores'=>['MYSTERIOUS_FI'=>3],'dim_scores'=>['MYSTERY'=>16,'OBSERVATION'=>10]],
                ['key'=>'d','label'=>'無趣或過於嚴肅','scores'=>['ANALYTICAL_FI'=>3],'dim_scores'=>['ANALYSIS'=>14,'CONFIDENCE'=>12]],
            ]],
            ['body' => '你認為第一印象裡最難以偽裝的是？',
             'options' => [
                ['key'=>'a','label'=>'一個人的自信程度','scores'=>['CONFIDENT_FI'=>3],'dim_scores'=>['CONFIDENCE'=>18,'WARMTH'=>8]],
                ['key'=>'b','label'=>'一個人是否真的在乎對方','scores'=>['WARM_FI'=>3],'dim_scores'=>['WARMTH'=>18,'OBSERVATION'=>8]],
                ['key'=>'c','label'=>'一個人的眼神和氣場','scores'=>['INTUITIVE_FI'=>3],'dim_scores'=>['INTUITION'=>18,'WARMTH'=>8]],
                ['key'=>'d','label'=>'一個人說話時的真實底氣','scores'=>['VERBAL_FI'=>3],'dim_scores'=>['ANALYSIS'=>14,'CONFIDENCE'=>12]],
            ]],
            ['body' => '你在第一次見面時最擔心的事是？',
             'options' => [
                ['key'=>'a','label'=>'看起來不夠自信或不夠有氣場','scores'=>['CONFIDENT_FI'=>3],'dim_scores'=>['CONFIDENCE'=>16,'WARMTH'=>10]],
                ['key'=>'b','label'=>'讓對方感到不舒服或疏遠','scores'=>['WARM_FI'=>3],'dim_scores'=>['WARMTH'=>16,'OBSERVATION'=>10]],
                ['key'=>'c','label'=>'被認為很無聊或沒有特色','scores'=>['MYSTERIOUS_FI'=>3],'dim_scores'=>['MYSTERY'=>16,'OBSERVATION'=>10]],
                ['key'=>'d','label'=>'說錯話或給錯誤的印象','scores'=>['VERBAL_FI'=>3],'dim_scores'=>['ANALYSIS'=>14,'CONFIDENCE'=>12]],
            ]],
        ]);
    }

    // =========================================================================
    // 人生方向
    // =========================================================================
    private function fixLifeDirection(): void
    {
        $quiz = Quiz::where('slug', 'life-direction')->with('questions')->first();
        if (!$quiz) return;
        $this->command->info("修正：人生方向");
        $this->deleteRepeatedQuestions($quiz);

        $this->addQuestions($quiz, [
            ['body' => '你在做決定時，最難放棄的是什麼？',
             'options' => [
                ['key'=>'a','label'=>'達成目標的機會','scores'=>['ACHIEVER'=>3],'dim_scores'=>['ACHIEVEMENT'=>18,'MEANING'=>8]],
                ['key'=>'b','label'=>'能夠幫助到他人的可能性','scores'=>['MEANING_SEEKER'=>3],'dim_scores'=>['MEANING'=>16,'CONNECTION'=>10]],
                ['key'=>'c','label'=>'保持自由和選擇的空間','scores'=>['FREE_SPIRIT'=>3],'dim_scores'=>['FREEDOM'=>18,'MEANING'=>8]],
                ['key'=>'d','label'=>'和重要的人維持深刻的連結','scores'=>['CONNECTOR'=>3],'dim_scores'=>['CONNECTION'=>18,'SECURITY'=>8]],
            ]],
            ['body' => '如果人生只能選一種成功，你選哪個？',
             'options' => [
                ['key'=>'a','label'=>'達到讓自己驕傲的成就','scores'=>['ACHIEVER'=>3],'dim_scores'=>['ACHIEVEMENT'=>18,'MEANING'=>8]],
                ['key'=>'b','label'=>'創造了對世界有意義的事','scores'=>['MEANING_SEEKER'=>3],'dim_scores'=>['MEANING'=>18,'CONNECTION'=>8]],
                ['key'=>'c','label'=>'過上完全自由、按自己方式的生活','scores'=>['FREE_SPIRIT'=>3],'dim_scores'=>['FREEDOM'=>18,'MEANING'=>8]],
                ['key'=>'d','label'=>'擁有深厚的愛和關係','scores'=>['CONNECTOR'=>3],'dim_scores'=>['CONNECTION'=>18,'SECURITY'=>8]],
            ]],
            ['body' => '你在什麼時候最清楚地感受到「這就是我要的人生」？',
             'options' => [
                ['key'=>'a','label'=>'突破一個以前做不到的事','scores'=>['ACHIEVER'=>3],'dim_scores'=>['ACHIEVEMENT'=>18,'MEANING'=>8]],
                ['key'=>'b','label'=>'看到自己的行動真的影響了某個人','scores'=>['MEANING_SEEKER'=>3],'dim_scores'=>['MEANING'=>16,'CONNECTION'=>10]],
                ['key'=>'c','label'=>'做了一個完全由自己決定的選擇','scores'=>['FREE_SPIRIT'=>3],'dim_scores'=>['FREEDOM'=>18,'MEANING'=>8]],
                ['key'=>'d','label'=>'感受到和重要的人之間真實的連結','scores'=>['CONNECTOR'=>3],'dim_scores'=>['CONNECTION'=>18,'SECURITY'=>8]],
            ]],
            ['body' => '你最害怕哪種人生？',
             'options' => [
                ['key'=>'a','label'=>'一生平庸，沒有達成任何值得驕傲的事','scores'=>['ACHIEVER'=>3],'dim_scores'=>['ACHIEVEMENT'=>18,'MEANING'=>8]],
                ['key'=>'b','label'=>'一生忙碌但沒有意義','scores'=>['MEANING_SEEKER'=>3],'dim_scores'=>['MEANING'=>18,'CONNECTION'=>8]],
                ['key'=>'c','label'=>'一生被框架限制，無法做自己','scores'=>['FREE_SPIRIT'=>3],'dim_scores'=>['FREEDOM'=>18,'MEANING'=>8]],
                ['key'=>'d','label'=>'一生孤獨，沒有真正的深刻關係','scores'=>['CONNECTOR'=>3],'dim_scores'=>['CONNECTION'=>18,'SECURITY'=>8]],
            ]],
            ['body' => '你如何判斷一個選擇值不值得？',
             'options' => [
                ['key'=>'a','label'=>'看它是否讓我更靠近目標','scores'=>['ACHIEVER'=>3],'dim_scores'=>['ACHIEVEMENT'=>16,'MEANING'=>10]],
                ['key'=>'b','label'=>'看它是否有更大的意義和貢獻','scores'=>['MEANING_SEEKER'=>3],'dim_scores'=>['MEANING'=>18,'CONNECTION'=>8]],
                ['key'=>'c','label'=>'看它是否讓我保有自主權','scores'=>['FREE_SPIRIT'=>3],'dim_scores'=>['FREEDOM'=>18,'MEANING'=>8]],
                ['key'=>'d','label'=>'看它是否讓重要的關係更深','scores'=>['CONNECTOR'=>3],'dim_scores'=>['CONNECTION'=>16,'SECURITY'=>10]],
            ]],
            ['body' => '你在人生低谷時最能讓你站起來的是？',
             'options' => [
                ['key'=>'a','label'=>'重新設定一個新的目標，找到前進方向','scores'=>['ACHIEVER'=>3],'dim_scores'=>['ACHIEVEMENT'=>16,'MEANING'=>10]],
                ['key'=>'b','label'=>'想到自己還有未完成的使命','scores'=>['MEANING_SEEKER'=>3],'dim_scores'=>['MEANING'=>18,'CONNECTION'=>8]],
                ['key'=>'c','label'=>'放下當前的事，重新找到自由感','scores'=>['FREE_SPIRIT'=>3],'dim_scores'=>['FREEDOM'=>18,'MEANING'=>8]],
                ['key'=>'d','label'=>'想到身邊需要我的人','scores'=>['CONNECTOR'=>3],'dim_scores'=>['CONNECTION'=>16,'SECURITY'=>10]],
            ]],
            ['body' => '你在規劃未來時，最在意的是？',
             'options' => [
                ['key'=>'a','label'=>'五年後我站在哪裡，達到了什麼','scores'=>['ACHIEVER'=>3],'dim_scores'=>['ACHIEVEMENT'=>18,'MEANING'=>8]],
                ['key'=>'b','label'=>'我的存在有沒有讓世界更好一點點','scores'=>['MEANING_SEEKER'=>3],'dim_scores'=>['MEANING'=>18,'CONNECTION'=>8]],
                ['key'=>'c','label'=>'我是否還有足夠的空間做我想做的事','scores'=>['FREE_SPIRIT'=>3],'dim_scores'=>['FREEDOM'=>18,'MEANING'=>8]],
                ['key'=>'d','label'=>'身邊重要的人是否也在成長','scores'=>['CONNECTOR'=>3],'dim_scores'=>['CONNECTION'=>16,'SECURITY'=>10]],
            ]],
            ['body' => '你對「安穩」的態度是？',
             'options' => [
                ['key'=>'a','label'=>'安穩是一種我不太追求的狀態','scores'=>['ACHIEVER'=>2,'FREE_SPIRIT'=>2],'dim_scores'=>['ACHIEVEMENT'=>12,'FREEDOM'=>14]],
                ['key'=>'b','label'=>'安穩讓我有更多空間去幫助他人','scores'=>['MEANING_SEEKER'=>3],'dim_scores'=>['MEANING'=>16,'CONNECTION'=>10]],
                ['key'=>'c','label'=>'安穩讓我失去探索的動力','scores'=>['FREE_SPIRIT'=>3],'dim_scores'=>['FREEDOM'=>18,'MEANING'=>8]],
                ['key'=>'d','label'=>'安穩讓關係更長久更深刻','scores'=>['CONNECTOR'=>2,'SECURITY_SEEKER'=>2],'dim_scores'=>['CONNECTION'=>12,'SECURITY'=>14]],
            ]],
            ['body' => '你理想中的工作環境是？',
             'options' => [
                ['key'=>'a','label'=>'有清晰的目標和晉升空間','scores'=>['ACHIEVER'=>3],'dim_scores'=>['ACHIEVEMENT'=>18,'MEANING'=>8]],
                ['key'=>'b','label'=>'做的事對社會有正面影響','scores'=>['MEANING_SEEKER'=>3],'dim_scores'=>['MEANING'=>18,'CONNECTION'=>8]],
                ['key'=>'c','label'=>'自主靈活，不被過多規定限制','scores'=>['FREE_SPIRIT'=>3],'dim_scores'=>['FREEDOM'=>18,'MEANING'=>8]],
                ['key'=>'d','label'=>'有真實的團隊感和彼此支持','scores'=>['CONNECTOR'=>3],'dim_scores'=>['CONNECTION'=>18,'SECURITY'=>8]],
            ]],
            ['body' => '你在人生中最不願意妥協的是？',
             'options' => [
                ['key'=>'a','label'=>'我的目標和追求成就的空間','scores'=>['ACHIEVER'=>3],'dim_scores'=>['ACHIEVEMENT'=>18,'MEANING'=>8]],
                ['key'=>'b','label'=>'我相信自己在做有意義事情的感受','scores'=>['MEANING_SEEKER'=>3],'dim_scores'=>['MEANING'=>18,'CONNECTION'=>8]],
                ['key'=>'c','label'=>'我的自主權和自由','scores'=>['FREE_SPIRIT'=>3],'dim_scores'=>['FREEDOM'=>18,'MEANING'=>8]],
                ['key'=>'d','label'=>'我和重要的人的深刻連結','scores'=>['CONNECTOR'=>3],'dim_scores'=>['CONNECTION'=>18,'SECURITY'=>8]],
            ]],
        ]);
    }

    // =========================================================================
    // 情緒壓力管理風格
    // =========================================================================
    private function fixEmotionalStress(): void
    {
        $quiz = Quiz::where('slug', 'emotional-stress')->with('questions')->first();
        if (!$quiz) return;
        $this->command->info("修正：情緒壓力管理風格");
        $this->deleteRepeatedQuestions($quiz);

        $this->addQuestions($quiz, [
            ['body' => '壓力累積到一定程度，你最常出現的反應是？',
             'options' => [
                ['key'=>'a','label'=>'什麼都沒說，繼續做事','scores'=>['SUPPRESSOR'=>3],'dim_scores'=>['CONTROL'=>18,'DEPTH'=>8]],
                ['key'=>'b','label'=>'需要找人說說，把情緒說出來','scores'=>['EXPRESSER'=>3],'dim_scores'=>['EXPRESSION'=>16,'DEPTH'=>10]],
                ['key'=>'c','label'=>'開始分析是哪裡出了問題','scores'=>['ANALYZER_E'=>3],'dim_scores'=>['ANALYSIS'=>16,'CONTROL'=>10]],
                ['key'=>'d','label'=>'去做別的事讓自己忘記壓力','scores'=>['AVOIDER'=>3],'dim_scores'=>['AVOIDANCE'=>16,'CONTROL'=>10]],
            ]],
            ['body' => '當你感到沮喪時，你最先想做的事是？',
             'options' => [
                ['key'=>'a','label'=>'一個人安靜待著，等情緒過去','scores'=>['SUPPRESSOR'=>3],'dim_scores'=>['CONTROL'=>16,'DEPTH'=>10]],
                ['key'=>'b','label'=>'打電話給朋友，說說這件事','scores'=>['EXPRESSER'=>3],'dim_scores'=>['EXPRESSION'=>18,'DEPTH'=>8]],
                ['key'=>'c','label'=>'想清楚為什麼會沮喪','scores'=>['ANALYZER_E'=>3],'dim_scores'=>['ANALYSIS'=>18,'CONTROL'=>8]],
                ['key'=>'d','label'=>'去運動或做某件讓身體動起來的事','scores'=>['GROUNDED'=>3],'dim_scores'=>['GROUNDING'=>18,'CONTROL'=>8]],
            ]],
            ['body' => '在高壓情況下，你最難做到的事是？',
             'options' => [
                ['key'=>'a','label'=>'讓別人看到我真實的情緒狀態','scores'=>['SUPPRESSOR'=>3],'dim_scores'=>['CONTROL'=>18,'DEPTH'=>8]],
                ['key'=>'b','label'=>'不把情緒說出來，一個人消化','scores'=>['EXPRESSER'=>3],'dim_scores'=>['EXPRESSION'=>16,'DEPTH'=>10]],
                ['key'=>'c','label'=>'不去分析，直接感受情緒','scores'=>['ANALYZER_E'=>3],'dim_scores'=>['ANALYSIS'=>16,'CONTROL'=>10]],
                ['key'=>'d','label'=>'停下來，讓自己完全感受壓力','scores'=>['AVOIDER'=>3],'dim_scores'=>['AVOIDANCE'=>16,'CONTROL'=>10]],
            ]],
            ['body' => '你面對衝突或爭執時，通常怎麼做？',
             'options' => [
                ['key'=>'a','label'=>'忍住，表面保持平靜','scores'=>['SUPPRESSOR'=>3],'dim_scores'=>['CONTROL'=>18,'DEPTH'=>8]],
                ['key'=>'b','label'=>'直接說出我的感受和不滿','scores'=>['EXPRESSER'=>3],'dim_scores'=>['EXPRESSION'=>18,'DEPTH'=>8]],
                ['key'=>'c','label'=>'先冷靜，再理性分析狀況','scores'=>['ANALYZER_E'=>3],'dim_scores'=>['ANALYSIS'=>18,'CONTROL'=>8]],
                ['key'=>'d','label'=>'先離開現場，之後再處理','scores'=>['AVOIDER'=>3],'dim_scores'=>['AVOIDANCE'=>16,'CONTROL'=>10]],
            ]],
            ['body' => '你在情緒崩潰後，恢復的方式通常是？',
             'options' => [
                ['key'=>'a','label'=>'睡一覺，明天繼續','scores'=>['RESILIENT'=>3],'dim_scores'=>['RESILIENCE'=>18,'CONTROL'=>8]],
                ['key'=>'b','label'=>'徹底發洩，哭完就好了','scores'=>['EXPRESSER'=>3],'dim_scores'=>['EXPRESSION'=>18,'DEPTH'=>8]],
                ['key'=>'c','label'=>'找出是什麼讓我崩潰，避免再次發生','scores'=>['ANALYZER_E'=>3],'dim_scores'=>['ANALYSIS'=>18,'CONTROL'=>8]],
                ['key'=>'d','label'=>'去大自然或做讓身體感到舒服的事','scores'=>['GROUNDED'=>3],'dim_scores'=>['GROUNDING'=>18,'CONTROL'=>8]],
            ]],
            ['body' => '有人問「你還好嗎？」，你最常的回答是？',
             'options' => [
                ['key'=>'a','label'=>'「還好，沒事」（其實可能不太好）','scores'=>['SUPPRESSOR'=>3],'dim_scores'=>['CONTROL'=>18,'DEPTH'=>8]],
                ['key'=>'b','label'=>'直接說出目前的狀況和感受','scores'=>['EXPRESSER'=>3],'dim_scores'=>['EXPRESSION'=>16,'DEPTH'=>10]],
                ['key'=>'c','label'=>'「還在處理中」（然後分析給對方聽）','scores'=>['ANALYZER_E'=>3],'dim_scores'=>['ANALYSIS'=>16,'CONTROL'=>10]],
                ['key'=>'d','label'=>'「有點累，但不想說太多」','scores'=>['AVOIDER'=>2,'SUPPRESSOR'=>2],'dim_scores'=>['AVOIDANCE'=>12,'CONTROL'=>14]],
            ]],
            ['body' => '你認為最健康的情緒管理方式是？',
             'options' => [
                ['key'=>'a','label'=>'保持理性，不讓情緒影響行動','scores'=>['SUPPRESSOR'=>2,'ANALYZER_E'=>2],'dim_scores'=>['CONTROL'=>14,'ANALYSIS'=>12]],
                ['key'=>'b','label'=>'讓情緒有出口，說出來或寫出來','scores'=>['EXPRESSER'=>3],'dim_scores'=>['EXPRESSION'=>18,'DEPTH'=>8]],
                ['key'=>'c','label'=>'了解情緒的來源，從根本解決','scores'=>['ANALYZER_E'=>3],'dim_scores'=>['ANALYSIS'=>18,'CONTROL'=>8]],
                ['key'=>'d','label'=>'回到身體感受，用呼吸和移動調節','scores'=>['GROUNDED'=>3],'dim_scores'=>['GROUNDING'=>18,'CONTROL'=>8]],
            ]],
            ['body' => '你最不舒服的社交情境是？',
             'options' => [
                ['key'=>'a','label'=>'被要求在眾人面前分享脆弱','scores'=>['SUPPRESSOR'=>3],'dim_scores'=>['CONTROL'=>18,'DEPTH'=>8]],
                ['key'=>'b','label'=>'被要求壓抑情緒，假裝沒事','scores'=>['EXPRESSER'=>3],'dim_scores'=>['EXPRESSION'=>18,'DEPTH'=>8]],
                ['key'=>'c','label'=>'情緒化的衝突，沒辦法理性討論','scores'=>['ANALYZER_E'=>3],'dim_scores'=>['ANALYSIS'=>18,'CONTROL'=>8]],
                ['key'=>'d','label'=>'直接面對讓我焦慮的問題','scores'=>['AVOIDER'=>3],'dim_scores'=>['AVOIDANCE'=>16,'CONTROL'=>10]],
            ]],
            ['body' => '你在什麼情況下最能感到情緒被釋放？',
             'options' => [
                ['key'=>'a','label'=>'完成了一件困難的事，讓壓力自然消散','scores'=>['RESILIENT'=>3],'dim_scores'=>['RESILIENCE'=>16,'CONTROL'=>10]],
                ['key'=>'b','label'=>'和人說出所有想說的話','scores'=>['EXPRESSER'=>3],'dim_scores'=>['EXPRESSION'=>18,'DEPTH'=>8]],
                ['key'=>'c','label'=>'終於搞清楚情緒的根源','scores'=>['ANALYZER_E'=>3],'dim_scores'=>['ANALYSIS'=>18,'CONTROL'=>8]],
                ['key'=>'d','label'=>'做了一個讓身體感到輕盈的活動','scores'=>['GROUNDED'=>3],'dim_scores'=>['GROUNDING'=>18,'CONTROL'=>8]],
            ]],
            ['body' => '別人說你在壓力下最讓他們意外的是？',
             'options' => [
                ['key'=>'a','label'=>'你看起來一點事都沒有，但其實壓力很大','scores'=>['SUPPRESSOR'=>3],'dim_scores'=>['CONTROL'=>18,'DEPTH'=>8]],
                ['key'=>'b','label'=>'你會把壓力說出來，不藏在心裡','scores'=>['EXPRESSER'=>3],'dim_scores'=>['EXPRESSION'=>16,'DEPTH'=>10]],
                ['key'=>'c','label'=>'你在壓力下反而更冷靜更有分析力','scores'=>['ANALYZER_E'=>3],'dim_scores'=>['ANALYSIS'=>18,'CONTROL'=>8]],
                ['key'=>'d','label'=>'你很快就可以恢復，好像忘掉了','scores'=>['RESILIENT'=>2,'AVOIDER'=>2],'dim_scores'=>['RESILIENCE'=>12,'AVOIDANCE'=>14]],
            ]],
        ]);
    }
}
