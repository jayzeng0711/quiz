<?php

namespace Database\Seeders;

use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

/**
 * Fixes all questions with repeated option sets by assigning unique,
 * contextually relevant options to each question.
 */
class FixAllRepeatedOptionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->fixBehaviorPattern();
        $this->fixFirstImpression();
        $this->fixLifeDirection();
        $this->fixCharisma();
        $this->command->info('✅ All repeated options fixed');
    }

    // ─── helper ─────────────────────────────────────────────────────────────
    private function o(string $k, string $l, array $s, array $d = []): array
    {
        $opt = ['key' => $k, 'label' => $l, 'scores' => $s];
        if ($d) $opt['dim_scores'] = $d;
        return $opt;
    }

    private function set(int $id, array $options): void
    {
        $q = QuizQuestion::find($id);
        if (! $q) return;
        $q->update(['options' => $options]);
    }

    // ════════════════════════════════════════════════════════════════════════
    // 行為模式
    // Types: SPRINTER, PLANNER, INNOVATOR, COLLABORATOR, PERFECTIONIST, ADAPTOR, ANALYZER, RISK_TAKER
    // ════════════════════════════════════════════════════════════════════════
    private function fixBehaviorPattern(): void
    {
        $data = [
            191 => [ // 你更像哪種決策者？
                $this->o('a','直覺決策者——感覺對就走，邊做邊修正',['SPRINTER'=>3,'RISK_TAKER'=>1],['SPEED'=>16,'RISK'=>12]),
                $this->o('b','系統決策者——收集資訊，分析完再決定',['ANALYZER'=>3,'PLANNER'=>1],['PLANNING'=>16,'ATTENTION'=>12]),
                $this->o('c','共識決策者——先聽所有人意見再拍板',['COLLABORATOR'=>3],['COOPERATION'=>16,'SPEED'=>10]),
                $this->o('d','完美決策者——要找到最優解才能行動',['PERFECTIONIST'=>3],['ATTENTION'=>18,'PLANNING'=>10]),
            ],
            192 => [ // 你在壓力下最先失去的是？
                $this->o('a','耐心——我變得急躁，什麼都想快點結束',['SPRINTER'=>3],['SPEED'=>16,'RISK'=>10]),
                $this->o('b','彈性——我更執著於計畫，拒絕任何改變',['PLANNER'=>3],['PLANNING'=>16,'ATTENTION'=>10]),
                $this->o('c','獨立性——我比平時更需要別人的支持和意見',['COLLABORATOR'=>3],['COOPERATION'=>16,'SPEED'=>8]),
                $this->o('d','對「夠好」的接受——標準反而變更高',['PERFECTIONIST'=>3],['ATTENTION'=>18,'PLANNING'=>8]),
            ],
            193 => [ // 你完成一件事後，通常的感受是？
                $this->o('a','立刻想到下一件要做的事，停不下來',['SPRINTER'=>3],['SPEED'=>16,'MOMENTUM'=>12]),
                $this->o('b','仔細回顧有沒有地方可以做得更好',['PERFECTIONIST'=>2,'ANALYZER'=>2],['ATTENTION'=>14,'PLANNING'=>12]),
                $this->o('c','想和完成這件事的夥伴一起慶祝',['COLLABORATOR'=>3],['COOPERATION'=>16,'SPEED'=>8]),
                $this->o('d','滿足地看著成果，然後好好休息',['ADAPTOR'=>2,'PLANNER'=>2],['PLANNING'=>12,'ATTENTION'=>12]),
            ],
            194 => [ // 你如何對待「差不多夠好」？
                $this->o('a','完全接受——完成比完美重要',['SPRINTER'=>3,'ADAPTOR'=>1],['SPEED'=>16,'FLEXIBILITY'=>12]),
                $this->o('b','取決於重要性——重要的事絕對不將就',['PLANNER'=>2,'PERFECTIONIST'=>2],['PLANNING'=>12,'ATTENTION'=>14]),
                $this->o('c','不太舒服——我的標準很難降下來',['PERFECTIONIST'=>3],['ATTENTION'=>18,'PLANNING'=>8]),
                $this->o('d','我會先交出去，再繼續優化',['INNOVATOR'=>2,'ADAPTOR'=>2],['FLEXIBILITY'=>14,'SPEED'=>12]),
            ],
            195 => [ // 你在哪種情況下效率最低？
                $this->o('a','當任務沒有明確的截止日，感覺不急迫',['SPRINTER'=>3],['SPEED'=>14,'RISK'=>12]),
                $this->o('b','當沒有清晰的目標和流程，一切模糊',['PLANNER'=>3,'ANALYZER'=>1],['PLANNING'=>16,'ATTENTION'=>10]),
                $this->o('c','當我必須獨自完成，沒有人可以討論',['COLLABORATOR'=>3],['COOPERATION'=>16,'SPEED'=>8]),
                $this->o('d','當環境一直被打斷，無法專注進入狀態',['ANALYZER'=>2,'PERFECTIONIST'=>2],['ATTENTION'=>14,'PLANNING'=>12]),
            ],
            196 => [ // 你最容易哪種任務拖延？
                $this->o('a','重複性的事——沒有挑戰感，我提不起勁',['INNOVATOR'=>2,'RISK_TAKER'=>2],['INNOVATION'=>14,'SPEED'=>12]),
                $this->o('b','需要大量前置準備的事——太複雜，不知從何開始',['SPRINTER'=>3],['SPEED'=>14,'PLANNING'=>10]),
                $this->o('c','獨立完成的大型計畫——沒有隊友讓我缺乏動力',['COLLABORATOR'=>3],['COOPERATION'=>16,'SPEED'=>8]),
                $this->o('d','感覺做不到完美的事——我怕做出來不夠好',['PERFECTIONIST'=>3],['ATTENTION'=>18,'SPEED'=>8]),
            ],
            197 => [ // 你認為「做事的品質」和「做事的速度」哪個更重要？
                $this->o('a','速度絕對優先——可以之後修，先完成先贏',['SPRINTER'=>3],['SPEED'=>18,'RISK'=>8]),
                $this->o('b','品質優先——做慢一點，但做好',['PERFECTIONIST'=>2,'PLANNER'=>2],['ATTENTION'=>14,'PLANNING'=>12]),
                $this->o('c','看情況——高風險高要求時重品質，急事重速度',['ADAPTOR'=>3],['FLEXIBILITY'=>16,'SPEED'=>10]),
                $this->o('d','找到最有效率的方法——讓兩者都最大化',['INNOVATOR'=>2,'ANALYZER'=>2],['INNOVATION'=>14,'ATTENTION'=>12]),
            ],
            198 => [ // 你在一個新環境中會先做什麼？
                $this->o('a','觀察規則和潛規則，了解這裡怎麼運作',['ANALYZER'=>3,'PLANNER'=>1],['PLANNING'=>14,'ATTENTION'=>14]),
                $this->o('b','找到關鍵的人，建立關係網絡',['COLLABORATOR'=>3],['COOPERATION'=>16,'SPEED'=>10]),
                $this->o('c','快速找到我可以貢獻的地方，直接開始',['SPRINTER'=>2,'INNOVATOR'=>2],['SPEED'=>14,'INNOVATION'=>12]),
                $this->o('d','先適應氣氛，觀察幾天再行動',['ADAPTOR'=>3],['FLEXIBILITY'=>16,'SPEED'=>8]),
            ],
            199 => [ // 你如何激勵自己做不喜歡的事？
                $this->o('a','設一個截止時間，逼自己在限制裡完成',['SPRINTER'=>3],['SPEED'=>16,'EXECUTION'=>10]),
                $this->o('b','把它分解成小步驟，一步一步執行',['PLANNER'=>2,'ANALYZER'=>2],['PLANNING'=>14,'ATTENTION'=>12]),
                $this->o('c','找人一起做，有伴比較有動力',['COLLABORATOR'=>3],['COOPERATION'=>16,'SPEED'=>8]),
                $this->o('d','找出做這件事的意義或好處，說服自己',['ANALYZER'=>2,'ADAPTOR'=>2],['PLANNING'=>12,'FLEXIBILITY'=>12]),
            ],
            200 => [ // 你對「冒險」的態度是？
                $this->o('a','天然吸引我——不確定感讓我更興奮',['RISK_TAKER'=>3,'SPRINTER'=>1],['RISK'=>18,'SPEED'=>10]),
                $this->o('b','謹慎評估後才冒——有根據的冒險才值得',['ANALYZER'=>2,'PLANNER'=>2],['PLANNING'=>14,'RISK'=>10]),
                $this->o('c','集體決定——大家覺得值得我才加入',['COLLABORATOR'=>3],['COOPERATION'=>14,'RISK'=>10]),
                $this->o('d','我傾向避開不確定性，風險讓我不舒服',['PERFECTIONIST'=>2,'PLANNER'=>2],['ATTENTION'=>12,'PLANNING'=>14]),
            ],
            201 => [ // 你在一個決策沒有明確答案時，你通常怎麼做？
                $this->o('a','直覺——感覺哪個對就選哪個',['SPRINTER'=>2,'RISK_TAKER'=>2],['SPEED'=>14,'RISK'=>12]),
                $this->o('b','繼續蒐集資訊，直到有更清楚的依據',['ANALYZER'=>3,'PLANNER'=>1],['PLANNING'=>14,'ATTENTION'=>14]),
                $this->o('c','諮詢信任的人，集思廣益後決定',['COLLABORATOR'=>3],['COOPERATION'=>16,'PLANNING'=>8]),
                $this->o('d','先列出每個選項的優缺點，再決定',['PLANNER'=>2,'PERFECTIONIST'=>2],['PLANNING'=>14,'ATTENTION'=>12]),
            ],
            202 => [ // 你面對一個很大的目標，你的策略是？
                $this->o('a','先動起來，邊做邊找方向',['SPRINTER'=>3],['SPEED'=>16,'RISK'=>10]),
                $this->o('b','拆解成清晰的里程碑，按計畫推進',['PLANNER'=>3,'ANALYZER'=>1],['PLANNING'=>16,'ATTENTION'=>10]),
                $this->o('c','找到合適的夥伴，分工合作',['COLLABORATOR'=>3],['COOPERATION'=>16,'SPEED'=>8]),
                $this->o('d','先做市場調查或小範圍測試，再全力投入',['ANALYZER'=>2,'INNOVATOR'=>2],['PLANNING'=>12,'INNOVATION'=>14]),
            ],
            203 => [ // 你在工作中最討厭的干擾是？
                $this->o('a','不停被問問題，讓我無法集中',['ANALYZER'=>2,'PERFECTIONIST'=>2],['ATTENTION'=>14,'PLANNING'=>12]),
                $this->o('b','臨時插入的任務，打亂原本的計畫',['PLANNER'=>3],['PLANNING'=>18,'ATTENTION'=>8]),
                $this->o('c','一個人悶頭做，缺少互動和交流',['COLLABORATOR'=>3],['COOPERATION'=>16,'SPEED'=>8]),
                $this->o('d','重複做同樣的事，沒有任何變化',['INNOVATOR'=>2,'RISK_TAKER'=>2],['INNOVATION'=>14,'SPEED'=>12]),
            ],
            204 => [ // 你如何評估一件事是否值得你的時間？
                $this->o('a','看結果的影響力——能帶來多大的改變',['RISK_TAKER'=>2,'INNOVATOR'=>2],['RISK'=>12,'INNOVATION'=>14]),
                $this->o('b','看它是否符合我整體的計畫和優先順序',['PLANNER'=>3],['PLANNING'=>18,'ATTENTION'=>8]),
                $this->o('c','看對我重要的人是否受益',['COLLABORATOR'=>2,'ADAPTOR'=>2],['COOPERATION'=>14,'FLEXIBILITY'=>12]),
                $this->o('d','直覺——對的事情自然值得',['SPRINTER'=>2,'ADAPTOR'=>2],['SPEED'=>12,'FLEXIBILITY'=>14]),
            ],
            205 => [ // 你在哪種情況下最容易犯錯？
                $this->o('a','趕時間的時候——我跳過了應該確認的步驟',['SPRINTER'=>3],['SPEED'=>14,'EXECUTION'=>12]),
                $this->o('b','事情太多的時候——注意力被分散',['PLANNER'=>2,'PERFECTIONIST'=>2],['PLANNING'=>12,'ATTENTION'=>14]),
                $this->o('c','單獨行動的時候——沒有人幫我把關',['COLLABORATOR'=>3],['COOPERATION'=>14,'ATTENTION'=>10]),
                $this->o('d','嘗試新方法的時候——還沒掌握節奏',['INNOVATOR'=>2,'ADAPTOR'=>2],['INNOVATION'=>12,'FLEXIBILITY'=>14]),
            ],
            206 => [ // 你完成一個長期計畫後，最先想到的是？
                $this->o('a','下一個計畫是什麼？我已經開始想了',['SPRINTER'=>3,'INNOVATOR'=>1],['SPEED'=>14,'INNOVATION'=>12]),
                $this->o('b','仔細回顧整個過程，記錄學到了什麼',['PLANNER'=>2,'ANALYZER'=>2],['PLANNING'=>12,'REFLECTION'=>14]),
                $this->o('c','和一起完成的人好好慶祝',['COLLABORATOR'=>3],['COOPERATION'=>16,'SPEED'=>8]),
                $this->o('d','讓自己充分休息，再考慮接下來怎麼做',['ADAPTOR'=>3],['FLEXIBILITY'=>14,'PLANNING'=>10]),
            ],
            207 => [ // 你對「反覆確認」的態度是？
                $this->o('a','多此一舉——確認一次就夠了，直接做',['SPRINTER'=>3],['SPEED'=>16,'EXECUTION'=>10]),
                $this->o('b','必要的——沒有確認的行動是不負責任的',['PERFECTIONIST'=>2,'PLANNER'=>2],['ATTENTION'=>14,'PLANNING'=>12]),
                $this->o('c','值得的——讓所有人對齊，避免後來的誤解',['COLLABORATOR'=>3],['COOPERATION'=>14,'ATTENTION'=>10]),
                $this->o('d','看情況——複雜的事需要，簡單的事不需要',['ADAPTOR'=>2,'ANALYZER'=>2],['FLEXIBILITY'=>12,'PLANNING'=>14]),
            ],
            208 => [ // 你如何對待別人給你的建議？
                $this->o('a','先接收，但最終還是相信自己的判斷',['SPRINTER'=>2,'RISK_TAKER'=>2],['SPEED'=>12,'RISK'=>14]),
                $this->o('b','認真評估——好的建議我會記下來改進',['ANALYZER'=>3,'PERFECTIONIST'=>1],['ATTENTION'=>16,'PLANNING'=>10]),
                $this->o('c','感謝並認真考慮，別人的視角很寶貴',['COLLABORATOR'=>3],['COOPERATION'=>16,'REFLECTION'=>8]),
                $this->o('d','區分建議的來源和品質，選擇性採用',['PLANNER'=>2,'ADAPTOR'=>2],['PLANNING'=>12,'FLEXIBILITY'=>14]),
            ],
            209 => [ // 你最常用什麼方式解決卡住的問題？
                $this->o('a','暫時放下，去做別的事，靈感自然來',['ADAPTOR'=>2,'INNOVATOR'=>2],['FLEXIBILITY'=>14,'INNOVATION'=>12]),
                $this->o('b','系統性分析，把問題拆解到最小單位',['ANALYZER'=>3,'PLANNER'=>1],['PLANNING'=>14,'ATTENTION'=>14]),
                $this->o('c','找人討論，外力往往能打開思路',['COLLABORATOR'=>3],['COOPERATION'=>16,'SPEED'=>8]),
                $this->o('d','強迫自己在限定時間內提出任何解法',['SPRINTER'=>3],['SPEED'=>14,'RISK'=>12]),
            ],
            210 => [ // 你在一個項目的哪個階段最有活力？
                $this->o('a','啟動階段——一切都是可能性，最讓人興奮',['SPRINTER'=>2,'INNOVATOR'=>2],['SPEED'=>14,'INNOVATION'=>12]),
                $this->o('b','規劃階段——把混亂變成清晰的藍圖',['PLANNER'=>3],['PLANNING'=>18,'ATTENTION'=>8]),
                $this->o('c','協作階段——和不同人一起創造',['COLLABORATOR'=>3],['COOPERATION'=>16,'SPEED'=>8]),
                $this->o('d','收尾階段——把每個細節做到完美',['PERFECTIONIST'=>2,'ANALYZER'=>2],['ATTENTION'=>16,'PLANNING'=>10]),
            ],
            211 => [ // 你如何平衡「追求完美」和「截止日期」？
                $this->o('a','截止日優先——先交出去，再說品質',['SPRINTER'=>3],['SPEED'=>16,'EXECUTION'=>10]),
                $this->o('b','我設自己的截止日，比要求的早，留時間修',['PLANNER'=>2,'PERFECTIONIST'=>2],['PLANNING'=>14,'ATTENTION'=>12]),
                $this->o('c','跟團隊確認哪些部分最關鍵，集中力氣在那裡',['COLLABORATOR'=>2,'ANALYZER'=>2],['COOPERATION'=>12,'ATTENTION'=>14]),
                $this->o('d','根據情況調整標準——不是每件事都需要完美',['ADAPTOR'=>3],['FLEXIBILITY'=>16,'PLANNING'=>8]),
            ],
            212 => [ // 你在協作中最不喜歡的是？
                $this->o('a','等待其他人——速度不一致讓我很煩',['SPRINTER'=>3],['SPEED'=>16,'EXECUTION'=>10]),
                $this->o('b','方向一直在變，計畫反覆被推翻',['PLANNER'=>3],['PLANNING'=>18,'ATTENTION'=>8]),
                $this->o('c','有人只說不做，不貢獻只批評',['COLLABORATOR'=>3],['COOPERATION'=>14,'EXECUTION'=>10]),
                $this->o('d','最後結果的品質不如預期，沒有達到我的標準',['PERFECTIONIST'=>3],['ATTENTION'=>18,'EXECUTION'=>8]),
            ],
            213 => [ // 你面對一個看起來很複雜的問題，你的第一反應是？
                $this->o('a','興奮——複雜代表挑戰，我喜歡這種感覺',['RISK_TAKER'=>2,'INNOVATOR'=>2],['RISK'=>14,'INNOVATION'=>14]),
                $this->o('b','先拆解——把複雜問題切成小問題',['ANALYZER'=>3,'PLANNER'=>1],['PLANNING'=>14,'ATTENTION'=>14]),
                $this->o('c','找人——這種問題需要不同的腦袋一起思考',['COLLABORATOR'=>3],['COOPERATION'=>16,'SPEED'=>8]),
                $this->o('d','先做一個最小可行的嘗試，看看反應再調整',['ADAPTOR'=>2,'SPRINTER'=>2],['FLEXIBILITY'=>14,'SPEED'=>12]),
            ],
            214 => [ // 你如何看待「反覆修改」？
                $this->o('a','很好——每次修改都讓結果更好',['PERFECTIONIST'=>3],['ATTENTION'=>18,'EXECUTION'=>8]),
                $this->o('b','必要時做，但要有明確目的',['ANALYZER'=>2,'PLANNER'=>2],['PLANNING'=>12,'ATTENTION'=>14]),
                $this->o('c','根據使用者或夥伴的反饋來修——不自己閉門改',['COLLABORATOR'=>3],['COOPERATION'=>14,'ATTENTION'=>10]),
                $this->o('d','到一個程度就放手——改到什麼時候才算完？',['SPRINTER'=>2,'ADAPTOR'=>2],['SPEED'=>12,'FLEXIBILITY'=>14]),
            ],
            215 => [ // 你覺得最高效的學習方式是？
                $this->o('a','直接嘗試——做錯了再修，比讀說明書快',['SPRINTER'=>3,'RISK_TAKER'=>1],['SPEED'=>16,'RISK'=>10]),
                $this->o('b','系統性學習——先建立完整架構，再深入細節',['PLANNER'=>2,'ANALYZER'=>2],['PLANNING'=>14,'ATTENTION'=>12]),
                $this->o('c','向有經驗的人學——和人互動中吸收最快',['COLLABORATOR'=>3],['COOPERATION'=>14,'SPEED'=>10]),
                $this->o('d','大量閱讀和研究，自己思考消化',['ANALYZER'=>2,'PERFECTIONIST'=>2],['ATTENTION'=>14,'PLANNING'=>12]),
            ],
            216 => [ // 你如何處理一個做到一半發現方向錯了的任務？
                $this->o('a','立刻停下，重新評估，不浪費更多時間',['SPRINTER'=>2,'ANALYZER'=>2],['SPEED'=>14,'PLANNING'=>12]),
                $this->o('b','先完成這個版本，再做修正版',['PLANNER'=>2,'PERFECTIONIST'=>2],['PLANNING'=>12,'EXECUTION'=>14]),
                $this->o('c','找夥伴討論，確認方向後再繼續',['COLLABORATOR'=>3],['COOPERATION'=>16,'PLANNING'=>8]),
                $this->o('d','把已經做好的部分保留，調整剩下的',['ADAPTOR'=>3,'INNOVATOR'=>1],['FLEXIBILITY'=>16,'INNOVATION'=>10]),
            ],
            217 => [ // 你在哪種工作中感到最有成就感？
                $this->o('a','快速解決了一個別人都在頭疼的問題',['SPRINTER'=>2,'RISK_TAKER'=>2],['SPEED'=>14,'INNOVATION'=>12]),
                $this->o('b','一個複雜的計畫按時按質完成',['PLANNER'=>2,'PERFECTIONIST'=>2],['PLANNING'=>14,'ATTENTION'=>12]),
                $this->o('c','帶著一個團隊一起做出了好成果',['COLLABORATOR'=>3],['COOPERATION'=>18,'EXECUTION'=>8]),
                $this->o('d','找到了一個更好的方式，讓事情變得更有效率',['INNOVATOR'=>3],['INNOVATION'=>18,'SPEED'=>8]),
            ],
            218 => [ // 你對「授權」的態度是？
                $this->o('a','沒問題——交給別人，我可以去做下一件事',['SPRINTER'=>3],['SPEED'=>14,'COOPERATION'=>10]),
                $this->o('b','謹慎——我需要確認對方有能力承接',['PLANNER'=>2,'PERFECTIONIST'=>2],['PLANNING'=>12,'ATTENTION'=>14]),
                $this->o('c','我喜歡——讓每個人貢獻他們最好的部分',['COLLABORATOR'=>3],['COOPERATION'=>16,'EXECUTION'=>8]),
                $this->o('d','困難——我很難相信別人做得和我一樣好',['PERFECTIONIST'=>3],['ATTENTION'=>18,'EXECUTION'=>8]),
            ],
            219 => [ // 你如何對待一個你不認同的決定？
                $this->o('a','說出來，但說完就行動——我不拖延',['SPRINTER'=>2,'RISK_TAKER'=>2],['SPEED'=>12,'EXECUTION'=>14]),
                $this->o('b','寫下我的疑慮，提出替代方案',['ANALYZER'=>2,'PLANNER'=>2],['PLANNING'=>12,'ATTENTION'=>14]),
                $this->o('c','先了解為什麼這樣決定，再表達不同意見',['COLLABORATOR'=>3],['COOPERATION'=>14,'REFLECTION'=>10]),
                $this->o('d','如果影響品質，我一定會堅持反對到底',['PERFECTIONIST'=>3],['ATTENTION'=>16,'EXECUTION'=>10]),
            ],
            220 => [ // 你認為你最大的工作習慣優點是什麼？
                $this->o('a','行動力——我說做就做，不拖延',['SPRINTER'=>3],['SPEED'=>18,'EXECUTION'=>8]),
                $this->o('b','計畫性——我讓複雜的事情有清楚的路徑',['PLANNER'=>3],['PLANNING'=>18,'ATTENTION'=>8]),
                $this->o('c','合作性——我讓團隊比個人更強大',['COLLABORATOR'=>3],['COOPERATION'=>18,'EXECUTION'=>8]),
                $this->o('d','品質意識——我不讓「夠了」成為終點',['PERFECTIONIST'=>3],['ATTENTION'=>18,'EXECUTION'=>8]),
            ],
        ];

        foreach ($data as $id => $options) {
            $this->set($id, $options);
        }
        $this->command->info('  ✅ 行為模式 (30 questions)');
    }

    // ════════════════════════════════════════════════════════════════════════
    // 第一印象
    // Types: MAGNETIC, WARM, CONFIDENT, MYSTERIOUS, FUNNY, RELIABLE, INTELLECTUAL, ENERGETIC
    // ════════════════════════════════════════════════════════════════════════
    private function fixFirstImpression(): void
    {
        $d = fn($w, $c, $m, $h) => ['WARMTH'=>$w,'CONFIDENCE'=>$c,'MYSTERY'=>$m,'HUMOR'=>$h];
        $data = [
            241 => [ // 你第一次見到陌生人，你的開場白通常是？
                $this->o('a','問對方的工作或興趣，讓他先說',['WARM'=>3],['WARMTH'=>16,'RELIABILITY'=>10]),
                $this->o('b','直接說明我是誰、我在做什麼',['CONFIDENT'=>3],['CONFIDENCE'=>16,'RELIABILITY'=>10]),
                $this->o('c','說一句讓氣氛輕鬆的話或笑話',['FUNNY'=>3,'ENERGETIC'=>1],['HUMOR'=>16,'WARMTH'=>10]),
                $this->o('d','微笑點頭，等對方先開口',['MYSTERIOUS'=>3],['MYSTERY'=>14,'RELIABILITY'=>10]),
            ],
            242 => [ // 你在一個聚會中，別人怎麼描述你？
                $this->o('a','暖場的人——讓所有人都感到被包含',['WARM'=>3,'MAGNETIC'=>1],['WARMTH'=>16,'RELIABILITY'=>10]),
                $this->o('b','有趣的人——總是帶來笑聲',['FUNNY'=>3,'ENERGETIC'=>1],['HUMOR'=>16,'WARMTH'=>10]),
                $this->o('c','有深度的人——聊起來讓你思考',['INTELLECTUAL'=>3],['MYSTERY'=>14,'CONFIDENCE'=>10]),
                $this->o('d','神秘的人——你想更了解他',['MYSTERIOUS'=>3],['MYSTERY'=>18,'CONFIDENCE'=>8]),
            ],
            243 => [ // 你的朋友第一次介紹你給陌生人時，最常說你什麼？
                $this->o('a','「他超好相處的，你一定喜歡他」',['WARM'=>3],['WARMTH'=>18,'RELIABILITY'=>8]),
                $this->o('b','「他很有料，你們可以聊聊」',['INTELLECTUAL'=>3],['CONFIDENCE'=>14,'MYSTERY'=>12]),
                $this->o('c','「他很好笑，在他旁邊你會一直笑」',['FUNNY'=>3,'ENERGETIC'=>1],['HUMOR'=>18,'WARMTH'=>8]),
                $this->o('d','「他不太說話，但說話都很有重量」',['MYSTERIOUS'=>2,'RELIABLE'=>2],['MYSTERY'=>14,'RELIABILITY'=>12]),
            ],
            244 => [ // 你留給別人最常見的第一印象是？
                $this->o('a','溫暖，讓人感到放鬆',['WARM'=>3],['WARMTH'=>18,'RELIABILITY'=>8]),
                $this->o('b','有自信，讓人感到可以信賴',['CONFIDENT'=>3],['CONFIDENCE'=>18,'RELIABILITY'=>8]),
                $this->o('c','充滿活力，讓人精神一振',['ENERGETIC'=>3],['HUMOR'=>14,'WARMTH'=>12]),
                $this->o('d','難以看透，讓人想多了解',['MYSTERIOUS'=>3,'MAGNETIC'=>1],['MYSTERY'=>18,'CONFIDENCE'=>8]),
            ],
            245 => [ // 你認為自己在陌生場合的狀態是？
                $this->o('a','主動型——我去找人聊，享受認識新朋友',['ENERGETIC'=>2,'MAGNETIC'=>2],['WARMTH'=>14,'HUMOR'=>12]),
                $this->o('b','選擇性社交——我挑感覺對的人深聊',['INTELLECTUAL'=>2,'MYSTERIOUS'=>2],['MYSTERY'=>14,'CONFIDENCE'=>12]),
                $this->o('c','適應型——可主動可被動，看場合',['WARM'=>2,'RELIABLE'=>2],['WARMTH'=>12,'RELIABILITY'=>14]),
                $this->o('d','觀察型——我先感受環境，再決定怎麼融入',['MYSTERIOUS'=>3],['MYSTERY'=>16,'CONFIDENCE'=>10]),
            ],
            246 => [ // 你第一次和人說話，你最自然的話題是？
                $this->o('a','他的興趣和熱情——我好奇他在意什麼',['WARM'=>3],['WARMTH'=>16,'RELIABILITY'=>10]),
                $this->o('b','當下發生的事或共同的環境',['FUNNY'=>2,'ENERGETIC'=>2],['HUMOR'=>14,'WARMTH'=>12]),
                $this->o('c','一個有趣的問題，讓對話走向更深的地方',['INTELLECTUAL'=>3],['MYSTERY'=>14,'CONFIDENCE'=>12]),
                $this->o('d','工作或來這裡的原因——直接高效',['CONFIDENT'=>3],['CONFIDENCE'=>16,'RELIABILITY'=>10]),
            ],
            247 => [ // 當有人注意到你，你通常的反應是？
                $this->o('a','自然回應，試著建立連結',['WARM'=>2,'MAGNETIC'=>2],['WARMTH'=>14,'RELIABILITY'=>12]),
                $this->o('b','有意識地展現我最好的一面',['CONFIDENT'=>2,'PERFORMER'=>2],['CONFIDENCE'=>14,'WARMTH'=>12]),
                $this->o('c','讓他們更好奇——不馬上說太多',['MYSTERIOUS'=>3],['MYSTERY'=>18,'CONFIDENCE'=>8]),
                $this->o('d','感到自在——我喜歡被注意的感覺',['MAGNETIC'=>2,'ENERGETIC'=>2],['HUMOR'=>12,'CONFIDENCE'=>14]),
            ],
            248 => [ // 你更喜歡讓人覺得你是哪種人？
                $this->o('a','有溫度、讓人感到被接受的人',['WARM'=>3],['WARMTH'=>18,'RELIABILITY'=>8]),
                $this->o('b','有深度、值得花時間了解的人',['INTELLECTUAL'=>2,'MYSTERIOUS'=>2],['MYSTERY'=>14,'CONFIDENCE'=>12]),
                $this->o('c','有趣、讓人在你旁邊自然開心的人',['FUNNY'=>2,'ENERGETIC'=>2],['HUMOR'=>14,'WARMTH'=>12]),
                $this->o('d','可靠、讓人知道你說的算的人',['RELIABLE'=>3],['RELIABILITY'=>18,'CONFIDENCE'=>8]),
            ],
            249 => [ // 你和新認識的人分開後，你通常在想什麼？
                $this->o('a','希望他有感受到我的善意',['WARM'=>3],['WARMTH'=>16,'RELIABILITY'=>10]),
                $this->o('b','他說的某句話讓我一直在想',['INTELLECTUAL'=>3],['MYSTERY'=>14,'CONFIDENCE'=>12]),
                $this->o('c','想到我說的某個笑話，自己還是覺得很好笑',['FUNNY'=>3],['HUMOR'=>16,'WARMTH'=>10]),
                $this->o('d','他對我的印象是什麼？他會想再見我嗎？',['MAGNETIC'=>2,'RELIABLE'=>2],['CONFIDENCE'=>12,'RELIABILITY'=>14]),
            ],
            250 => [ // 你認為你最吸引人的特質是什麼？
                $this->o('a','我的溫暖——在我面前人們感到安全',['WARM'=>3],['WARMTH'=>18,'RELIABILITY'=>8]),
                $this->o('b','我的自信——我清楚知道我是誰',['CONFIDENT'=>3],['CONFIDENCE'=>18,'RELIABILITY'=>8]),
                $this->o('c','我的幽默——我讓緊繃的場合輕鬆起來',['FUNNY'=>3],['HUMOR'=>18,'WARMTH'=>8]),
                $this->o('d','我的深度——人們在了解我後，總有新的發現',['MYSTERIOUS'=>2,'INTELLECTUAL'=>2],['MYSTERY'=>14,'CONFIDENCE'=>12]),
            ],
            251 => [ // 你第一次見面時，更希望對方記得你的什麼？
                $this->o('a','和我說話時的感覺——被關注、被接納',['WARM'=>3],['WARMTH'=>18,'RELIABILITY'=>8]),
                $this->o('b','一句我說的話，讓他後來還在想',['INTELLECTUAL'=>2,'MYSTERIOUS'=>2],['MYSTERY'=>14,'CONFIDENCE'=>12]),
                $this->o('c','一個讓大家笑的時刻',['FUNNY'=>3],['HUMOR'=>18,'WARMTH'=>8]),
                $this->o('d','一種「這個人不簡單」的直覺',['MAGNETIC'=>2,'CONFIDENT'=>2],['MYSTERY'=>12,'CONFIDENCE'=>14]),
            ],
            252 => [ // 你在社交場合的「招牌動作」是？
                $this->o('a','主動走向還沒認識的人，讓他感到歡迎',['WARM'=>3,'ENERGETIC'=>1],['WARMTH'=>16,'HUMOR'=>10]),
                $this->o('b','說一個讓大家笑的話，打破尷尬',['FUNNY'=>3],['HUMOR'=>18,'WARMTH'=>8]),
                $this->o('c','安靜地觀察，然後在對的時刻說一句話',['MYSTERIOUS'=>2,'INTELLECTUAL'=>2],['MYSTERY'=>14,'CONFIDENCE'=>12]),
                $this->o('d','帶著自信進場，讓人感到你的存在',['CONFIDENT'=>2,'MAGNETIC'=>2],['CONFIDENCE'=>14,'MYSTERY'=>12]),
            ],
            253 => [ // 別人說你讓他們感到？
                $this->o('a','被接受——在你面前不需要表演',['WARM'=>3],['WARMTH'=>18,'RELIABILITY'=>8]),
                $this->o('b','有點摸不透——但越不懂越想靠近',['MYSTERIOUS'=>3],['MYSTERY'=>18,'CONFIDENCE'=>8]),
                $this->o('c','開心——和你在一起心情就好',['FUNNY'=>2,'ENERGETIC'=>2],['HUMOR'=>14,'WARMTH'=>12]),
                $this->o('d','被刺激——和你說話讓我想更多',['INTELLECTUAL'=>3],['CONFIDENCE'=>14,'MYSTERY'=>12]),
            ],
            254 => [ // 你在一個重要的第一印象場合前，你的準備方式是？
                $this->o('a','想清楚對方最需要感受到什麼，然後去做到那個',['WARM'=>2,'RELIABLE'=>2],['WARMTH'=>12,'RELIABILITY'=>14]),
                $this->o('b','準備幾個讓對話輕鬆的話題或開場',['FUNNY'=>2,'ENERGETIC'=>2],['HUMOR'=>12,'WARMTH'=>14]),
                $this->o('c','確認自己的外表和狀態，以最佳狀態出場',['CONFIDENT'=>3],['CONFIDENCE'=>16,'RELIABILITY'=>10]),
                $this->o('d','深入了解對方背景，做到知己知彼',['INTELLECTUAL'=>2,'RELIABLE'=>2],['CONFIDENCE'=>12,'RELIABILITY'=>14]),
            ],
            255 => [ // 你如何讓人記得你？
                $this->o('a','真誠關注他——他說的話你都記得',['WARM'=>3],['WARMTH'=>16,'RELIABILITY'=>10]),
                $this->o('b','說一件他沒想過但有共鳴的事',['INTELLECTUAL'=>2,'MYSTERIOUS'=>2],['MYSTERY'=>14,'CONFIDENCE'=>12]),
                $this->o('c','製造一個讓他笑的時刻',['FUNNY'=>3],['HUMOR'=>16,'WARMTH'=>10]),
                $this->o('d','讓他感到「和你在一起很特別」',['MAGNETIC'=>3],['MYSTERY'=>14,'CONFIDENCE'=>12]),
            ],
            256 => [ // 你認為「好的第一印象」最重要的是？
                $this->o('a','讓對方感到舒適，沒有壓力',['WARM'=>3],['WARMTH'=>18,'RELIABILITY'=>8]),
                $this->o('b','清楚表達自己是誰、你帶來什麼價值',['CONFIDENT'=>3],['CONFIDENCE'=>16,'RELIABILITY'=>10]),
                $this->o('c','讓對話有記憶點，讓他之後還會想起你',['INTELLECTUAL'=>2,'MAGNETIC'=>2],['MYSTERY'=>14,'CONFIDENCE'=>12]),
                $this->o('d','真實——不演，讓對方感受到真實的你',['RELIABLE'=>3],['RELIABILITY'=>16,'WARMTH'=>10]),
            ],
            257 => [ // 你在哪種場合最容易讓人留下好印象？
                $this->o('a','小型的深度對話——一對一或小群體',['INTELLECTUAL'=>2,'WARM'=>2],['WARMTH'=>12,'MYSTERY'=>14]),
                $this->o('b','輕鬆的社交場合——有笑聲有互動',['FUNNY'=>2,'ENERGETIC'=>2],['HUMOR'=>14,'WARMTH'=>12]),
                $this->o('c','正式場合——我表現穩定、讓人信賴',['CONFIDENT'=>2,'RELIABLE'=>2],['CONFIDENCE'=>12,'RELIABILITY'=>14]),
                $this->o('d','初次見面——我的第一印象往往最強烈',['MAGNETIC'=>3],['MYSTERY'=>14,'CONFIDENCE'=>12]),
            ],
            258 => [ // 你對自己的外表和形象的態度是？
                $this->o('a','展現友善——讓人感到親切比酷更重要',['WARM'=>3],['WARMTH'=>16,'RELIABILITY'=>10]),
                $this->o('b','展現自信——我希望我的外表說明我的態度',['CONFIDENT'=>3],['CONFIDENCE'=>16,'MYSTERY'=>10]),
                $this->o('c','不刻意——舒適自然就是我的風格',['RELIABLE'=>2,'ADAPTOR'=>2],['RELIABILITY'=>12,'WARMTH'=>14]),
                $this->o('d','有意識地塑造——形象是一種溝通方式',['MAGNETIC'=>2,'INTELLECTUAL'=>2],['MYSTERY'=>12,'CONFIDENCE'=>14]),
            ],
            259 => [ // 你在不同場合的形象是否會改變？
                $this->o('a','幾乎不變——我到哪裡都是同一個我',['RELIABLE'=>3],['RELIABILITY'=>18,'WARMTH'=>8]),
                $this->o('b','根據場合調整，但核心不變',['CONFIDENT'=>2,'ADAPTOR'=>2],['CONFIDENCE'=>12,'RELIABILITY'=>14]),
                $this->o('c','會——我會讀懂場合需要什麼，然後給它',['MAGNETIC'=>2,'INTELLECTUAL'=>2],['MYSTERY'=>12,'CONFIDENCE'=>14]),
                $this->o('d','有點多面向——在不同人面前有不同的面',['MYSTERIOUS'=>3],['MYSTERY'=>18,'CONFIDENCE'=>8]),
            ],
            260 => [ // 你如何對待初次見面時的尷尬沉默？
                $this->o('a','立刻用問題或話題填補，不讓它尷尬',['WARM'=>2,'ENERGETIC'=>2],['WARMTH'=>14,'HUMOR'=>12]),
                $this->o('b','說一句讓人笑的話，打破冷場',['FUNNY'=>3],['HUMOR'=>18,'WARMTH'=>8]),
                $this->o('c','讓沉默稍微停留，用眼神傳達我在',['MYSTERIOUS'=>2,'INTELLECTUAL'=>2],['MYSTERY'=>14,'CONFIDENCE'=>12]),
                $this->o('d','不慌，沉默也是對話的一部分',['RELIABLE'=>3],['RELIABILITY'=>16,'MYSTERY'=>10]),
            ],
            281 => [ // 你如何讓談話有意思？
                $this->o('a','問一個大家都沒想過的問題',['INTELLECTUAL'=>3],['MYSTERY'=>14,'CONFIDENCE'=>12]),
                $this->o('b','把輕鬆的話題說得讓人捧腹大笑',['FUNNY'=>3],['HUMOR'=>18,'WARMTH'=>8]),
                $this->o('c','讓每個人都有機會說話，沒有人被冷落',['WARM'=>3],['WARMTH'=>18,'RELIABILITY'=>8]),
                $this->o('d','帶入一個意外的角度，讓對話方向轉變',['MAGNETIC'=>2,'MYSTERIOUS'=>2],['MYSTERY'=>14,'CONFIDENCE'=>12]),
            ],
            282 => [ // 你覺得你的存在感是強還是弱？
                $this->o('a','強——我走進哪裡，空氣就不一樣',['MAGNETIC'=>3,'CONFIDENT'=>1],['MYSTERY'=>14,'CONFIDENCE'=>14]),
                $this->o('b','溫和——人們喜歡我在，但我不搶風頭',['WARM'=>3,'RELIABLE'=>1],['WARMTH'=>16,'RELIABILITY'=>10]),
                $this->o('c','強烈但選擇性——我選擇的時候，非常有存在感',['MYSTERIOUS'=>3],['MYSTERY'=>16,'CONFIDENCE'=>10]),
                $this->o('d','要靠聲音和能量——我的笑聲讓我被注意',['FUNNY'=>2,'ENERGETIC'=>2],['HUMOR'=>12,'WARMTH'=>14]),
            ],
            283 => [ // 你最常用什麼讓對方放鬆？
                $this->o('a','問問他今天怎樣，真心傾聽',['WARM'=>3],['WARMTH'=>18,'RELIABILITY'=>8]),
                $this->o('b','一個小笑話，讓他先笑出來',['FUNNY'=>3],['HUMOR'=>18,'WARMTH'=>8]),
                $this->o('c','用平靜的語氣讓他感到不需要緊繃',['RELIABLE'=>2,'MYSTERIOUS'=>2],['RELIABILITY'=>12,'MYSTERY'=>14]),
                $this->o('d','分享一件我自己有點丟臉的事，先示弱',['INTELLECTUAL'=>2,'WARM'=>2],['WARMTH'=>12,'RELIABILITY'=>14]),
            ],
        ];

        foreach ($data as $id => $options) {
            $this->set($id, $options);
        }
        $this->command->info('  ✅ 第一印象 (23 questions)');
    }

    // ════════════════════════════════════════════════════════════════════════
    // 人生方向
    // Types: ACHIEVER, MEANING_SEEKER, FREE_SPIRIT, CONNECTOR, BUILDER, SECURITY_SEEKER, EXPLORER, LEGACY_BUILDER
    // ════════════════════════════════════════════════════════════════════════
    private function fixLifeDirection(): void
    {
        $data = [
            284 => [ // 什麼讓你在早上願意起床？
                $this->o('a','有一件今天必須完成的任務，我不能輸',['ACHIEVER'=>3],['ACHIEVEMENT'=>16,'MEANING'=>10]),
                $this->o('b','知道今天我做的事對某個人是有意義的',['MEANING_SEEKER'=>2,'CONNECTOR'=>2],['MEANING'=>14,'CONNECTION'=>12]),
                $this->o('c','不知道今天會遇見什麼——這讓我期待',['EXPLORER'=>3],['FREEDOM'=>16,'MEANING'=>10]),
                $this->o('d','生活中有穩定的節奏和目標讓我安心前進',['SECURITY_SEEKER'=>2,'BUILDER'=>2],['SECURITY'=>14,'ACHIEVEMENT'=>12]),
            ],
            285 => [ // 你認為最值得為之犧牲的事是什麼？
                $this->o('a','一個能讓我突破自己、達到新高度的目標',['ACHIEVER'=>3],['ACHIEVEMENT'=>16,'MEANING'=>10]),
                $this->o('b','讓某個群體或某個人的生命變好',['MEANING_SEEKER'=>2,'CONNECTOR'=>2],['MEANING'=>14,'CONNECTION'=>12]),
                $this->o('c','一個從無到有的創造，讓我把想法變成現實',['BUILDER'=>3],['ACHIEVEMENT'=>14,'MEANING'=>12]),
                $this->o('d','讓這個世界在我離開後仍記得我做過的事',['LEGACY_BUILDER'=>3],['MEANING'=>16,'ACHIEVEMENT'=>10]),
            ],
            286 => [ // 你在哪個方向感到最有活力？
                $this->o('a','向前衝——衝破目標帶來的那種能量',['ACHIEVER'=>3],['ACHIEVEMENT'=>18,'MEANING'=>8]),
                $this->o('b','向內探索——了解自己和他人的深層需求',['MEANING_SEEKER'=>3],['MEANING'=>16,'CONNECTION'=>10]),
                $this->o('c','向外擴張——探索沒去過的地方和沒試過的事',['EXPLORER'=>3],['FREEDOM'=>16,'MEANING'=>10]),
                $this->o('d','向下紮根——把已有的事情做得更深、更紮實',['BUILDER'=>2,'SECURITY_SEEKER'=>2],['SECURITY'=>12,'ACHIEVEMENT'=>14]),
            ],
            287 => [ // 如果錢不是問題，你會做什麼？
                $this->o('a','去挑戰一個讓我感到恐懼的大目標',['ACHIEVER'=>3,'EXPLORER'=>1],['ACHIEVEMENT'=>14,'FREEDOM'=>12]),
                $this->o('b','投入某個我真正相信能改變世界的事',['MEANING_SEEKER'=>2,'LEGACY_BUILDER'=>2],['MEANING'=>16,'ACHIEVEMENT'=>10]),
                $this->o('c','旅行、探索，不斷擴大我的生命視野',['EXPLORER'=>3],['FREEDOM'=>18,'MEANING'=>8]),
                $this->o('d','建立某個可以讓我的家人或社群更好的東西',['BUILDER'=>2,'CONNECTOR'=>2],['CONNECTION'=>14,'ACHIEVEMENT'=>12]),
            ],
            288 => [ // 你對十年後的自己最重要的期望是什麼？
                $this->o('a','我達到了那個讓現在的自己覺得遙不可及的目標',['ACHIEVER'=>3],['ACHIEVEMENT'=>18,'MEANING'=>8]),
                $this->o('b','我的存在讓某些人或某件事變得更好',['MEANING_SEEKER'=>2,'LEGACY_BUILDER'=>2],['MEANING'=>14,'CONNECTION'=>12]),
                $this->o('c','我還在探索，還在成長，沒有停下來',['EXPLORER'=>3],['FREEDOM'=>16,'MEANING'=>10]),
                $this->o('d','我和重要的人之間的關係是深刻和真實的',['CONNECTOR'=>3],['CONNECTION'=>18,'MEANING'=>8]),
            ],
            289 => [ // 你最害怕在人生結束時沒有完成的事是什麼？
                $this->o('a','那件我一直說「以後再說」但其實一直渴望的事',['ACHIEVER'=>2,'EXPLORER'=>2],['ACHIEVEMENT'=>12,'FREEDOM'=>14]),
                $this->o('b','對我在乎的人說出那些一直藏在心裡的話',['CONNECTOR'=>3],['CONNECTION'=>18,'MEANING'=>8]),
                $this->o('c','那個我一直在腦海中建設但沒有真正去做的夢想',['BUILDER'=>2,'DREAMER'=>2],['ACHIEVEMENT'=>12,'MEANING'=>14]),
                $this->o('d','讓我的存在對這個世界留下有意義的痕跡',['LEGACY_BUILDER'=>3],['MEANING'=>18,'ACHIEVEMENT'=>8]),
            ],
            290 => [ // 你如何定義「成功的人生」？
                $this->o('a','設定了目標，並且一個一個達成',['ACHIEVER'=>3],['ACHIEVEMENT'=>18,'MEANING'=>8]),
                $this->o('b','知道自己為什麼活著，並且忠實於那個原因',['MEANING_SEEKER'=>3],['MEANING'=>18,'ACHIEVEMENT'=>8]),
                $this->o('c','活出了只屬於自己的方式，沒有活在別人的期待裡',['FREE_SPIRIT'=>3],['FREEDOM'=>18,'MEANING'=>8]),
                $this->o('d','在身邊的人心裡留下了真實的影響',['CONNECTOR'=>2,'LEGACY_BUILDER'=>2],['CONNECTION'=>14,'MEANING'=>12]),
            ],
            291 => [ // 你認為什麼讓人生有意義？
                $this->o('a','持續突破自己，成為比昨天更好的人',['ACHIEVER'=>3],['ACHIEVEMENT'=>16,'MEANING'=>10]),
                $this->o('b','做讓這個世界更好的事，哪怕很小',['MEANING_SEEKER'=>2,'LEGACY_BUILDER'=>2],['MEANING'=>16,'CONNECTION'=>10]),
                $this->o('c','深刻地愛和被愛——關係是一切的根',['CONNECTOR'=>3],['CONNECTION'=>18,'MEANING'=>8]),
                $this->o('d','不斷探索和發現，讓生命永遠有新鮮感',['EXPLORER'=>3],['FREEDOM'=>16,'MEANING'=>10]),
            ],
            292 => [ // 你最想留給後人的是什麼？
                $this->o('a','一個他們可以超越的高標準',['ACHIEVER'=>2,'LEGACY_BUILDER'=>2],['ACHIEVEMENT'=>12,'MEANING'=>14]),
                $this->o('b','一種對待生命的態度——讓他們也想這樣活著',['MEANING_SEEKER'=>2,'LEGACY_BUILDER'=>2],['MEANING'=>14,'CONNECTION'=>12]),
                $this->o('c','一些真正有用的東西，讓他們的生活更好',['BUILDER'=>3],['ACHIEVEMENT'=>14,'MEANING'=>12]),
                $this->o('d','幾段真實深刻的關係，讓他們知道被愛過',['CONNECTOR'=>3],['CONNECTION'=>18,'MEANING'=>8]),
            ],
            293 => [ // 你的人生核心價值是什麼？
                $this->o('a','成就——我相信我能做到，而且我要做到',['ACHIEVER'=>3],['ACHIEVEMENT'=>18,'MEANING'=>8]),
                $this->o('b','意義——做的每件事都要值得',['MEANING_SEEKER'=>3],['MEANING'=>18,'ACHIEVEMENT'=>8]),
                $this->o('c','自由——我的時間和選擇是我的',['FREE_SPIRIT'=>3],['FREEDOM'=>18,'MEANING'=>8]),
                $this->o('d','連結——生命的意義在於人與人之間',['CONNECTOR'=>3],['CONNECTION'=>18,'MEANING'=>8]),
            ],
            294 => [ // 你在哪種工作中感到最有生命力？
                $this->o('a','挑戰性高、需要全力以赴的工作',['ACHIEVER'=>3],['ACHIEVEMENT'=>18,'MEANING'=>8]),
                $this->o('b','對真實的人有直接幫助的工作',['MEANING_SEEKER'=>2,'CONNECTOR'=>2],['MEANING'=>14,'CONNECTION'=>12]),
                $this->o('c','從零開始創造某個東西的工作',['BUILDER'=>3],['ACHIEVEMENT'=>14,'MEANING'=>12]),
                $this->o('d','讓我探索不同領域和可能性的工作',['EXPLORER'=>3],['FREEDOM'=>16,'MEANING'=>10]),
            ],
            295 => [ // 你如何在人生方向上做決定？
                $this->o('a','看哪個選擇讓我更靠近我的目標',['ACHIEVER'=>3],['ACHIEVEMENT'=>16,'MEANING'=>10]),
                $this->o('b','問自己：這件事有意義嗎？我相信它嗎？',['MEANING_SEEKER'=>3],['MEANING'=>16,'CONNECTION'=>10]),
                $this->o('c','哪個選擇給我更多自由，我就選哪個',['FREE_SPIRIT'=>3],['FREEDOM'=>18,'MEANING'=>8]),
                $this->o('d','哪個選擇讓我和在乎的人更近，我選那個',['CONNECTOR'=>3],['CONNECTION'=>16,'MEANING'=>10]),
            ],
            296 => [ // 你如何平衡「追求夢想」和「現實考量」？
                $this->o('a','先讓現實穩定，然後在夢想上一點一點推進',['SECURITY_SEEKER'=>2,'BUILDER'=>2],['SECURITY'=>14,'ACHIEVEMENT'=>12]),
                $this->o('b','追夢的路上，讓夢想也有盈利的方式',['ACHIEVER'=>2,'BUILDER'=>2],['ACHIEVEMENT'=>14,'SECURITY'=>12]),
                $this->o('c','夢想優先——現實是可以被解決的問題',['FREE_SPIRIT'=>2,'EXPLORER'=>2],['FREEDOM'=>14,'ACHIEVEMENT'=>12]),
                $this->o('d','找到讓夢想和意義同時存在的交叉點',['MEANING_SEEKER'=>2,'LEGACY_BUILDER'=>2],['MEANING'=>14,'ACHIEVEMENT'=>12]),
            ],
            297 => [ // 你最願意為什麼努力工作？
                $this->o('a','為了突破自己的極限，看看我能走多遠',['ACHIEVER'=>3],['ACHIEVEMENT'=>18,'MEANING'=>8]),
                $this->o('b','為了讓我在乎的人過得更好',['CONNECTOR'=>2,'MEANING_SEEKER'=>2],['CONNECTION'=>14,'MEANING'=>12]),
                $this->o('c','為了建設一個有形的、可以留下來的東西',['BUILDER'=>3],['ACHIEVEMENT'=>14,'MEANING'=>12]),
                $this->o('d','為了讓我的下一代有更好的起點',['LEGACY_BUILDER'=>3],['MEANING'=>16,'ACHIEVEMENT'=>10]),
            ],
            298 => [ // 你認為人生最重要的三件事是什麼？
                $this->o('a','成就、成長、留下影響力',['ACHIEVER'=>2,'LEGACY_BUILDER'=>2],['ACHIEVEMENT'=>14,'MEANING'=>12]),
                $this->o('b','意義、連結、真實',['MEANING_SEEKER'=>2,'CONNECTOR'=>2],['MEANING'=>14,'CONNECTION'=>12]),
                $this->o('c','自由、探索、可能性',['FREE_SPIRIT'=>2,'EXPLORER'=>2],['FREEDOM'=>14,'MEANING'=>12]),
                $this->o('d','安全、建設、傳承',['SECURITY_SEEKER'=>2,'BUILDER'=>2],['SECURITY'=>12,'ACHIEVEMENT'=>14]),
            ],
            299 => [ // 你在什麼情況下最感到人生值得？
                $this->o('a','達成了一個曾經讓我覺得不可能的目標',['ACHIEVER'=>3],['ACHIEVEMENT'=>18,'MEANING'=>8]),
                $this->o('b','在某個平凡的時刻，突然感受到了深深的感謝',['MEANING_SEEKER'=>3],['MEANING'=>18,'CONNECTION'=>8]),
                $this->o('c','探索到某個讓我感到驚訝和喜悅的新事物',['EXPLORER'=>3],['FREEDOM'=>16,'MEANING'=>10]),
                $this->o('d','和某個人有了一段讓彼此都感到真實的連結',['CONNECTOR'=>3],['CONNECTION'=>18,'MEANING'=>8]),
            ],
            300 => [ // 你如何看待「安穩」和「冒險」的選擇？
                $this->o('a','安穩是我追求的——有了根，才能走得遠',['SECURITY_SEEKER'=>3],['SECURITY'=>18,'ACHIEVEMENT'=>8]),
                $this->o('b','冒險是我的本能——安穩讓我感到窒息',['FREE_SPIRIT'=>2,'EXPLORER'=>2],['FREEDOM'=>16,'ACHIEVEMENT'=>10]),
                $this->o('c','兩者都需要——不同的人生階段需要不同的選擇',['ADAPTOR'=>2,'MEANING_SEEKER'=>2],['MEANING'=>12,'SECURITY'=>14]),
                $this->o('d','冒險是為了建設更好的安穩，不是為了冒險而冒險',['BUILDER'=>2,'ACHIEVER'=>2],['ACHIEVEMENT'=>12,'SECURITY'=>14]),
            ],
            301 => [ // 你最想在哪個領域留下影響力？
                $this->o('a','讓某個產業或系統變得更好的地方',['ACHIEVER'=>2,'INNOVATOR'=>2],['ACHIEVEMENT'=>12,'MEANING'=>14]),
                $this->o('b','讓人的內心世界更豐富或更療癒的地方',['MEANING_SEEKER'=>3],['MEANING'=>18,'CONNECTION'=>8]),
                $this->o('c','讓某個社群或群體更有連結感的地方',['CONNECTOR'=>3],['CONNECTION'=>18,'MEANING'=>8]),
                $this->o('d','讓後來的人有更好的基礎出發的地方',['LEGACY_BUILDER'=>3],['MEANING'=>16,'ACHIEVEMENT'=>10]),
            ],
            302 => [ // 你如何看待人生中的「失敗」？
                $this->o('a','它讓我更清楚什麼不是我的路，幫我聚焦',['ACHIEVER'=>2,'MEANING_SEEKER'=>2],['ACHIEVEMENT'=>12,'MEANING'=>14]),
                $this->o('b','它是探索的必然代價，我接受',['EXPLORER'=>3],['FREEDOM'=>16,'MEANING'=>10]),
                $this->o('c','它讓我更了解自己的極限，也更了解自己的韌性',['ACHIEVER'=>3],['ACHIEVEMENT'=>16,'MEANING'=>10]),
                $this->o('d','它讓我意識到什麼對我真正重要，值得繼續',['MEANING_SEEKER'=>3],['MEANING'=>18,'ACHIEVEMENT'=>8]),
            ],
            303 => [ // 你如何讓自己的選擇和價值觀一致？
                $this->o('a','在做重要決定前，先問自己：這符合我的目標嗎？',['ACHIEVER'=>3],['ACHIEVEMENT'=>16,'MEANING'=>10]),
                $this->o('b','讓自己有定期反思的習慣，問自己活得真實嗎',['MEANING_SEEKER'=>3],['MEANING'=>18,'ACHIEVEMENT'=>8]),
                $this->o('c','說不——拒絕那些讓我偏離核心的事',['FREE_SPIRIT'=>2,'ACHIEVER'=>2],['FREEDOM'=>12,'ACHIEVEMENT'=>14]),
                $this->o('d','讓重要的人知道我的價值觀，讓他們幫我把關',['CONNECTOR'=>3],['CONNECTION'=>16,'MEANING'=>10]),
            ],
        ];

        foreach ($data as $id => $options) {
            $this->set($id, $options);
        }
        $this->command->info('  ✅ 人生方向 (20 questions)');
    }

    // ════════════════════════════════════════════════════════════════════════
    // 魅力吸引力
    // Types: MAGNETIC_C, VERBAL_C, WARM_C, MYSTERIOUS_C, CONFIDENT_C, RELIABLE_C, INTELLECTUAL_C, FREE_C
    // ════════════════════════════════════════════════════════════════════════
    private function fixCharisma(): void
    {
        $d = fn($p, $v, $e, $m) => ['PRESENCE'=>$p,'VERBAL'=>$v,'EMOTIONAL'=>$e,'MYSTERY_C'=>$m];
        $data = [
            384 => [ // 別人說你最讓他們著迷的是什麼？
                $this->o('a','你走進來，空氣就不同了',['MAGNETIC_C'=>3],['PRESENCE'=>18,'MYSTERY_C'=>8]),
                $this->o('b','你說的話，讓我很久之後還在想',['VERBAL_C'=>3],['VERBAL'=>18,'PRESENCE'=>8]),
                $this->o('c','在你面前，我感到可以做真實的自己',['WARM_C'=>3],['EMOTIONAL'=>18,'PRESENCE'=>8]),
                $this->o('d','你的深度，每次靠近都有新的發現',['MYSTERIOUS_C'=>3],['MYSTERY_C'=>18,'PRESENCE'=>8]),
            ],
            385 => [ // 你認為你的魅力來自哪裡？
                $this->o('a','我的存在感——我在或不在，別人感受得到',['MAGNETIC_C'=>3],['PRESENCE'=>18,'MYSTERY_C'=>8]),
                $this->o('b','我的語言——我說話的方式讓人想繼續聽',['VERBAL_C'=>3],['VERBAL'=>18,'PRESENCE'=>8]),
                $this->o('c','我的真實——我不演，讓人感到被尊重',['FREE_C'=>2,'WARM_C'=>2],['EMOTIONAL'=>14,'MYSTERY_C'=>12]),
                $this->o('d','我的自信——我清楚知道我是誰',['CONFIDENT_C'=>3],['PRESENCE'=>14,'VERBAL'=>12]),
            ],
            386 => [ // 你在什麼情況下最讓人感到你的存在感？
                $this->o('a','走進一個充滿陌生人的房間的那一刻',['MAGNETIC_C'=>3],['PRESENCE'=>18,'MYSTERY_C'=>8]),
                $this->o('b','開口說話之後——話語讓人記住我',['VERBAL_C'=>3],['VERBAL'=>18,'PRESENCE'=>8]),
                $this->o('c','安靜地在某個人最需要的時候出現',['RELIABLE_C'=>2,'WARM_C'=>2],['EMOTIONAL'=>14,'TRUST_BASED'=>12]),
                $this->o('d','當我做了什麼出乎意料的事',['FREE_C'=>2,'MYSTERIOUS_C'=>2],['MYSTERY_C'=>14,'PRESENCE'=>12]),
            ],
            387 => [ // 你如何在短時間內讓人記住你？
                $this->o('a','說一句讓他們一直在想的話',['VERBAL_C'=>3],['VERBAL'=>18,'PRESENCE'=>8]),
                $this->o('b','讓他在我面前感到被完全接受',['WARM_C'=>3],['EMOTIONAL'=>18,'TRUST_BASED'=>8]),
                $this->o('c','做一件他完全意料之外的事',['FREE_C'=>2,'MAGNETIC_C'=>2],['MYSTERY_C'=>14,'PRESENCE'=>12]),
                $this->o('d','讓他感到和我說話是件值得記住的事',['INTELLECTUAL_C'=>2,'MAGNETIC_C'=>2],['VERBAL'=>12,'MYSTERY_C'=>14]),
            ],
            388 => [ // 你覺得什麼是讓一個人有魅力的關鍵？
                $this->o('a','在場感——完全投入，沒有分心',['MAGNETIC_C'=>3],['PRESENCE'=>18,'MYSTERY_C'=>8]),
                $this->o('b','語言能力——選詞精準，讓話有分量',['VERBAL_C'=>3],['VERBAL'=>18,'PRESENCE'=>8]),
                $this->o('c','讓人感到安全——在你面前不需要防備',['WARM_C'=>3],['EMOTIONAL'=>18,'TRUST_BASED'=>8]),
                $this->o('d','讓人感到你有深度，值得繼續了解',['MYSTERIOUS_C'=>3],['MYSTERY_C'=>18,'PRESENCE'=>8]),
            ],
            389 => [ // 你在談話中最自然做到的是？
                $this->o('a','讓對方感到這段對話很特別',['MAGNETIC_C'=>3],['PRESENCE'=>16,'MYSTERY_C'=>10]),
                $this->o('b','用語言讓複雜的事情變得清晰',['VERBAL_C'=>2,'INTELLECTUAL_C'=>2],['VERBAL'=>14,'PRESENCE'=>12]),
                $this->o('c','讓對方覺得被真正傾聽和理解',['WARM_C'=>3],['EMOTIONAL'=>18,'TRUST_BASED'=>8]),
                $this->o('d','讓對話走向意想不到的深度',['INTELLECTUAL_C'=>2,'MYSTERIOUS_C'=>2],['VERBAL'=>12,'MYSTERY_C'=>14]),
            ],
            390 => [ // 你讓別人最常說你的一個特質是？
                $this->o('a','「你一出現，整個場合就不一樣了」',['MAGNETIC_C'=>3],['PRESENCE'=>18,'MYSTERY_C'=>8]),
                $this->o('b','「你說的話，我回去之後還在想」',['VERBAL_C'=>3],['VERBAL'=>18,'PRESENCE'=>8]),
                $this->o('c','「和你說話，我感到很放鬆」',['WARM_C'=>3],['EMOTIONAL'=>18,'TRUST_BASED'=>8]),
                $this->o('d','「我一直覺得你還有很多面沒看到」',['MYSTERIOUS_C'=>3],['MYSTERY_C'=>18,'PRESENCE'=>8]),
            ],
            391 => [ // 你認為魅力是天生的還是培養的？
                $this->o('a','天生的——它是一種存在方式，不是技巧',['MAGNETIC_C'=>2,'FREE_C'=>2],['PRESENCE'=>14,'MYSTERY_C'=>12]),
                $this->o('b','培養的——我透過語言和表達讓自己更有影響力',['VERBAL_C'=>2,'INTELLECTUAL_C'=>2],['VERBAL'=>14,'PRESENCE'=>12]),
                $this->o('c','從內而外的——從真實開始，魅力自然出來',['WARM_C'=>2,'RELIABLE_C'=>2],['EMOTIONAL'=>14,'TRUST_BASED'=>12]),
                $this->o('d','深度帶來的——你越有深度，越讓人著迷',['MYSTERIOUS_C'=>2,'INTELLECTUAL_C'=>2],['MYSTERY_C'=>14,'VERBAL'=>12]),
            ],
            392 => [ // 你面對一個你想讓對方對你有好印象的場合，你最依賴什麼？
                $this->o('a','我的氣場和在場感——讓他感受到我的存在',['MAGNETIC_C'=>3],['PRESENCE'=>18,'MYSTERY_C'=>8]),
                $this->o('b','我說話的方式——選擇對的詞，說對的話',['VERBAL_C'=>3],['VERBAL'=>18,'PRESENCE'=>8]),
                $this->o('c','真誠的關注——讓他感到自己是重要的',['WARM_C'=>3],['EMOTIONAL'=>18,'TRUST_BASED'=>8]),
                $this->o('d','我的可靠感——讓他知道我說的算',['RELIABLE_C'=>3],['TRUST_BASED'=>18,'PRESENCE'=>8]),
            ],
            393 => [ // 你覺得自己最具吸引力的一面是什麼？
                $this->o('a','我的存在感——我能讓任何場合改變溫度',['MAGNETIC_C'=>3],['PRESENCE'=>18,'MYSTERY_C'=>8]),
                $this->o('b','我的語言魅力——我說話的方式讓人想繼續聽',['VERBAL_C'=>3],['VERBAL'=>18,'PRESENCE'=>8]),
                $this->o('c','我的溫暖——讓人在我面前感到被接受',['WARM_C'=>3],['EMOTIONAL'=>18,'TRUST_BASED'=>8]),
                $this->o('d','我的深度——每次靠近都有新的東西可以發現',['MYSTERIOUS_C'=>3],['MYSTERY_C'=>18,'PRESENCE'=>8]),
            ],
        ];

        foreach ($data as $id => $options) {
            $this->set($id, $options);
        }
        $this->command->info('  ✅ 魅力吸引力 (10 questions)');
    }
}
