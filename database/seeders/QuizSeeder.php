<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\ResultType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $quiz = Quiz::create([
            'title' => '你的職場溝通風格測驗',
            'description' => '透過 10 道情境題，找出你在職場中的溝通風格與人際相處模式，並獲得專屬分析報告。',
            'slug' => 'workplace-communication-style',
            'price' => 1900,
            'is_active' => true,
            'meta' => [
                'emoji'             => '💼',
                'card_bg'           => 'from-brand-500 to-violet-500',
                'card_light'        => 'from-brand-50 to-violet-50',
                'tag'               => '職場測驗',
                'cover_image'       => null,
                'estimated_minutes' => 5,
                'tags'              => ['職場', '溝通', '人格特質'],
            ],
        ]);

        $this->seedResultTypes($quiz);
        $this->seedQuestions($quiz);
    }

    private function seedResultTypes(Quiz $quiz): void
    {
        $types = [
            [
                'code' => 'DRIVER',
                'title' => '主導型 Driver',
                'description' => '你果斷、直接，喜歡掌控局面，以結果為導向。你在高壓環境下依然能快速做決策，是天生的領導者。',
                'report_content' => '<h2>主導型 Driver</h2><p>你的溝通風格直接而有效率，你喜歡快速得到結論，並推動事情向前進展。在職場中，你往往成為帶動團隊的核心人物。</p><h3>優勢</h3><ul><li>決策快速，不猶豫</li><li>目標導向，執行力強</li><li>在壓力下依然冷靜</li></ul><h3>成長方向</h3><ul><li>練習傾聽他人的想法，避免忽略細節</li><li>在溝通時保留更多空間給對方</li></ul>',
                'sort_order' => 1,
            ],
            [
                'code' => 'EXPRESSIVE',
                'title' => '表現型 Expressive',
                'description' => '你充滿熱情、善於表達，喜歡激勵他人。你天生的魅力讓你在社交場合如魚得水，能輕易建立人際連結。',
                'report_content' => '<h2>表現型 Expressive</h2><p>你是職場中的活力來源，善於用故事和情感感染他人，讓枯燥的議題變得生動有趣。</p><h3>優勢</h3><ul><li>高度感染力與說服力</li><li>創意豐富，思維跳躍</li><li>擅長建立人際關係</li></ul><h3>成長方向</h3><ul><li>注意對話的結構與邏輯性</li><li>確保想法能有效轉化為行動</li></ul>',
                'sort_order' => 2,
            ],
            [
                'code' => 'AMIABLE',
                'title' => '親和型 Amiable',
                'description' => '你溫暖、體貼，優先考慮他人感受。你是團隊中的潤滑劑，讓每個人都感到被重視與包容。',
                'report_content' => '<h2>親和型 Amiable</h2><p>你的溝通方式溫和而細膩，你天生具備同理心，能感受到他人的情緒狀態並給予適當的支持。</p><h3>優勢</h3><ul><li>高度同理心</li><li>擅長維繫長期關係</li><li>團隊協調者</li></ul><h3>成長方向</h3><ul><li>練習在必要時表達自身立場</li><li>避免過度委屈自己以取悅他人</li></ul>',
                'sort_order' => 3,
            ],
            [
                'code' => 'ANALYTICAL',
                'title' => '分析型 Analytical',
                'description' => '你縝密、邏輯性強，做決策前會蒐集大量資訊。你對品質有高度要求，是追求精準與完美的思考者。',
                'report_content' => '<h2>分析型 Analytical</h2><p>你的溝通建立在數據與事實之上，你會在充分了解全貌後才發言，讓你的意見往往具有高度說服力。</p><h3>優勢</h3><ul><li>嚴謹、準確，不輕易犯錯</li><li>善於識別問題的根本原因</li><li>規劃能力強</li></ul><h3>成長方向</h3><ul><li>嘗試在資訊不完整時做出決策</li><li>加強與直覺型同事的溝通方式</li></ul>',
                'sort_order' => 4,
            ],
            [
                'code' => 'NEGOTIATOR',
                'title' => '協商型 Negotiator',
                'description' => '你善於找到各方的平衡點，能在衝突中保持冷靜，引導對話走向共識。',
                'report_content' => '<h2>協商型 Negotiator</h2><p>你天生具備外交特質，能夠理解不同立場，並找到讓各方都能接受的解決方案。</p><h3>優勢</h3><ul><li>衝突處理能力強</li><li>靈活應變</li><li>善於傾聽多方意見</li></ul><h3>成長方向</h3><ul><li>明確表達自身核心立場</li><li>避免為求和諧而過度妥協</li></ul>',
                'sort_order' => 5,
            ],
            [
                'code' => 'VISIONARY',
                'title' => '願景型 Visionary',
                'description' => '你具有前瞻思維，能看到他人所看不到的機會與可能性，是組織中的創新推手。',
                'report_content' => '<h2>願景型 Visionary</h2><p>你的溝通充滿未來感，你能將宏觀的想法具體化，激勵他人看見更大的圖景。</p><h3>優勢</h3><ul><li>創新思維</li><li>高度激勵性</li><li>能連結跨領域資源</li></ul><h3>成長方向</h3><ul><li>加強執行細節的關注</li><li>確保願景能落地成為可行計畫</li></ul>',
                'sort_order' => 6,
            ],
            [
                'code' => 'SUPPORTER',
                'title' => '支持型 Supporter',
                'description' => '你低調、可靠，默默為團隊付出。你是他人最信任的夥伴，關鍵時刻永遠在場。',
                'report_content' => '<h2>支持型 Supporter</h2><p>你的力量在於穩定性與可靠性，你不需要聚光燈，卻是讓整個團隊得以運作的基石。</p><h3>優勢</h3><ul><li>高度可靠，言出必行</li><li>善於細心照顧他人需求</li><li>不輕易放棄</li></ul><h3>成長方向</h3><ul><li>練習主動爭取機會展現自身價值</li><li>提升自我倡導能力</li></ul>',
                'sort_order' => 7,
            ],
            [
                'code' => 'CHALLENGER',
                'title' => '挑戰型 Challenger',
                'description' => '你勇於質疑現狀，不怕說出不同意見。你的批判性思維是推動組織進步的重要力量。',
                'report_content' => '<h2>挑戰型 Challenger</h2><p>你不輕易接受「一直都是這樣做」的答案，你的質疑精神促使團隊持續反思與進步。</p><h3>優勢</h3><ul><li>批判性思維強</li><li>不怕逆流而行</li><li>推動創新改革</li></ul><h3>成長方向</h3><ul><li>在提出質疑的同時，也帶來解決方案</li><li>注意溝通語氣，避免引起不必要的對立</li></ul>',
                'sort_order' => 8,
            ],
        ];

        foreach ($types as $type) {
            ResultType::create(array_merge(['quiz_id' => $quiz->id], $type));
        }
    }

    private function seedQuestions(Quiz $quiz): void
    {
        $questions = [
            [
                'body' => '你剛加入一個新專案，團隊成員對進行方向意見不一。你會怎麼做？',
                'options' => [
                    ['key' => 'a', 'label' => '直接提出自己認為最有效率的方案，說服大家跟進', 'scores' => ['DRIVER' => 3, 'CHALLENGER' => 2]],
                    ['key' => 'b', 'label' => '先聽完所有人的意見，再熱情地分享你的想法', 'scores' => ['EXPRESSIVE' => 3, 'NEGOTIATOR' => 1]],
                    ['key' => 'c', 'label' => '默默觀察，等待適合的時機才發言', 'scores' => ['ANALYTICAL' => 2, 'SUPPORTER' => 2]],
                    ['key' => 'd', 'label' => '主動協調，試著找出大家都能接受的中間方案', 'scores' => ['AMIABLE' => 2, 'NEGOTIATOR' => 3]],
                ],
            ],
            [
                'body' => '你的主管交付了一個模糊的任務，只說「你去處理一下」。你的第一反應是？',
                'options' => [
                    ['key' => 'a', 'label' => '立刻開始動作，邊做邊調整方向', 'scores' => ['DRIVER' => 3, 'EXPRESSIVE' => 1]],
                    ['key' => 'b', 'label' => '找主管釐清細節，確認預期目標後再行動', 'scores' => ['ANALYTICAL' => 3, 'SUPPORTER' => 1]],
                    ['key' => 'c', 'label' => '先問問有經驗的同事，了解前例做法', 'scores' => ['AMIABLE' => 2, 'SUPPORTER' => 2]],
                    ['key' => 'd', 'label' => '思考這個任務背後可能帶來的機會，從更宏觀的角度來規劃', 'scores' => ['VISIONARY' => 3, 'CHALLENGER' => 1]],
                ],
            ],
            [
                'body' => '會議中你不同意某位資深同事的觀點，你會怎麼回應？',
                'options' => [
                    ['key' => 'a', 'label' => '當場清楚表達反對意見，並提出數據支持', 'scores' => ['DRIVER' => 2, 'CHALLENGER' => 3]],
                    ['key' => 'b', 'label' => '先認同對方的出發點，再委婉提出你的不同看法', 'scores' => ['AMIABLE' => 2, 'NEGOTIATOR' => 3]],
                    ['key' => 'c', 'label' => '會後私下找對方溝通，避免公開衝突', 'scores' => ['SUPPORTER' => 2, 'AMIABLE' => 2]],
                    ['key' => 'd', 'label' => '先蒐集更多資料，等有更充分的論據後再提出', 'scores' => ['ANALYTICAL' => 3, 'SUPPORTER' => 1]],
                ],
            ],
            [
                'body' => '你發現自己負責的專案可能有更好的做法，但這需要打破既有流程。你會？',
                'options' => [
                    ['key' => 'a', 'label' => '直接向上提案，用具體數字說明新方法的優勢', 'scores' => ['DRIVER' => 2, 'ANALYTICAL' => 2, 'CHALLENGER' => 1]],
                    ['key' => 'b', 'label' => '先小範圍試驗，用成果說服團隊', 'scores' => ['VISIONARY' => 3, 'DRIVER' => 1]],
                    ['key' => 'c', 'label' => '和幾位核心成員討論可行性，再一起推動', 'scores' => ['NEGOTIATOR' => 2, 'AMIABLE' => 2]],
                    ['key' => 'd', 'label' => '研究現有問題並整理成完整報告，讓決策者有充分資訊', 'scores' => ['ANALYTICAL' => 3, 'SUPPORTER' => 1]],
                ],
            ],
            [
                'body' => '團隊中有位同事的工作表現明顯影響到整體進度，但你並非他的主管。你會？',
                'options' => [
                    ['key' => 'a', 'label' => '直接找他談，坦率說明問題所在', 'scores' => ['DRIVER' => 3, 'CHALLENGER' => 1]],
                    ['key' => 'b', 'label' => '先關心他是否有遇到困難，提供協助', 'scores' => ['AMIABLE' => 3, 'SUPPORTER' => 2]],
                    ['key' => 'c', 'label' => '向主管反映，讓正式管道來處理', 'scores' => ['ANALYTICAL' => 2, 'SUPPORTER' => 1]],
                    ['key' => 'd', 'label' => '找機會在輕鬆的場合側面了解情況', 'scores' => ['EXPRESSIVE' => 2, 'NEGOTIATOR' => 2]],
                ],
            ],
            [
                'body' => '你需要向跨部門的陌生同事尋求協助，但對方看起來很忙。你會如何開口？',
                'options' => [
                    ['key' => 'a', 'label' => '直接說明需求和目的，尊重對方時間', 'scores' => ['DRIVER' => 3, 'ANALYTICAL' => 1]],
                    ['key' => 'b', 'label' => '先聊幾句建立關係，再自然帶出請求', 'scores' => ['EXPRESSIVE' => 3, 'AMIABLE' => 1]],
                    ['key' => 'c', 'label' => '寫信詳細說明背景與需求，讓對方有時間評估', 'scores' => ['ANALYTICAL' => 3, 'SUPPORTER' => 1]],
                    ['key' => 'd', 'label' => '先問對方方不方便，再視情況說明', 'scores' => ['AMIABLE' => 2, 'NEGOTIATOR' => 2]],
                ],
            ],
            [
                'body' => '專案遇到重大瓶頸，team 的士氣低落。你會採取什麼行動？',
                'options' => [
                    ['key' => 'a', 'label' => '立刻召集會議，重新釐清優先序和下一步', 'scores' => ['DRIVER' => 3, 'NEGOTIATOR' => 1]],
                    ['key' => 'b', 'label' => '用你的樂觀和故事激勵大家，重燃士氣', 'scores' => ['EXPRESSIVE' => 3, 'VISIONARY' => 1]],
                    ['key' => 'c', 'label' => '一一找成員聊聊，傾聽他們的狀況與需求', 'scores' => ['AMIABLE' => 3, 'SUPPORTER' => 1]],
                    ['key' => 'd', 'label' => '分析問題根源，提出系統性解決方案', 'scores' => ['ANALYTICAL' => 3, 'CHALLENGER' => 1]],
                ],
            ],
            [
                'body' => '你在一個重要會議中臨時被要求發表看法，但你事先沒有準備。你會？',
                'options' => [
                    ['key' => 'a', 'label' => '憑直覺說出當下的判斷，再補充說明', 'scores' => ['DRIVER' => 2, 'EXPRESSIVE' => 2]],
                    ['key' => 'b', 'label' => '誠實表示需要一點時間整理思緒', 'scores' => ['ANALYTICAL' => 3, 'AMIABLE' => 1]],
                    ['key' => 'c', 'label' => '先複述問題確認自己理解，再給出初步想法', 'scores' => ['NEGOTIATOR' => 2, 'ANALYTICAL' => 2]],
                    ['key' => 'd', 'label' => '反問在場其他人的看法，讓討論更豐富後再發言', 'scores' => ['SUPPORTER' => 2, 'AMIABLE' => 1, 'NEGOTIATOR' => 1]],
                ],
            ],
            [
                'body' => '你認為什麼樣的工作回饋方式對你幫助最大？',
                'options' => [
                    ['key' => 'a', 'label' => '直接、具體、聚焦在結果與改善方向', 'scores' => ['DRIVER' => 3, 'ANALYTICAL' => 1]],
                    ['key' => 'b', 'label' => '先肯定努力，再以鼓勵的方式提出建議', 'scores' => ['EXPRESSIVE' => 2, 'AMIABLE' => 2]],
                    ['key' => 'c', 'label' => '給我詳細的書面分析，讓我可以仔細思考', 'scores' => ['ANALYTICAL' => 3, 'SUPPORTER' => 1]],
                    ['key' => 'd', 'label' => '在輕鬆的環境下非正式地交流，比較自在', 'scores' => ['AMIABLE' => 2, 'EXPRESSIVE' => 1, 'NEGOTIATOR' => 1]],
                ],
            ],
            [
                'body' => '如果可以自由選擇，你最喜歡在職場中扮演哪種角色？',
                'options' => [
                    ['key' => 'a', 'label' => '決策者：負責拍板，推動事情往前走', 'scores' => ['DRIVER' => 4]],
                    ['key' => 'b', 'label' => '倡議者：發掘機會，說服大家相信新的可能', 'scores' => ['EXPRESSIVE' => 2, 'VISIONARY' => 2]],
                    ['key' => 'c', 'label' => '執行者：負責把計畫落實，確保品質與細節', 'scores' => ['ANALYTICAL' => 2, 'SUPPORTER' => 2]],
                    ['key' => 'd', 'label' => '橋樑：連結不同的人與資源，讓合作順暢', 'scores' => ['NEGOTIATOR' => 2, 'AMIABLE' => 2]],
                ],
            ],
        ];

        foreach ($questions as $index => $question) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'body' => $question['body'],
                'type' => 'single_choice',
                'options' => $question['options'],
                'sort_order' => $index + 1,
                'is_required' => true,
            ]);
        }
    }
}
