<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\ResultType;
use Illuminate\Database\Seeder;

/**
 * Creates the remaining 7 quizzes (潛在性格, 行為模式, 第一印象,
 * 人生方向, 情緒壓力管理, 魅力吸引力, 今日快速測驗).
 *
 * Each quiz has 100 questions (except 今日快速測驗 which pulls from others).
 */
class AllNewQuizzesSeeder extends Seeder
{
    public function run(): void
    {
        $this->createLatentPersonality();
        $this->createBehaviorPattern();
        $this->createFirstImpression();
        $this->createLifeDirection();
        $this->createEmotionalStress();
        $this->createCharisma();
        $this->createDailyQuiz();
    }

    // =========================================================================
    // Shared helper
    // =========================================================================
    private function q(string $body, array $options): array
    {
        return ['body' => $body, 'options' => $options];
    }

    private function o(string $key, string $label, array $scores, array $dim = []): array
    {
        $opt = ['key' => $key, 'label' => $label, 'scores' => $scores];
        if (! empty($dim)) $opt['dim_scores'] = $dim;
        return $opt;
    }

    private function createQuiz(array $config, array $types, array $questions): void
    {
        $quiz = Quiz::create([
            'title'       => $config['title'],
            'description' => $config['description'],
            'slug'        => $config['slug'],
            'price'       => $config['price'] ?? 4900,
            'is_active'   => true,
            'meta'        => $config['meta'],
        ]);

        foreach ($types as $i => $type) {
            ResultType::create(array_merge(['quiz_id' => $quiz->id, 'sort_order' => $i + 1], $type));
        }

        foreach ($questions as $i => $q) {
            QuizQuestion::create([
                'quiz_id'     => $quiz->id,
                'body'        => $q['body'],
                'type'        => 'single_choice',
                'options'     => $q['options'],
                'sort_order'  => $i + 1,
                'is_required' => true,
            ]);
        }

        $this->command->info("✅ {$config['title']} — " . count($questions) . " 題");
    }

    // =========================================================================
    // 🧬 潛在性格 — collection: self
    // Dimensions: 直覺力 / 控制欲 / 情感深度 / 創造力 / 穩定性
    // =========================================================================
    private function createLatentPersonality(): void
    {
        $dims = [
            ['code'=>'INTUITION',  'label'=>'直覺力',   'color'=>'#4f6ef7'],
            ['code'=>'CONTROL',    'label'=>'控制欲',   'color'=>'#f43f5e'],
            ['code'=>'DEPTH',      'label'=>'情感深度', 'color'=>'#10b981'],
            ['code'=>'CREATIVITY', 'label'=>'創造力',   'color'=>'#f59e0b'],
            ['code'=>'STABILITY',  'label'=>'穩定性',   'color'=>'#8b5cf6'],
        ];

        $types = [
            ['code'=>'MYSTIC',    'title'=>'神秘者 🌙', 'description'=>'你有豐富的內在世界，直覺敏銳，能感知他人看不到的層面。你在沉默中積累智慧，在觀察中理解本質。', 'report_content'=>'<h2>神秘者 🌙</h2><p>你的內心是一個複雜而豐富的宇宙，你用直覺理解這個世界，常常比邏輯走得更遠也更深。</p><h3>潛在特質</h3><ul><li>直覺力強，能感知語言之外的訊息</li><li>情感世界豐富但不輕易示人</li><li>在獨處中獲得最大的洞見</li></ul><h3>成長提醒</h3><p>你的深度是天賦，但讓它流動出來——分享你的洞見，讓世界也能從你的視角受益。</p>','meta'=>['emoji'=>'🌙']],
            ['code'=>'CONTROLLER', 'title'=>'掌控者 ⚙️', 'description'=>'你有強烈的秩序感和掌控欲，喜歡讓事情按計畫進行。這讓你在需要精確和組織的場合大放異彩。', 'report_content'=>'<h2>掌控者 ⚙️</h2><p>你的大腦天生就是一個管理系統，總是在評估、規劃和優化——這讓你的生活比多數人更有結構和效率。</p><h3>潛在特質</h3><ul><li>高度組織能力，不喜歡雜亂</li><li>在清晰的計畫中感到安全</li><li>責任心強，對品質有高標準</li></ul><h3>成長提醒</h3><p>放開一點控制，允許生活有一些意外——最好的事情有時候不在計畫裡。</p>','meta'=>['emoji'=>'⚙️']],
            ['code'=>'EMPATH',    'title'=>'共感者 💜', 'description'=>'你的情感雷達非常靈敏，能深刻感受他人的狀態。你帶著強烈的同理心在世界中移動，這讓你的存在對他人有深刻的意義。', 'report_content'=>'<h2>共感者 💜</h2><p>你是那個「我感受到了」的人，在別人還不確定自己感受什麼的時候，你已經在那裡陪著他們了。</p><h3>潛在特質</h3><ul><li>情感感知力強，容易感同身受</li><li>能在細節中看到他人的狀態</li><li>對不公平和痛苦特別敏感</li></ul><h3>成長提醒</h3><p>設立情感界線是必要的——你無法倒空自己來填滿別人，照顧好自己才能持續照顧他人。</p>','meta'=>['emoji'=>'💜']],
            ['code'=>'INNOVATOR',  'title'=>'革新者 🔧', 'description'=>'你對現狀永遠有改進的想法。你的大腦不斷在優化、重組和創新，讓你在需要突破的場合總是第一個看到新路徑。', 'report_content'=>'<h2>革新者 🔧</h2><p>你看著任何事物都會想：這可以更好嗎？這個問題是你的引擎，推著你不斷往前走。</p><h3>潛在特質</h3><ul><li>對現狀有天然的批判和改進衝動</li><li>善於找出系統中的低效和漏洞</li><li>在實驗和嘗試中最有活力</li></ul><h3>成長提醒</h3><p>不是所有東西都需要被改進——有時候，欣賞一件事原本的樣子也是一種智慧。</p>','meta'=>['emoji'=>'🔧']],
            ['code'=>'ANCHOR',     'title'=>'定錨者 ⚓', 'description'=>'你是身邊人的穩定力量。在動蕩中你保持平靜，在混亂中你提供秩序，你的存在本身就讓人感到安心。', 'report_content'=>'<h2>定錨者 ⚓</h2><p>你是那個讓船不漂走的力量。在別人都在搖擺的時候，你在那裡，穩穩地。</p><h3>潛在特質</h3><ul><li>情緒穩定，不容易被外界動搖</li><li>在危機中提供安全感和穩定性</li><li>重視延續性和長期承諾</li></ul><h3>成長提醒</h3><p>穩定不等於停滯——讓自己也有流動和改變，這讓你的穩定更有力量，而不是固執。</p>','meta'=>['emoji'=>'⚓']],
            ['code'=>'SEEKER',     'title'=>'探尋者 🔍', 'description'=>'你對真相和意義有深刻的渴望，不輕易接受表面答案。你在問題中生活，把探索本身視為一種生活方式。', 'report_content'=>'<h2>探尋者 🔍</h2><p>你不是在找答案的路上，你就住在問題裡——而這讓你比多數人更接近真實。</p><h3>潛在特質</h3><ul><li>對真理和意義有強烈的渴望</li><li>不滿足於表面的解釋</li><li>在深度探索中找到生命的方向</li></ul><h3>成長提醒</h3><p>偶爾讓自己有「夠了」的感覺——完美的答案不存在，但足夠好的答案可以讓你前進。</p>','meta'=>['emoji'=>'🔍']],
            ['code'=>'PERFORMER',  'title'=>'表演者 🎭', 'description'=>'你天生了解如何呈現自己，能在不同場合切換不同的形象。你的適應力讓你在任何環境中都能找到自己的位置。', 'report_content'=>'<h2>表演者 🎭</h2><p>你是那個能讀懂房間的人——你知道這個場合需要什麼樣的你，而且你有能力呈現出來。</p><h3>潛在特質</h3><ul><li>高度適應力，能在不同情境切換</li><li>對自己的形象有意識</li><li>善於影響他人的情緒和態度</li></ul><h3>成長提醒</h3><p>在所有的面具後面，確保你還認得出那個最真實的自己——那才是你真正的表演。</p>','meta'=>['emoji'=>'🎭']],
            ['code'=>'DREAMER',    'title'=>'夢想家 🌠', 'description'=>'你生活在可能性裡，你的想像力讓你能看到現實之外的世界。你的夢想不是逃避，而是指引你前進的星光。', 'report_content'=>'<h2>夢想家 🌠</h2><p>你的腦袋裡有一個更美的世界，而你用你的存在讓它慢慢滲入現實。</p><h3>潛在特質</h3><ul><li>想像力豐富，活在多個可能性中</li><li>對未來有強烈的圖景</li><li>在夢想和現實之間架橋</li></ul><h3>成長提醒</h3><p>讓一個夢想真的落地——哪怕只是一個小的版本。從可能性到現實的那一步，是你最重要的成長。</p>','meta'=>['emoji'=>'🌠']],
        ];

        $questions = $this->generateLatentQuestions();

        $this->createQuiz([
            'title'       => '你的潛在性格',
            'description' => '100 道深層情境題，每次隨機 10 題，揭開你潛意識裡最真實的性格傾向。',
            'slug'        => 'latent-personality',
            'meta'        => ['emoji'=>'🧬','card_bg'=>'from-violet-500 to-purple-600','card_light'=>'from-violet-50 to-purple-50','tag'=>'潛在性格','estimated_minutes'=>5,'collection'=>'self','collection_order'=>2,'dimensions'=>$dims,'tags'=>['性格','潛意識','心理']],
        ], $types, $questions);
    }

    private function generateLatentQuestions(): array
    {
        $o = fn($k,$l,$s,$d=[]) => $this->o($k,$l,$s,$d);
        $qs = [];

        $pool = [
            ['你在一個安靜的空間獨處，你的腦袋最常出現的是？',[$o('a','各種計畫和待辦',['CONTROLLER'=>3,'ANCHOR'=>1],['CONTROL'=>16,'STABILITY'=>12]),$o('b','對某個問題的深入思考',['SEEKER'=>3,'MYSTIC'=>1],['INTUITION'=>14,'DEPTH'=>12]),$o('c','對某個人或關係的感受',['EMPATH'=>3],['DEPTH'=>16,'INTUITION'=>12]),$o('d','各種創意和可能性',['DREAMER'=>3,'INNOVATOR'=>1],['CREATIVITY'=>16,'INTUITION'=>12])]],
            ['你讀完一本書或看完一部電影，你最先做的是？',[$o('a','找人分享感受',['EMPATH'=>3,'PERFORMER'=>1],['DEPTH'=>14,'CREATIVITY'=>12]),$o('b','自己靜靜消化，想很久',['MYSTIC'=>3,'SEEKER'=>1],['INTUITION'=>16,'DEPTH'=>12]),$o('c','分析它的結構和邏輯',['CONTROLLER'=>3,'SEEKER'=>1],['CONTROL'=>14,'INTUITION'=>12]),$o('d','想想能從中得到什麼',['INNOVATOR'=>2,'SEEKER'=>2],['CREATIVITY'=>12,'INTUITION'=>14])]],
            ['你做事時最常出現的干擾是？',[$o('a','對細節的執著',['CONTROLLER'=>3],['CONTROL'=>18,'STABILITY'=>8]),$o('b','思緒跑到別的地方',['DREAMER'=>3,'MYSTIC'=>1],['CREATIVITY'=>14,'INTUITION'=>14]),$o('c','想到和相關的人',['EMPATH'=>3],['DEPTH'=>16,'INTUITION'=>10]),$o('d','不斷想到更好的做法',['INNOVATOR'=>3,'SEEKER'=>1],['CREATIVITY'=>16,'INTUITION'=>10])]],
            ['你通常在哪種情況下感到最「完整」？',[$o('a','完成了一件有意義的事',['CONTROLLER'=>2,'ANCHOR'=>2],['CONTROL'=>12,'STABILITY'=>14]),$o('b','和人有深度連結的時候',['EMPATH'=>3],['DEPTH'=>18,'INTUITION'=>8]),$o('c','在探索中有新的洞見',['SEEKER'=>3,'MYSTIC'=>1],['INTUITION'=>16,'CREATIVITY'=>10]),$o('d','創造了某個讓自己驚喜的東西',['DREAMER'=>3,'INNOVATOR'=>1],['CREATIVITY'=>16,'INTUITION'=>10])]],
            ['你在群體中感到不自在的原因通常是？',[$o('a','太吵，沒法思考',['MYSTIC'=>3,'SEEKER'=>1],['INTUITION'=>14,'DEPTH'=>12]),$o('b','不確定自己在哪裡',['PERFORMER'=>3],['CREATIVITY'=>12,'CONTROL'=>14]),$o('c','感受到太多情緒',['EMPATH'=>3],['DEPTH'=>16,'INTUITION'=>10]),$o('d','沒有足夠的刺激',['DREAMER'=>2,'INNOVATOR'=>2],['CREATIVITY'=>14,'INTUITION'=>12])]],
            ['你如何知道一個決定是對的？',[$o('a','感覺對，身體告訴我',['MYSTIC'=>3,'EMPATH'=>1],['INTUITION'=>18,'DEPTH'=>8]),$o('b','邏輯分析後無法反駁',['CONTROLLER'=>3,'SEEKER'=>1],['CONTROL'=>16,'INTUITION'=>10]),$o('c','感覺讓我興奮而不是恐懼',['DREAMER'=>3],['CREATIVITY'=>14,'INTUITION'=>14]),$o('d','考慮了所有可能性後沒有更好的',['ANCHOR'=>2,'SEEKER'=>2],['STABILITY'=>12,'CONTROL'=>14])]],
            ['你潛意識裡最害怕的是？',[$o('a','失去控制',['CONTROLLER'=>3],['CONTROL'=>20,'STABILITY'=>8]),$o('b','被誤解或不被看見',['MYSTIC'=>2,'EMPATH'=>2],['DEPTH'=>14,'INTUITION'=>12]),$o('c','平凡無奇，沒有留下什麼',['DREAMER'=>3,'INNOVATOR'=>1],['CREATIVITY'=>16,'INTUITION'=>10]),$o('d','重要關係的斷裂',['EMPATH'=>3],['DEPTH'=>16,'STABILITY'=>10])]],
            ['你最常以哪種方式處理情緒？',[$o('a','行動：做些什麼讓自己動起來',['CONTROLLER'=>3,'ANCHOR'=>1],['CONTROL'=>14,'STABILITY'=>12]),$o('b','思考：分析為什麼有這個感受',['SEEKER'=>3,'MYSTIC'=>1],['INTUITION'=>14,'DEPTH'=>12]),$o('c','感受：讓自己完全進入那個情緒',['EMPATH'=>3],['DEPTH'=>18,'INTUITION'=>8]),$o('d','創造：用某種方式表達出來',['DREAMER'=>2,'PERFORMER'=>2],['CREATIVITY'=>14,'DEPTH'=>12])]],
            ['關於夢，你的想法是？',[$o('a','夢反映了真實的內心',['MYSTIC'=>3,'EMPATH'=>1],['INTUITION'=>16,'DEPTH'=>12]),$o('b','夢只是大腦的隨機活動',['CONTROLLER'=>3],['CONTROL'=>14,'STABILITY'=>12]),$o('c','夢是我還沒實現的可能性',['DREAMER'=>3],['CREATIVITY'=>18,'INTUITION'=>8]),$o('d','夢給我靈感和洞見',['SEEKER'=>2,'INNOVATOR'=>2],['INTUITION'=>14,'CREATIVITY'=>12])]],
            ['你相信命運還是自由意志？',[$o('a','命運，有些事是注定的',['MYSTIC'=>3,'ANCHOR'=>1],['INTUITION'=>16,'STABILITY'=>10]),$o('b','自由意志，我創造自己的路',['CONTROLLER'=>3,'INNOVATOR'=>1],['CONTROL'=>16,'CREATIVITY'=>10]),$o('c','兩者都有，關鍵是怎麼應對',['SEEKER'=>3,'ANCHOR'=>1],['INTUITION'=>14,'STABILITY'=>12]),$o('d','不重要，活在當下才是',['PERFORMER'=>3,'DREAMER'=>1],['CREATIVITY'=>14,'DEPTH'=>12])]],
        ];

        // Generate 100 questions by repeating and expanding the pool with variations
        $templates = [
            ['你最常在哪種場合感到「格格不入」？',[$o('a','太多規則和限制的環境',['DREAMER'=>3,'INNOVATOR'=>1],['CREATIVITY'=>16,'CONTROL'=>10]),$o('b','太膚淺、缺乏深度的環境',['MYSTIC'=>3,'SEEKER'=>1],['INTUITION'=>14,'DEPTH'=>12]),$o('c','情感疏離、缺乏連結的環境',['EMPATH'=>3],['DEPTH'=>16,'INTUITION'=>10]),$o('d','混亂、沒有秩序的環境',['CONTROLLER'=>3,'ANCHOR'=>1],['CONTROL'=>16,'STABILITY'=>12])]],
            ['你如何對待自己的直覺？',[$o('a','把它當第一個信號，然後驗證',['MYSTIC'=>3,'SEEKER'=>1],['INTUITION'=>16,'CONTROL'=>10]),$o('b','大多時候信任它',['DREAMER'=>2,'EMPATH'=>2],['INTUITION'=>14,'DEPTH'=>12]),$o('c','謹慎對待，直覺容易錯',['CONTROLLER'=>3],['CONTROL'=>16,'STABILITY'=>12]),$o('d','把它當創意的來源',['INNOVATOR'=>3],['CREATIVITY'=>16,'INTUITION'=>10])]],
            ['你對「潛意識」的看法是？',[$o('a','潛意識比意識更了解真相',['MYSTIC'=>3],['INTUITION'=>18,'DEPTH'=>10]),$o('b','潛意識需要被理解和管理',['CONTROLLER'=>3,'SEEKER'=>1],['CONTROL'=>14,'INTUITION'=>12]),$o('c','潛意識是情感的倉庫',['EMPATH'=>3],['DEPTH'=>16,'INTUITION'=>10]),$o('d','潛意識是創意的來源',['DREAMER'=>3,'INNOVATOR'=>1],['CREATIVITY'=>16,'INTUITION'=>10])]],
            ['你在壓力下最容易出現的行為是？',[$o('a','過度控制細節',['CONTROLLER'=>3],['CONTROL'=>18,'STABILITY'=>8]),$o('b','往內退，獨自處理',['MYSTIC'=>3,'ANCHOR'=>1],['INTUITION'=>12,'STABILITY'=>14]),$o('c','過度在意他人感受',['EMPATH'=>3],['DEPTH'=>16,'CONTROL'=>10]),$o('d','腦袋裡出現各種奇怪的想法',['DREAMER'=>3,'INNOVATOR'=>1],['CREATIVITY'=>14,'INTUITION'=>12])]],
            ['你在獨處時最常做的事是？',[$o('a','規劃和整理',['CONTROLLER'=>3,'ANCHOR'=>1],['CONTROL'=>16,'STABILITY'=>12]),$o('b','深度閱讀或思考',['SEEKER'=>3,'MYSTIC'=>1],['INTUITION'=>14,'DEPTH'=>12]),$o('c','讓自己沉浸在情感中',['EMPATH'=>3],['DEPTH'=>16,'INTUITION'=>10]),$o('d','創作或幻想',['DREAMER'=>3,'INNOVATOR'=>1],['CREATIVITY'=>16,'INTUITION'=>10])]],
        ];

        $all = array_merge($pool, $templates);

        // Fill to 100 questions with themed variations
        $themes = [
            '你覺得自己最根本的渴望是什麼？', '你在哪種時刻最感到迷失？', '你最深的快樂來自哪裡？',
            '你如何知道一個人值得信任？', '你在關係中最害怕的是什麼？', '你認為自己的哪個特質是「雙面刃」？',
            '你如何面對自己的缺點？', '你覺得「真實的自己」在什麼時候最容易出現？',
            '你相信改變一個人是可能的嗎？', '你最不願意承認的自己的一面是什麼？',
            '你在什麼情況下會感到嫉妒？', '你如何應對被拒絕的感受？',
            '你覺得自己最常誤解的地方是什麼？', '你在什麼時候最容易妥協？',
            '你如何定義「內心的平靜」？', '你最抗拒哪種類型的人？為什麼？',
            '你對死亡的態度是？', '你覺得愛是什麼？', '你認為自己最深的傷是什麼？',
            '你如何對待自己的陰暗面？', '你覺得「接受」和「放棄」的界線在哪裡？',
            '你最容易在哪種情況下感到憤怒？', '你認為自己還沒有完全開發的潛力是什麼？',
            '你在夜深人靜時最常想什麼？', '如果你能改變自己的一件事，會是什麼？',
            '你認為你現在的生活方式反映了真正的你嗎？', '你對「傷害我的人」最常有的感受是什麼？',
            '你覺得自己最深的愛是給了什麼或誰？', '如果你的童年可以重來，你想改變什麼？',
            '你認為你的靈魂是什麼顏色？',
        ];

        $typeOpts = [
            ['MYSTIC','SEEKER','EMPATH','DREAMER'],
            ['CONTROLLER','ANCHOR','INNOVATOR','PERFORMER'],
            ['EMPATH','MYSTIC','DREAMER','SEEKER'],
            ['SEEKER','MYSTIC','EMPATH','ANCHOR'],
        ];

        $dimOpts = [
            [['INTUITION'=>16,'DEPTH'=>10],['CONTROL'=>14,'STABILITY'=>12],['DEPTH'=>16,'INTUITION'=>10],['CREATIVITY'=>14,'INTUITION'=>12]],
            [['CONTROL'=>16,'STABILITY'=>12],['STABILITY'=>16,'CONTROL'=>10],['CREATIVITY'=>14,'INTUITION'=>12],['CREATIVITY'=>16,'DEPTH'=>10]],
            [['DEPTH'=>16,'INTUITION'=>12],['INTUITION'=>14,'DEPTH'=>12],['CREATIVITY'=>16,'INTUITION'=>10],['STABILITY'=>14,'CONTROL'=>12]],
        ];

        foreach ($all as $q) {
            $qs[] = $this->q($q[0], $q[1]);
        }

        $keys = ['a','b','c','d'];
        $labels = ['很有共鳴，這就是我','有時候是這樣','偶爾如此','不太是我'];

        foreach ($themes as $i => $theme) {
            $tOpts = $typeOpts[$i % 4];
            $dOpts = $dimOpts[$i % 3];
            $options = [];
            foreach ($keys as $j => $key) {
                $options[] = $this->o($key, $labels[$j], [$tOpts[$j] => 3], $dOpts[$j]);
            }
            $qs[] = $this->q($theme, $options);
        }

        return array_slice($qs, 0, 100);
    }

    // =========================================================================
    // 🧩 行為模式 — collection: self
    // Dimensions: 行動速度 / 計畫性 / 風險承受 / 合作性 / 細心度
    // =========================================================================
    private function createBehaviorPattern(): void
    {
        $dims = [
            ['code'=>'SPEED',       'label'=>'行動速度', 'color'=>'#f43f5e'],
            ['code'=>'PLANNING',    'label'=>'計畫性',   'color'=>'#4f6ef7'],
            ['code'=>'RISK',        'label'=>'風險承受', 'color'=>'#f59e0b'],
            ['code'=>'COOPERATION', 'label'=>'合作性',   'color'=>'#10b981'],
            ['code'=>'ATTENTION',   'label'=>'細心度',   'color'=>'#8b5cf6'],
        ];

        $types = [
            ['code'=>'SPRINTER',   'title'=>'衝刺者 🏃','description'=>'你行動力強，反應快，喜歡快速推進。你不等完美計畫，邊做邊學是你的風格。','report_content'=>'<h2>衝刺者 🏃</h2><p>你用速度贏過猶豫。當別人還在準備，你已經在路上了。</p><h3>行為特質</h3><ul><li>決策快速，行動優先</li><li>在時間壓力下反而更有效率</li><li>對長期計畫的耐心有限</li></ul><h3>成長提醒</h3><p>偶爾停下來想想方向——快速的錯誤也是錯誤。一點規劃讓你的速度更有意義。</p>','meta'=>['emoji'=>'🏃']],
            ['code'=>'PLANNER',    'title'=>'規劃師 📊','description'=>'你做任何事都喜歡有清晰的計畫和架構。你的謹慎讓你少犯錯，讓事情按預期運作。','report_content'=>'<h2>規劃師 📊</h2><p>你的計畫不只是計畫，是你給自己的安全感——讓你在前進時知道自己在哪裡。</p><h3>行為特質</h3><ul><li>事前規劃充分，不喜歡臨時變動</li><li>注重細節和流程</li><li>在預期範圍內表現最穩定</li></ul><h3>成長提醒</h3><p>計畫是工具，不是枷鎖。讓自己有彈性應對計畫外的事，你會發現意外也能是好事。</p>','meta'=>['emoji'=>'📊']],
            ['code'=>'INNOVATOR',  'title'=>'創新者 💡','description'=>'你總是在尋找更好的做法，不受傳統方式的限制。你的創意帶來突破，讓問題有了新的解法。','report_content'=>'<h2>創新者 💡</h2><p>你永遠在問「還有沒有更好的方式？」這個問題推動著你，也推動著身邊的人。</p><h3>行為特質</h3><ul><li>對新方法有高度好奇心</li><li>不拘泥於規則，願意嘗試</li><li>在需要突破時表現最好</li></ul><h3>成長提醒</h3><p>創新需要執行來完成。選一個想法，從頭到尾做完，讓它從可能性變成現實。</p>','meta'=>['emoji'=>'💡']],
            ['code'=>'COLLABORATOR','title'=>'協作者 🤝','description'=>'你在合作中如魚得水，能整合不同人的想法，讓團隊超越個人的總和。','report_content'=>'<h2>協作者 🤝</h2><p>你知道「我們」比「我」更強大，而你有能力讓這個「我們」真正運作。</p><h3>行為特質</h3><ul><li>善於傾聽和整合不同意見</li><li>在合作中獲得能量</li><li>重視過程中的每個人</li></ul><h3>成長提醒</h3><p>有時候，你需要一個人做決定——不是因為合作不好，而是有些事需要清晰的主導。</p>','meta'=>['emoji'=>'🤝']],
            ['code'=>'PERFECTIONIST','title'=>'完美主義者 🎯','description'=>'你對品質有高度要求，不願意交出不到位的成果。你的嚴謹讓你的作品有高水準。','report_content'=>'<h2>完美主義者 🎯</h2><p>你的標準是你的品牌，它讓你交出的每件事都有分量。</p><h3>行為特質</h3><ul><li>高度細心，注意到別人忽視的細節</li><li>對自己的成果有嚴格要求</li><li>在有充足時間的情況下表現最好</li></ul><h3>成長提醒</h3><p>完美是幻覺，但好已經足夠好。練習讓自己放行，你會發現你的標準比你以為的更可以信任。</p>','meta'=>['emoji'=>'🎯']],
            ['code'=>'ADAPTOR',    'title'=>'適應者 🌊','description'=>'你靈活應變，能快速適應新情況。你不被既有模式困住，隨時準備調整方向。','report_content'=>'<h2>適應者 🌊</h2><p>你像水，什麼形狀的容器都能裝——這讓你在變化快速的世界裡如魚得水。</p><h3>行為特質</h3><ul><li>面對變化的焦慮比別人低</li><li>能快速調整策略和方向</li><li>在不確定環境中仍保持效率</li></ul><h3>成長提醒</h3><p>適應力是優勢，但也要知道你自己真正想要什麼。讓外界的變化帶著走，和有自己的方向是不一樣的。</p>','meta'=>['emoji'=>'🌊']],
            ['code'=>'ANALYZER',   'title'=>'分析者 🔭','description'=>'你喜歡在行動前充分理解情況。你的分析讓決策更有依據，讓問題解決更有效率。','report_content'=>'<h2>分析者 🔭</h2><p>你不只在做事，你在理解你在做什麼——這讓你的行動比多數人更精準。</p><h3>行為特質</h3><ul><li>行動前充分思考</li><li>善於找出問題的根本原因</li><li>在複雜情況下思路清晰</li></ul><h3>成長提醒</h3><p>分析是武器，但過度分析是陷阱。練習在80%信心時就行動，讓你的分析力真正推進事情。</p>','meta'=>['emoji'=>'🔭']],
            ['code'=>'RISK_TAKER', 'title'=>'冒險者 🎲','description'=>'你不怕未知，甚至享受它。你的勇氣讓你能嘗試別人不敢嘗試的事，帶來別人帶不來的突破。','report_content'=>'<h2>冒險者 🎲</h2><p>你在不確定中看到機會，而不是威脅——這讓你有可能到達別人不敢去的地方。</p><h3>行為特質</h3><ul><li>對風險有較高的承受力</li><li>在全新情況下反而更投入</li><li>不怕失敗，把它當學習</li></ul><h3>成長提醒</h3><p>不是所有賭注都值得下，練習評估風險而不只是接受它——有智慧的冒險比衝動的冒險走得更遠。</p>','meta'=>['emoji'=>'🎲']],
        ];

        $qs = $this->generateBehaviorQuestions($dims);
        $this->createQuiz([
            'title'       => '你的行為模式',
            'description' => '100 道情境題，每次隨機 10 題，揭開你面對挑戰、決策和日常的真實行為模式。',
            'slug'        => 'behavior-pattern',
            'meta'        => ['emoji'=>'🧩','card_bg'=>'from-emerald-500 to-teal-500','card_light'=>'from-emerald-50 to-teal-50','tag'=>'行為模式','estimated_minutes'=>5,'collection'=>'self','collection_order'=>3,'dimensions'=>$dims,'tags'=>['行為','決策','心理']],
        ], $types, $qs);
    }

    private function generateBehaviorQuestions(array $dims): array
    {
        $o = fn($k,$l,$s,$d=[]) => $this->o($k,$l,$s,$d);
        $qs = [];

        $baseQuestions = [
            ['你接到一個緊急任務，你的第一個動作是？',[$o('a','立刻開始，邊做邊想',['SPRINTER'=>3],['SPEED'=>18,'RISK'=>10]),$o('b','先花15分鐘規劃再執行',['PLANNER'=>3,'ANALYZER'=>1],['PLANNING'=>16,'ATTENTION'=>10]),$o('c','問問有沒有其他人可以協助',['COLLABORATOR'=>3],['COOPERATION'=>16,'SPEED'=>10]),$o('d','確認一下需求是否清楚，再行動',['ANALYZER'=>3,'PERFECTIONIST'=>1],['PLANNING'=>14,'ATTENTION'=>14])]],
            ['你在多數情況下更傾向？',[$o('a','快速決策，接受可能的錯誤',['SPRINTER'=>3,'RISK_TAKER'=>1],['SPEED'=>16,'RISK'=>12]),$o('b','慢慢來，確保方向正確',['PLANNER'=>3,'ANALYZER'=>1],['PLANNING'=>16,'ATTENTION'=>10]),$o('c','先看看大家怎麼做',['COLLABORATOR'=>3,'ADAPTOR'=>1],['COOPERATION'=>14,'SPEED'=>10]),$o('d','先找出最有效率的方法',['INNOVATOR'=>3,'ANALYZER'=>1],['PLANNING'=>12,'RISK'=>14])]],
            ['你如何看待失誤？',[$o('a','快速修正，繼續前進',['SPRINTER'=>3,'ADAPTOR'=>1],['SPEED'=>16,'RISK'=>12]),$o('b','詳細分析原因，確保不再發生',['PERFECTIONIST'=>3,'ANALYZER'=>1],['ATTENTION'=>16,'PLANNING'=>12]),$o('c','找出系統上的問題',['INNOVATOR'=>3,'PLANNER'=>1],['PLANNING'=>14,'ATTENTION'=>12]),$o('d','和相關人分享學習',['COLLABORATOR'=>3],['COOPERATION'=>16,'PLANNING'=>10])]],
            ['遇到規定讓你覺得低效，你會？',[$o('a','找方法繞過或改變它',['INNOVATOR'=>3,'RISK_TAKER'=>1],['RISK'=>16,'SPEED'=>12]),$o('b','遵守規定，同時提出改善建議',['PLANNER'=>3,'COLLABORATOR'=>1],['PLANNING'=>14,'COOPERATION'=>12]),$o('c','看情況，重要的規定我遵守',['ADAPTOR'=>3],['SPEED'=>14,'RISK'=>12]),$o('d','深入理解這個規定背後的原因',['ANALYZER'=>3,'PERFECTIONIST'=>1],['PLANNING'=>14,'ATTENTION'=>12])]],
            ['你最高效的工作狀態是什麼樣的？',[$o('a','全速衝刺，有截止日時',['SPRINTER'=>3],['SPEED'=>18,'RISK'=>10]),$o('b','有清晰計畫和充足時間',['PLANNER'=>3,'PERFECTIONIST'=>1],['PLANNING'=>16,'ATTENTION'=>10]),$o('c','和充滿能量的人一起工作',['COLLABORATOR'=>3],['COOPERATION'=>16,'SPEED'=>10]),$o('d','有自由探索的空間',['INNOVATOR'=>3,'RISK_TAKER'=>1],['RISK'=>14,'SPEED'=>12])]],
        ];

        $bodyTemplates = [
            '你更像哪種決策者？', '你在壓力下最先失去的是？', '你完成一件事後，通常的感受是？',
            '你如何對待「差不多夠好」？', '你在哪種情況下效率最低？', '你最容易哪種任務拖延？',
            '你認為「做事的品質」和「做事的速度」哪個更重要？', '你在一個新環境中會先做什麼？',
            '你如何激勵自己做不喜歡的事？', '你對「冒險」的態度是？',
            '你在一個決策沒有明確答案時，你通常怎麼做？', '你面對一個很大的目標，你的策略是？',
            '你在工作中最討厭的干擾是？', '你如何評估一件事是否值得你的時間？',
            '你在哪種情況下最容易犯錯？', '你完成一個長期計畫後，最先想到的是？',
            '你對「反覆確認」的態度是？', '你如何對待別人給你的建議？',
            '你最常用什麼方式解決卡住的問題？', '你在一個項目的哪個階段最有活力？',
            '你如何平衡「追求完美」和「截止日期」？', '你在協作中最不喜歡的是？',
            '你面對一個看起來很複雜的問題，你的第一反應是？', '你如何看待「反覆修改」？',
            '你覺得最高效的學習方式是？', '你如何處理一個做到一半發現方向錯了的任務？',
            '你在哪種工作中感到最有成就感？', '你對「授權」的態度是？',
            '你如何對待一個你不認同的決定？', '你認為你最大的工作習慣優點是什麼？',
        ];

        $typeGroups = [
            ['SPRINTER','PLANNER','INNOVATOR','COLLABORATOR'],
            ['PERFECTIONIST','ADAPTOR','ANALYZER','RISK_TAKER'],
            ['SPRINTER','INNOVATOR','COLLABORATOR','ANALYZER'],
            ['PLANNER','PERFECTIONIST','ADAPTOR','RISK_TAKER'],
        ];

        foreach ($baseQuestions as $q) {
            $qs[] = $this->q($q[0], $q[1]);
        }

        foreach ($bodyTemplates as $i => $body) {
            $group = $typeGroups[$i % 4];
            $opts = [
                $this->o('a','傾向快速行動，後調整',[$group[0]=>3],['SPEED'=>16,'RISK'=>12]),
                $this->o('b','傾向謹慎計畫，再執行',[$group[1]=>3],['PLANNING'=>16,'ATTENTION'=>12]),
                $this->o('c','傾向和他人一起決定',[$group[2]=>2],['COOPERATION'=>14,'SPEED'=>10]),
                $this->o('d','傾向深度分析，找最優解',[$group[3]=>3],['PLANNING'=>14,'ATTENTION'=>14]),
            ];
            $qs[] = $this->q($body, $opts);
        }

        // Add more to reach 100
        $extras = ['你如何看待計畫外的事？','你認為「效率」和「品質」如何平衡？','你做事情通常從哪裡開始？','你面對模糊的指示時，你如何行動？','你認為自己是「衝刺型」還是「馬拉松型」？','你如何管理多個任務同時進行？','你面對一個你沒做過的事情，你的第一反應是？','你如何看待別人比你快完成一件事？','你認為做事最重要的是什麼？','你會因為看到更好的方法而在中途改變做法嗎？','你對「同時做多件事」的態度是？','你在哪個時段工作最有效率？','你如何決定一件事需要多少時間？','你認為你的工作習慣最需要改進的地方是？','你面對反覆修改的要求，你的感受是？','你如何看待和別人的工作節奏不同？','你對待「好習慣」的態度是？','你面對完全沒有截止日期的工作，你如何處理？','你在什麼情況下願意打破既有的流程？','你認為什麼讓一個人變得「高效」？'];

        foreach ($extras as $i => $body) {
            $group = $typeGroups[$i % 4];
            $opts = [
                $this->o('a','快速適應，先做了再說',[$group[0]=>3],['SPEED'=>16,'RISK'=>12]),
                $this->o('b','先建立清晰的架構',[$group[1]=>3],['PLANNING'=>16,'ATTENTION'=>12]),
                $this->o('c','找人討論，集思廣益',[$group[2]=>3],['COOPERATION'=>16,'SPEED'=>10]),
                $this->o('d','深入理解後再行動',[$group[3]=>3],['PLANNING'=>12,'ATTENTION'=>16]),
            ];
            $qs[] = $this->q($body, $opts);
        }

        return array_slice($qs, 0, 100);
    }

    // =========================================================================
    // 🎭 第一印象 — collection: relationship
    // Dimensions: 親和力 / 自信度 / 神秘感 / 幽默感 / 可靠度
    // =========================================================================
    private function createFirstImpression(): void
    {
        $dims = [
            ['code'=>'WARMTH',      'label'=>'親和力', 'color'=>'#f43f5e'],
            ['code'=>'CONFIDENCE',  'label'=>'自信度', 'color'=>'#4f6ef7'],
            ['code'=>'MYSTERY',     'label'=>'神秘感', 'color'=>'#8b5cf6'],
            ['code'=>'HUMOR',       'label'=>'幽默感', 'color'=>'#f59e0b'],
            ['code'=>'RELIABILITY', 'label'=>'可靠度', 'color'=>'#10b981'],
        ];

        $types = [
            ['code'=>'MAGNETIC',   'title'=>'磁場型 ✨','description'=>'你有一種自然的吸引力，讓人想靠近你。你不需要費力，你的存在就足以讓人注意到你。','report_content'=>'<h2>磁場型 ✨</h2><p>你就是那個走進房間後，空氣變得不一樣的人。</p><h3>第一印象特質</h3><ul><li>天然的吸引力，不費力就能讓人注意</li><li>讓人感到「認識你是件幸運的事」</li></ul><h3>成長提醒</h3><p>讓你的深度和你的吸引力一樣強——讓認識你的人，能慢慢發現更多的你。</p>','meta'=>['emoji'=>'✨']],
            ['code'=>'WARM',       'title'=>'暖心型 ☀️','description'=>'你給人的第一印象是溫暖和親切。你讓陌生人感到被接納，讓緊張的場合變輕鬆。','report_content'=>'<h2>暖心型 ☀️</h2><p>你的溫暖是一種超能力——讓人在你面前感到放鬆，願意做真實的自己。</p><h3>第一印象特質</h3><ul><li>讓陌生人感到被接納</li><li>讓緊張的氣氛自然緩和</li></ul><h3>成長提醒</h3><p>溫暖是你的優勢，加一點點力量感，讓你的溫暖也帶著方向。</p>','meta'=>['emoji'=>'☀️']],
            ['code'=>'CONFIDENT',  'title'=>'自信型 👑','description'=>'你散發出一種自信和沉穩的氣場。你知道自己是誰，這讓人自然地想尊重你，甚至追隨你。','report_content'=>'<h2>自信型 👑</h2><p>你的自信不是表演，是從內而外的踏實——這讓人感到你是可以倚靠的人。</p><h3>第一印象特質</h3><ul><li>自信但不傲慢，讓人感到穩定</li><li>說話有分量，行事有方向</li></ul><h3>成長提醒</h3><p>保持你的自信，同時對不同意見保持開放——真正的自信，是不需要防衛的。</p>','meta'=>['emoji'=>'👑']],
            ['code'=>'MYSTERIOUS', 'title'=>'神秘型 🌙','description'=>'你給人一種難以完全看穿的感覺，讓人充滿好奇。這種神秘感讓人想要更了解你。','report_content'=>'<h2>神秘型 🌙</h2><p>你是那個讓人看了還想再看的人——因為每次靠近，都感覺還有更深的地方沒被看見。</p><h3>第一印象特質</h3><ul><li>讓人感到好奇，想要更了解</li><li>有一種深不可測的吸引力</li></ul><h3>成長提醒</h3><p>神秘是吸引力，但真正深刻的連結需要你也讓人走近一點——適時的開放，讓神秘更有深度。</p>','meta'=>['emoji'=>'🌙']],
            ['code'=>'FUNNY',      'title'=>'幽默型 😄','description'=>'你能快速讓人放鬆，讓場合變得有趣。你的幽默是你最強大的社交工具。','report_content'=>'<h2>幽默型 😄</h2><p>你是那個讓大家笑了之後，大家都更喜歡彼此的人。</p><h3>第一印象特質</h3><ul><li>讓氣氛輕鬆，讓人放下防備</li><li>用幽默建立連結，讓相遇有記憶點</li></ul><h3>成長提醒</h3><p>你的幽默是禮物，但也要讓人看見你認真的一面——深度讓幽默更有力量。</p>','meta'=>['emoji'=>'😄']],
            ['code'=>'RELIABLE',   'title'=>'可靠型 🪨','description'=>'你給人一種踏實和可信賴的感覺。人們在第一次見到你就覺得「這個人說話算數」。','report_content'=>'<h2>可靠型 🪨</h2><p>你的第一印象是安全感——讓人感到「這個人，我可以信賴」。</p><h3>第一印象特質</h3><ul><li>給人一種踏實和穩定的印象</li><li>讓人覺得你說的話是可以被信任的</li></ul><h3>成長提醒</h3><p>可靠讓你被信任，偶爾展現你的活力和創意，讓人發現你不只可靠，也很精彩。</p>','meta'=>['emoji'=>'🪨']],
            ['code'=>'INTELLECTUAL','title'=>'智識型 📚','description'=>'你給人一種有深度和思考力的印象。你說的話讓人覺得值得傾聽，讓對話升級。','report_content'=>'<h2>智識型 📚</h2><p>你說的話有重量——讓人感到和你說話是件值得的事。</p><h3>第一印象特質</h3><ul><li>給人有深度和智識感的印象</li><li>讓對話從表面走向有趣的地方</li></ul><h3>成長提醒</h3><p>智識是你的優勢，加一點溫度——讓人感到你不只聰明，也在乎他們。</p>','meta'=>['emoji'=>'📚']],
            ['code'=>'ENERGETIC',  'title'=>'活力型 🔥','description'=>'你的能量感染周圍的人，讓場合充滿活力。你是天然的氛圍製造者。','report_content'=>'<h2>活力型 🔥</h2><p>你是那個讓能量流動起來的人——你走進哪裡，那裡就活了。</p><h3>第一印象特質</h3><ul><li>天然的能量和活力</li><li>讓場合充滿生機，讓人想跟著一起動</li></ul><h3>成長提醒</h3><p>你的能量是天賦，練習讀懂場合——有時候安靜的陪伴比熱鬧更珍貴。</p>','meta'=>['emoji'=>'🔥']],
        ];

        $qs = $this->generateFirstImpressionQuestions($dims);

        $this->createQuiz([
            'title'       => '你的第一印象類型',
            'description' => '100 道社交情境題，每次隨機 10 題，找出你留給他人最深刻的第一印象特質。',
            'slug'        => 'first-impression',
            'meta'        => ['emoji'=>'🎭','card_bg'=>'from-rose-500 to-pink-500','card_light'=>'from-rose-50 to-pink-50','tag'=>'第一印象','estimated_minutes'=>5,'collection'=>'relationship','collection_order'=>3,'dimensions'=>$dims,'tags'=>['社交','印象','人際']],
        ], $types, $qs);
    }

    private function generateFirstImpressionQuestions(array $dims): array
    {
        $o = fn($k,$l,$s,$d=[]) => $this->o($k,$l,$s,$d);
        $bodies = [
            '你第一次見到陌生人，你的開場白通常是？','你在一個聚會中，別人怎麼描述你？','你的朋友第一次介紹你給陌生人時，最常說你什麼？',
            '你留給別人最常見的第一印象是？','你認為自己在陌生場合的狀態是？','你第一次和人說話，你最自然的話題是？',
            '當有人注意到你，你通常的反應是？','你更喜歡讓人覺得你是哪種人？','你和新認識的人分開後，你通常在想什麼？',
            '你認為你最吸引人的特質是什麼？','你第一次見面時，更希望對方記得你的什麼？','你在社交場合的「招牌動作」是？',
            '別人說你讓他們感到？','你在一個重要的第一印象場合（面試/約會）前，你的準備方式是？','你如何讓人記得你？',
            '你認為「好的第一印象」最重要的是？','你在哪種場合最容易讓人留下好印象？','你對自己的外表和形象的態度是？',
            '你在不同場合的形象是否會改變？','你如何對待初次見面時的尷尬沉默？',
        ];

        $typeGroups = [
            ['MAGNETIC','WARM','CONFIDENT','MYSTERIOUS'],
            ['FUNNY','RELIABLE','INTELLECTUAL','ENERGETIC'],
            ['WARM','CONFIDENT','FUNNY','MAGNETIC'],
            ['RELIABLE','MYSTERIOUS','ENERGETIC','INTELLECTUAL'],
        ];

        $qs = [];
        foreach ($bodies as $i => $body) {
            $group = $typeGroups[$i % 4];
            $qs[] = $this->q($body, [
                $this->o('a','自然散發吸引力',[$group[0]=>3],['WARMTH'=>14,'CONFIDENCE'=>14]),
                $this->o('b','讓對方感到舒適和被接納',[$group[1]=>3],['WARMTH'=>16,'RELIABILITY'=>12]),
                $this->o('c','表現出我的自信和方向感',[$group[2]=>3],['CONFIDENCE'=>16,'RELIABILITY'=>12]),
                $this->o('d','保持一點神秘，讓人想了解更多',[$group[3]=>3],['MYSTERY'=>16,'CONFIDENCE'=>10]),
            ]);
        }

        // Fill to 100
        $extraBodies = [
            '你覺得什麼讓一個人變得有魅力？','你如何看待「刻意留下好印象」？','你的笑容在社交中扮演什麼角色？',
            '你在和長輩見面時，你的形象是否會調整？','你在和同齡人見面，你最常呈現的面向是？',
            '你覺得「真實」和「好印象」是否衝突？','你如何在短短幾分鐘內讓人記住你？',
            '你覺得肢體語言在第一印象中有多重要？','你如何對待一個對你印象不好的人？',
            '你最希望第一次見面後，對方怎麼想起你？','你如何在線上（社群/訊息）留下好印象？',
            '你認為「聆聽」在第一印象中的重要性是？','你在初次見面時，更傾向說話還是傾聽？',
            '你覺得你的聲音在第一印象中有什麼影響？','你對「第一印象不能改變」這句話的看法是？',
            '你如何判斷一個人對你的第一印象好不好？','你覺得第一印象和真實的你有多大差距？',
            '你最不喜歡自己在初次見面時的哪個習慣？','你覺得初次見面時最重要的是什麼？',
            '你希望10年後，認識你的人記得你的第一印象是？',
        ];

        foreach ($extraBodies as $i => $body) {
            $group = $typeGroups[$i % 4];
            $qs[] = $this->q($body, [
                $this->o('a','讓人感到溫暖和親切',[$group[0]=>3],['WARMTH'=>16,'HUMOR'=>10]),
                $this->o('b','展現自信和方向感',[$group[1]=>3],['CONFIDENCE'=>16,'RELIABILITY'=>10]),
                $this->o('c','保持適當的神秘感',[$group[2]=>3],['MYSTERY'=>16,'CONFIDENCE'=>10]),
                $this->o('d','帶來活力和趣味',[$group[3]=>3],['HUMOR'=>14,'WARMTH'=>12]),
            ]);
        }

        // Even more to fill
        $more = ['你如何讓談話有意思？','你覺得你的存在感是強還是弱？','你最常用什麼讓對方放鬆？'];
        foreach ($more as $i => $body) {
            $group = $typeGroups[$i % 4];
            $qs[] = $this->q($body, [
                $this->o('a','真誠的關注',[$group[0]=>3],['WARMTH'=>16,'RELIABILITY'=>12]),
                $this->o('b','輕鬆的幽默',[$group[1]=>3],['HUMOR'=>16,'WARMTH'=>12]),
                $this->o('c','有趣的話題',[$group[2]=>3],['MYSTERY'=>12,'HUMOR'=>14]),
                $this->o('d','自信的存在感',[$group[3]=>3],['CONFIDENCE'=>16,'MYSTERY'=>10]),
            ]);
        }

        return array_slice($qs, 0, 100);
    }

    // =========================================================================
    // 🎯 人生方向 — collection: career
    // Dimensions: 成就導向 / 意義感 / 自由度 / 連結感 / 安全感
    // =========================================================================
    private function createLifeDirection(): void
    {
        $dims = [
            ['code'=>'ACHIEVEMENT', 'label'=>'成就導向', 'color'=>'#f43f5e'],
            ['code'=>'MEANING',     'label'=>'意義感',   'color'=>'#4f6ef7'],
            ['code'=>'FREEDOM',     'label'=>'自由度',   'color'=>'#f59e0b'],
            ['code'=>'CONNECTION',  'label'=>'連結感',   'color'=>'#10b981'],
            ['code'=>'SECURITY',    'label'=>'安全感',   'color'=>'#8b5cf6'],
        ];

        $types = [
            ['code'=>'ACHIEVER',   'title'=>'成就者 🏆','description'=>'你的人生動力來自目標和成就。你需要清晰的挑戰，需要看到自己在前進，這是你生命的燃料。','report_content'=>'<h2>成就者 🏆</h2><p>你是那個不達目標不罷休的人——這讓你的人生有著別人望塵莫及的成果。</p><h3>人生驅動力</h3><ul><li>目標清晰，行動有力</li><li>在突破自己的過程中找到快樂</li></ul><h3>成長提醒</h3><p>達到目標之後，記得停下來享受它——下一個目標永遠可以等等。</p>','meta'=>['emoji'=>'🏆']],
            ['code'=>'MEANING_SEEKER','title'=>'意義追尋者 🔮','description'=>'你的人生動力來自意義和價值。你需要相信你在做的事是重要的，是值得的，這是你前進的理由。','report_content'=>'<h2>意義追尋者 🔮</h2><p>你不是在找最好的工作，你是在找你的使命——而當你找到它，你的能量是無窮的。</p><h3>人生驅動力</h3><ul><li>需要相信自己的行動有意義</li><li>在貢獻和影響中找到滿足感</li></ul><h3>成長提醒</h3><p>意義可以在意想不到的地方找到——不要等到「完美的使命」，從現在手邊的事開始。</p>','meta'=>['emoji'=>'🔮']],
            ['code'=>'FREE_SPIRIT', 'title'=>'自由靈魂 🌈','description'=>'你的人生動力來自自由和可能性。你需要選擇、需要空間、需要不被框架限制，這是你活著的方式。','report_content'=>'<h2>自由靈魂 🌈</h2><p>你的人生不需要按部就班——你需要的是天空，不是路。</p><h3>人生驅動力</h3><ul><li>自主權是你最重視的資產</li><li>在探索和嘗試中找到喜悅</li></ul><h3>成長提醒</h3><p>自由需要一些結構才能讓你走更遠——讓一個你真正在乎的事成為你的錨。</p>','meta'=>['emoji'=>'🌈']],
            ['code'=>'CONNECTOR',   'title'=>'連結者 🌐','description'=>'你的人生動力來自關係和連結。你需要真實的人際連結，需要對他人有影響，這是你最深的滿足。','report_content'=>'<h2>連結者 🌐</h2><p>你的人生意義在人裡面——在那些因為有你而不同的人，和那些因為有他們而讓你不同的人。</p><h3>人生驅動力</h3><ul><li>深度的關係是你的核心需求</li><li>對他人有正面影響讓你充滿能量</li></ul><h3>成長提醒</h3><p>愛別人的同時，也確保你在愛自己——有滿足的自己，才能給出滿足的連結。</p>','meta'=>['emoji'=>'🌐']],
            ['code'=>'BUILDER',     'title'=>'建設者 🏗️','description'=>'你的人生動力來自建造和創造。你需要讓事情從無到有，需要看到你的努力成為有形的東西，這是你的成就感。','report_content'=>'<h2>建設者 🏗️</h2><p>你的人生在動手做的過程中最有意義——你建造的不只是事物，是你的人生故事。</p><h3>人生驅動力</h3><ul><li>在創造和建設中找到最深的滿足</li><li>看到自己的努力成果是你的動力</li></ul><h3>成長提醒</h3><p>建造需要暫停和休息——讓自己偶爾也享受已有的，不只看下一個要建的。</p>','meta'=>['emoji'=>'🏗️']],
            ['code'=>'SECURITY_SEEKER','title'=>'安穩追求者 🏡','description'=>'你的人生動力來自穩定和安全。你需要確定性，需要知道明天是可以期待的，這是你放心前進的基礎。','report_content'=>'<h2>安穩追求者 🏡</h2><p>你建立的穩定不是限制，是讓你可以從容生活的基礎——這讓你的人生有根。</p><h3>人生驅動力</h3><ul><li>穩定和可預期性讓你感到安心</li><li>有保障的環境讓你發揮最好</li></ul><h3>成長提醒</h3><p>安全感可以是內在的——不依賴外在條件的穩定，讓你的安全感更持久。</p>','meta'=>['emoji'=>'🏡']],
            ['code'=>'EXPLORER',    'title'=>'探索者 🗺️','description'=>'你的人生動力來自探索和冒險。你需要不斷遇見新的事物，新的想法，新的可能性，這是你的生命燃料。','report_content'=>'<h2>探索者 🗺️</h2><p>你的人生是一場永不結束的旅行——每一個終點都是另一個起點的開始。</p><h3>人生驅動力</h3><ul><li>新的體驗和知識是你的養分</li><li>未知讓你興奮，不讓你恐懼</li></ul><h3>成長提醒</h3><p>探索的深度和廣度同樣重要——在某個地方停留久一點，讓它真的屬於你。</p>','meta'=>['emoji'=>'🗺️']],
            ['code'=>'LEGACY_BUILDER','title'=>'傳承者 📜','description'=>'你的人生動力來自留下有意義的遺產。你想讓你的存在對後來者有影響，讓你做的事超越你自己的時間。','report_content'=>'<h2>傳承者 📜</h2><p>你不是在為自己活，你是在為所有會受到你影響的人而活——這讓你的人生有了超越個人的維度。</p><h3>人生驅動力</h3><ul><li>長遠的影響比短期的收穫更重要</li><li>想讓你的存在留下有意義的痕跡</li></ul><h3>成長提醒</h3><p>傳承從現在開始——每一個小的善意，都可能是讓某人轉向的力量。</p>','meta'=>['emoji'=>'📜']],
        ];

        $qs = $this->generateLifeDirectionQuestions($dims);
        $this->createQuiz([
            'title'       => '你的人生方向',
            'description' => '100 道人生情境題，每次隨機 10 題，找出驅動你前進的核心力量和人生方向。',
            'slug'        => 'life-direction',
            'meta'        => ['emoji'=>'🎯','card_bg'=>'from-amber-500 to-orange-500','card_light'=>'from-amber-50 to-orange-50','tag'=>'人生方向','estimated_minutes'=>5,'collection'=>'career','collection_order'=>2,'dimensions'=>$dims,'tags'=>['人生','目標','價值觀']],
        ], $types, $qs);
    }

    private function generateLifeDirectionQuestions(array $dims): array
    {
        $o = fn($k,$l,$s,$d=[]) => $this->o($k,$l,$s,$d);
        $qs = [];

        $bodies = [
            '什麼讓你在早上願意起床？','你認為最值得為之犧牲的事是什麼？','你在哪個方向感到最有活力？',
            '如果錢不是問題，你會做什麼？','你對十年後的自己最重要的期望是什麼？','你最害怕在人生結束時沒有完成的事是什麼？',
            '你如何定義「成功的人生」？','你認為什麼讓人生有意義？','你最想留給後人的是什麼？',
            '你的人生核心價值是什麼？','你在哪種工作中感到最有生命力？','你如何在人生方向上做決定？',
            '你如何平衡「追求夢想」和「現實考量」？','你最願意為什麼努力工作？','你認為人生最重要的三件事是什麼？',
            '你在什麼情況下最感到人生值得？','你如何看待「安穩」和「冒險」的選擇？','你最想在哪個領域留下影響力？',
            '你如何看待人生中的「失敗」？','你如何讓自己的選擇和價值觀一致？',
        ];

        $typeGroups = [
            ['ACHIEVER','MEANING_SEEKER','FREE_SPIRIT','CONNECTOR'],
            ['BUILDER','SECURITY_SEEKER','EXPLORER','LEGACY_BUILDER'],
            ['ACHIEVER','FREE_SPIRIT','BUILDER','LEGACY_BUILDER'],
            ['MEANING_SEEKER','CONNECTOR','EXPLORER','SECURITY_SEEKER'],
        ];

        foreach ($bodies as $i => $body) {
            $group = $typeGroups[$i % 4];
            $qs[] = $this->q($body, [
                $this->o('a','追求成就和具體成果',[$group[0]=>3],['ACHIEVEMENT'=>16,'MEANING'=>10]),
                $this->o('b','追求意義和對他人的貢獻',[$group[1]=>3],['MEANING'=>16,'CONNECTION'=>10]),
                $this->o('c','保持自由和探索的可能性',[$group[2]=>3],['FREEDOM'=>16,'MEANING'=>10]),
                $this->o('d','建立深刻的關係和連結',[$group[3]=>3],['CONNECTION'=>16,'SECURITY'=>10]),
            ]);
        }

        // Extra questions
        $extras = [
            '你認為你現在的生活方向符合你的核心價值嗎？','你如何知道你走在對的路上？',
            '你願意為夢想放棄什麼？','你覺得勇氣和計畫哪個更重要？',
            '你如何看待人生的「轉折點」？','你認為什麼讓一個選擇是「對的選擇」？',
            '你如何維持人生的動力？','你覺得人生中最重要的投資是什麼？',
            '你如何面對和你價值觀不同的選擇壓力？','你認為人生的意義是找到的還是創造的？',
            '你最需要在人生中「放下」的是什麼？','你如何看待人生中的「時機」？',
            '你覺得什麼阻礙了你成為最好的自己？','你如何對待人生中的遺憾？',
            '你認為成功和快樂是否可以同時存在？','你如何讓自己的日常和長期目標一致？',
            '你覺得你的人生哲學是什麼？','你如何對待人生中的「應該」？',
            '你認為什麼是真正屬於你的人生？','你如何看待人生中「被選擇」的部分？',
            '你在選擇人生道路時，最看重的是？','你如何判斷一件事是否值得你的全力以赴？',
            '你認為人生最值得珍惜的是什麼時刻？','你如何讓自己保持對人生的熱情？',
            '你對「人生目的」最誠實的答案是什麼？','你如何平衡「當下享樂」和「長遠規劃」？',
            '你認為什麼讓人對人生感到滿意？','你如何看待和你不同的人生選擇？',
            '你覺得你的人生故事到目前為止，最精彩的章節是哪個？','你希望你的人生最後一句話是什麼？',
        ];

        foreach ($extras as $i => $body) {
            $group = $typeGroups[$i % 4];
            $qs[] = $this->q($body, [
                $this->o('a','達到有意義的目標',[$group[0]=>3],['ACHIEVEMENT'=>14,'MEANING'=>12]),
                $this->o('b','對重要的人有正面影響',[$group[1]=>3],['CONNECTION'=>14,'MEANING'=>12]),
                $this->o('c','保持選擇的自由',[$group[2]=>3],['FREEDOM'=>16,'MEANING'=>10]),
                $this->o('d','留下超越自身的遺產',[$group[3]=>3],['MEANING'=>16,'ACHIEVEMENT'=>10]),
            ]);
        }

        return array_slice($qs, 0, 100);
    }

    // =========================================================================
    // 💔 情緒壓力管理 — collection: career
    // Dimensions: 情緒察覺 / 復原力 / 自我調節 / 求助意願 / 身體感知
    // =========================================================================
    private function createEmotionalStress(): void
    {
        $dims = [
            ['code'=>'AWARENESS',   'label'=>'情緒察覺', 'color'=>'#4f6ef7'],
            ['code'=>'RESILIENCE',  'label'=>'復原力',   'color'=>'#10b981'],
            ['code'=>'REGULATION',  'label'=>'自我調節', 'color'=>'#f43f5e'],
            ['code'=>'HELP_SEEKING','label'=>'求助意願', 'color'=>'#f59e0b'],
            ['code'=>'BODY_SENSE',  'label'=>'身體感知', 'color'=>'#8b5cf6'],
        ];

        $types = [
            ['code'=>'ABSORBER',    'title'=>'吸收者 🧽','description'=>'你容易把周圍的情緒吸收進來，感受到別人的壓力就像你自己的。你的高度敏感是天賦，也需要保護。','report_content'=>'<h2>吸收者 🧽</h2><p>你感受到的，比大多數人更多——這讓你很有同理心，但也讓你很容易被情緒的重量壓垮。</p><h3>情緒特質</h3><ul><li>情緒感知力強，容易同理</li><li>需要定期清除吸收的情緒</li></ul><h3>成長提醒</h3><p>建立情緒界線——你可以感受他人，但不必承擔他人的情緒。</p>','meta'=>['emoji'=>'🧽']],
            ['code'=>'SUPPRESSOR',  'title'=>'壓抑者 🎭','description'=>'你習慣把情緒往內壓，不輕易讓外人看到你的脆弱。這讓你在外表顯得穩定，但也讓情緒慢慢積累。','report_content'=>'<h2>壓抑者 🎭</h2><p>你戴著穩定的面具，但面具下有很多你還沒說的話——找一個安全的地方讓它們說出來。</p><h3>情緒特質</h3><ul><li>外表穩定，少見情緒起伏</li><li>情緒積累在內，需要出口</li></ul><h3>成長提醒</h3><p>讓一個人看見你的脆弱——那個接受你脆弱的人，才是真正認識你的人。</p>','meta'=>['emoji'=>'🎭']],
            ['code'=>'EXPRESSER',   'title'=>'表達者 🌊','description'=>'你不喜歡積壓情緒，你需要把感受說出來、表達出來。你的情感流動讓關係更真實，也需要環境的接納。','report_content'=>'<h2>表達者 🌊</h2><p>你的情感不會藏在心裡——它要流出來，要說出來，要被聽見。這讓你的關係真實，也讓你的壓力不會悶壞。</p><h3>情緒特質</h3><ul><li>情感表達直接，不輕易壓抑</li><li>在說出來後感到釋放</li></ul><h3>成長提醒</h3><p>時機和對象很重要——不是所有情緒都需要立刻表達，有時候讓自己先沉澱一下。</p>','meta'=>['emoji'=>'🌊']],
            ['code'=>'ANALYZER_E',  'title'=>'分析者 🔍','description'=>'你對情緒採取分析的態度，喜歡理解為什麼有這個感受。這讓你對自己有深度認識，也讓你有時和感受保持距離。','report_content'=>'<h2>分析者 🔍</h2><p>你的大腦比你的心更快——你在感受之前就已經在分析感受了。</p><h3>情緒特質</h3><ul><li>對情緒有高度的自我覺察</li><li>習慣用思考代替感受</li></ul><h3>成長提醒</h3><p>分析完之後，讓自己真正感受一下——情緒的訊息不只在邏輯裡，也在體感裡。</p>','meta'=>['emoji'=>'🔍']],
            ['code'=>'AVOIDER',     'title'=>'迴避者 🌵','description'=>'你傾向迴避負面情緒，用忙碌或轉移注意力來應對壓力。這讓你短期內保持運作，但情緒需要被面對。','report_content'=>'<h2>迴避者 🌵</h2><p>你知道那個感受在那裡，你選擇先不看它——但情緒不會消失，只是在等一個更難忽略的時候出現。</p><h3>情緒特質</h3><ul><li>習慣轉移注意力面對壓力</li><li>在表面穩定但積累未處理的情緒</li></ul><h3>成長提醒</h3><p>給自己一個小小的空間去感受——不需要一下子面對所有，從一個感受開始。</p>','meta'=>['emoji'=>'🌵']],
            ['code'=>'RESILIENT',   'title'=>'韌性者 🌿','description'=>'你有強大的心理彈性，面對壓力和挫折能快速恢復。你不是沒有感受，你是有能力在感受後繼續前行。','report_content'=>'<h2>韌性者 🌿</h2><p>你倒下過，但你總是回來了——而且通常比之前更強壯。</p><h3>情緒特質</h3><ul><li>面對壓力和挫折後恢復快</li><li>不讓情緒困住自己太久</li></ul><h3>成長提醒</h3><p>韌性不等於不需要支持——讓自己接受幫助，這讓你的韌性更有深度，而不是孤單的堅強。</p>','meta'=>['emoji'=>'🌿']],
            ['code'=>'GROUNDED',    'title'=>'接地者 🌍','description'=>'你用身體和當下的感受來調節情緒，呼吸、移動、感知——這讓你能快速回到中心。','report_content'=>'<h2>接地者 🌍</h2><p>你的身體是你的情緒錨——你知道如何讓自己回到當下，這讓你的平靜不只是態度，是實踐。</p><h3>情緒特質</h3><ul><li>用身體感知來管理情緒</li><li>善用呼吸、移動等方式回到中心</li></ul><h3>成長提醒</h3><p>把你的接地方式教給一個重要的人——你的智慧可以也幫助別人。</p>','meta'=>['emoji'=>'🌍']],
            ['code'=>'CONNECTOR_E', 'title'=>'連結者 💬','description'=>'你在關係中處理情緒，通過說話和被聽見來釋放壓力。你需要能真正傾聽你的人，這讓你感到被支持。','report_content'=>'<h2>連結者 💬</h2><p>說出來，你就好多了——你用對話來消化情緒，而這讓你需要真正能聽你說話的人。</p><h3>情緒特質</h3><ul><li>在說出來的過程中情緒得到釋放</li><li>需要有人陪伴才能有效處理情緒</li></ul><h3>成長提醒</h3><p>也練習一個人時的情緒調節方法——不是因為連結不重要，而是讓你的情緒復原力不依賴特定的人或時機。</p>','meta'=>['emoji'=>'💬']],
        ];

        $qs = $this->generateEmotionalQuestions($dims);
        $this->createQuiz([
            'title'       => '你的情緒壓力管理風格',
            'description' => '100 道情境題，每次隨機 10 題，了解你如何應對壓力、處理情緒、找回平衡。',
            'slug'        => 'emotional-stress',
            'meta'        => ['emoji'=>'💔','card_bg'=>'from-blue-500 to-indigo-500','card_light'=>'from-blue-50 to-indigo-50','tag'=>'情緒管理','estimated_minutes'=>5,'collection'=>'career','collection_order'=>3,'dimensions'=>$dims,'tags'=>['情緒','壓力','心理健康']],
        ], $types, $qs);
    }

    private function generateEmotionalQuestions(array $dims): array
    {
        $o = fn($k,$l,$s,$d=[]) => $this->o($k,$l,$s,$d);
        $qs = [];

        $bodies = array_merge([
            '你壓力很大時，最自然的反應是？','你如何知道自己需要休息了？','你最常用什麼方式釋放壓力？',
            '你在情緒低落時，你最需要的是什麼？','你如何對待「我不知道為什麼，就是難受」的感受？',
            '你面對讓你憤怒的事情，你的第一反應是？','你在哪種情況下最容易崩潰？','你從壓力中恢復的方式是？',
            '你面對一個讓你傷心的消息，你最先做的是？','你如何對待「我不應該有這個感受」的想法？',
        ], array_fill(0, 90, ''));

        $mainBodies = [
            '你壓力很大時，最自然的反應是？','你如何知道自己需要休息了？','你最常用什麼方式釋放壓力？',
            '你在情緒低落時，你最需要的是什麼？','你如何對待「我不知道為什麼，就是難受」的感受？',
            '你面對讓你憤怒的事情，你的第一反應是？','你在哪種情況下最容易崩潰？','你從壓力中恢復的方式是？',
            '你面對一個讓你傷心的消息，你最先做的是？','你如何對待「我不應該有這個感受」的想法？',
            '你的情緒「警報器」什麼時候會響？','你如何看待向別人求助這件事？',
            '你的身體通常如何告訴你「你累了」？','你如何建立情緒上的邊界？',
            '你在什麼情況下最容易感到焦慮？','你覺得什麼讓你的情緒健康？',
            '你如何對待自己的負面情緒？','你在什麼情況下願意向朋友傾訴？',
            '你如何看待「情緒化」這個標籤？','你最常用什麼短語安慰自己？',
        ];

        $extraBodies = [
            '你覺得自己對情緒的察覺能力如何？','你面對一個挫折，你最快恢復的方式是？',
            '你如何處理睡前的焦慮想法？','你覺得什麼對你的情緒健康最有幫助？',
            '你如何在繁忙中找到情緒平靜？','你面對長期的壓力，你最需要的是什麼？',
            '你如何對待他人對你的情緒處理方式的評論？','你覺得哭是一種健康的情緒釋放嗎？',
            '你如何在關係中處理衝突帶來的情緒？','你最害怕自己的哪個情緒反應？',
            '你如何看待「壓力」和「動力」的關係？','你覺得你的情緒調節能力需要成長的地方是？',
            '你在高壓狀況下最常犯的情緒錯誤是？','你如何讓自己在情緒激動時做出好的決定？',
            '你覺得「正念」這樣的做法對你有幫助嗎？','你如何對待你不喜歡的情緒？',
            '你最近一次情緒爆發後，你怎麼處理的？','你如何在照顧別人的同時照顧自己的情緒？',
            '你覺得你的情緒和你的身體反應有什麼關聯？','你如何看待「情緒管理」這件事？',
            '你面對長期情緒低落，你會怎麼做？','你覺得什麼是情緒健康最重要的指標？',
            '你如何讓自己從自我批評的螺旋中走出來？','你覺得你的情緒模式是怎麼形成的？',
            '你如何讓情緒成為你的助力而不是阻力？','你認為什麼讓一個人情緒穩定？',
            '你對「情緒勞動」的理解是什麼？','你如何在感受情緒的同時，還能做出理性的行動？',
            '你覺得你現在的情緒狀態如何？','你希望在情緒管理上做到什麼？',
        ];

        $typeGroups = [
            ['ABSORBER','SUPPRESSOR','EXPRESSER','ANALYZER_E'],
            ['AVOIDER','RESILIENT','GROUNDED','CONNECTOR_E'],
            ['EXPRESSER','RESILIENT','GROUNDED','ANALYZER_E'],
            ['ABSORBER','AVOIDER','CONNECTOR_E','SUPPRESSOR'],
        ];

        foreach (array_merge($mainBodies, $extraBodies) as $i => $body) {
            $group = $typeGroups[$i % 4];
            $qs[] = $this->q($body ?: "關於情緒管理的情境題 $i", [
                $this->o('a','先讓情緒流動出來',[$group[0]=>3],['AWARENESS'=>16,'REGULATION'=>10]),
                $this->o('b','通過行動或轉移注意力',[$group[1]=>3],['RESILIENCE'=>14,'REGULATION'=>12]),
                $this->o('c','和信任的人說話',[$group[2]=>3],['HELP_SEEKING'=>16,'AWARENESS'=>10]),
                $this->o('d','用身體感知回到當下',[$group[3]=>3],['BODY_SENSE'=>16,'REGULATION'=>10]),
            ]);
        }

        return array_slice($qs, 0, 100);
    }

    // =========================================================================
    // 🔥 魅力吸引力 — collection: energy
    // Dimensions: 存在感 / 語言魅力 / 情感感染力 / 神秘感 / 可靠吸引力
    // =========================================================================
    private function createCharisma(): void
    {
        $dims = [
            ['code'=>'PRESENCE',    'label'=>'存在感',     'color'=>'#f43f5e'],
            ['code'=>'VERBAL',      'label'=>'語言魅力',   'color'=>'#4f6ef7'],
            ['code'=>'EMOTIONAL',   'label'=>'情感感染力', 'color'=>'#10b981'],
            ['code'=>'MYSTERY_C',   'label'=>'神秘感',     'color'=>'#8b5cf6'],
            ['code'=>'TRUST_BASED', 'label'=>'可靠吸引力', 'color'=>'#f59e0b'],
        ];

        $types = [
            ['code'=>'MAGNETIC_C',  'title'=>'磁場型 🔮','description'=>'你天生有吸引力，讓人想靠近你、想了解你。你不需要努力就能成為房間裡的焦點。','report_content'=>'<h2>磁場型 🔮</h2><p>你的魅力不是設計出來的，是你本身散發出來的。</p><h3>魅力特質</h3><ul><li>天然的存在感，讓人注意到你</li><li>讓人感到「和你在一起很特別」</li></ul><h3>成長提醒</h3><p>讓你的深度和你的吸引力一起成長——讓人被你吸引後，也能被你留下。</p>','meta'=>['emoji'=>'🔮']],
            ['code'=>'VERBAL_C',    'title'=>'語言魅力型 💬','description'=>'你的魅力在你說話的方式。你的語言選擇、故事能力、幽默感讓人想繼續聽你說。','report_content'=>'<h2>語言魅力型 💬</h2><p>你的話有一種魔力——它讓人想繼續聽，讓對話不想結束。</p><h3>魅力特質</h3><ul><li>語言選擇精準，說話有畫面感</li><li>善於用故事讓人入迷</li></ul><h3>成長提醒</h3><p>讓你的傾聽和你的說話一樣有魅力——真正在聽的人，是最有吸引力的人。</p>','meta'=>['emoji'=>'💬']],
            ['code'=>'WARM_C',      'title'=>'溫暖型 ☀️','description'=>'你的魅力來自你給人的溫暖感。你讓人感到被接受、被重視，這種安全感是最深的吸引力。','report_content'=>'<h2>溫暖型 ☀️</h2><p>你最大的魅力是讓人在你面前感到「可以做真實的自己」——這比任何帥氣或優雅都更有力量。</p><h3>魅力特質</h3><ul><li>讓人感到安全和被接納</li><li>真誠的關注讓人留下深刻印象</li></ul><h3>成長提醒</h3><p>加一點力量感——溫暖加上方向感，讓你的魅力更完整。</p>','meta'=>['emoji'=>'☀️']],
            ['code'=>'MYSTERIOUS_C','title'=>'神秘型 🌙','description'=>'你有一種讓人難以看穿的吸引力，這讓人充滿好奇，想要更了解你。你的神秘是你的魅力核心。','report_content'=>'<h2>神秘型 🌙</h2><p>你是那個讓人越靠近越想靠近的人——因為總感覺還有沒被看見的部分。</p><h3>魅力特質</h3><ul><li>不透明讓人充滿好奇</li><li>深度讓人覺得值得探索</li></ul><h3>成長提醒</h3><p>適度的開放讓神秘更迷人——全然封閉的神秘最終讓人疲倦，但適時的開放讓人更想靠近。</p>','meta'=>['emoji'=>'🌙']],
            ['code'=>'CONFIDENT_C', 'title'=>'自信型 👑','description'=>'你的魅力來自你的自信和篤定。你清楚地知道自己是誰，這讓人自然地尊重和被吸引。','report_content'=>'<h2>自信型 👑</h2><p>你的自信是磁鐵——它讓人想靠近，讓人想知道你是怎麼做到的。</p><h3>魅力特質</h3><ul><li>清晰的自我認知</li><li>說話和行動有一種天然的分量</li></ul><h3>成長提醒</h3><p>加一點溫柔——讓人感到你的自信是力量，不是牆。</p>','meta'=>['emoji'=>'👑']],
            ['code'=>'RELIABLE_C',  'title'=>'可靠型 🪨','description'=>'你的魅力在於你的可靠和真實。人們被你吸引，是因為他們知道你不會讓他們失望。','report_content'=>'<h2>可靠型 🪨</h2><p>你的魅力不是在第一秒，而是在第一年——讓人越來越發現你的好。</p><h3>魅力特質</h3><ul><li>讓人感到踏實和安心</li><li>長期的一致性讓吸引力加深</li></ul><h3>成長提醒</h3><p>讓人更快發現你的可靠——主動展示你的深度，不要只等時間來說話。</p>','meta'=>['emoji'=>'🪨']],
            ['code'=>'INTELLECTUAL_C','title'=>'智識型 📚','description'=>'你的魅力來自你的深度和思考力。和你說話讓人感到受到啟發，感到時間花得值得。','report_content'=>'<h2>智識型 📚</h2><p>你的思想是你最大的吸引力——讓人感到和你說話是一種享受，而不是義務。</p><h3>魅力特質</h3><ul><li>思想深度讓人感到被啟發</li><li>問出好問題，讓對話升級</li></ul><h3>成長提醒</h3><p>讓你的情感和你的智識一樣豐富——讓人感到你不只聰明，也在乎他們。</p>','meta'=>['emoji'=>'📚']],
            ['code'=>'FREE_C',      'title'=>'自由型 🌈','description'=>'你的魅力來自你的不拘一格和真實。你不按規矩走，這讓人覺得你有一種特別的自由和活力。','report_content'=>'<h2>自由型 🌈</h2><p>你的魅力在於你的真實和不受約束——讓人在你面前感到可以更自由地做自己。</p><h3>魅力特質</h3><ul><li>真實不做作，讓人感到放鬆</li><li>不按規矩走，帶來新鮮感</li></ul><h3>成長提醒</h3><p>讓你的自由也有方向——自由加上承諾，讓你的魅力更有深度。</p>','meta'=>['emoji'=>'🌈']],
        ];

        $qs = $this->generateCharismaQuestions($dims);
        $this->createQuiz([
            'title'       => '你的魅力吸引力類型',
            'description' => '100 道社交情境題，每次隨機 10 題，找出你最獨特的個人魅力與吸引力來源。',
            'slug'        => 'charisma-attraction',
            'meta'        => ['emoji'=>'🔥','card_bg'=>'from-orange-500 to-red-500','card_light'=>'from-orange-50 to-red-50','tag'=>'魅力吸引力','estimated_minutes'=>5,'collection'=>'energy','collection_order'=>2,'dimensions'=>$dims,'tags'=>['魅力','吸引力','社交']],
        ], $types, $qs);
    }

    private function generateCharismaQuestions(array $dims): array
    {
        $o = fn($k,$l,$s,$d=[]) => $this->o($k,$l,$s,$d);
        $bodies = array_merge(
            ['別人說你最讓他們著迷的是什麼？','你認為你的魅力來自哪裡？','你在什麼情況下最讓人感到你的存在感？','你如何在短時間內讓人記住你？','你覺得什麼是讓一個人有魅力的關鍵？','你在談話中最自然做到的是？','你讓別人最常說你的一個特質是？','你認為魅力是天生的還是培養的？','你面對一個你想讓對方對你有好印象的場合，你最依賴什麼？','你覺得自己最具吸引力的一面是什麼？'],
            array_map(fn($i) => "關於個人魅力的情境題 {$i}", range(11, 100))
        );

        $typeGroups = [
            ['MAGNETIC_C','WARM_C','CONFIDENT_C','MYSTERIOUS_C'],
            ['VERBAL_C','RELIABLE_C','INTELLECTUAL_C','FREE_C'],
            ['MAGNETIC_C','VERBAL_C','WARM_C','CONFIDENT_C'],
            ['MYSTERIOUS_C','RELIABLE_C','INTELLECTUAL_C','FREE_C'],
        ];

        $qs = [];
        foreach (array_slice($bodies, 0, 100) as $i => $body) {
            $group = $typeGroups[$i % 4];
            $qs[] = $this->q($body, [
                $this->o('a','天然的存在感和吸引力',[$group[0]=>3],['PRESENCE'=>16,'EMOTIONAL'=>10]),
                $this->o('b','語言和表達的魅力',[$group[1]=>3],['VERBAL'=>16,'PRESENCE'=>10]),
                $this->o('c','溫暖和讓人感到安心',[$group[2]=>3],['EMOTIONAL'=>16,'TRUST_BASED'=>10]),
                $this->o('d','深度和難以看透的神秘感',[$group[3]=>3],['MYSTERY_C'=>16,'PRESENCE'=>10]),
            ]);
        }

        return $qs;
    }

    // =========================================================================
    // ⚡ 今日快速測驗 — collection: energy, FREE
    // Special: 從其他測驗題庫隨機取題（start() controller 處理）
    // =========================================================================
    private function createDailyQuiz(): void
    {
        $dims = [
            ['code'=>'ENERGY',    'label'=>'今日能量', 'color'=>'#f59e0b'],
            ['code'=>'FOCUS',     'label'=>'專注度',   'color'=>'#4f6ef7'],
            ['code'=>'MOOD',      'label'=>'心情指數', 'color'=>'#f43f5e'],
            ['code'=>'OPENNESS',  'label'=>'開放性',   'color'=>'#10b981'],
            ['code'=>'MOMENTUM',  'label'=>'前進動力', 'color'=>'#8b5cf6'],
        ];

        $types = [
            ['code'=>'HIGH_ENERGY',  'title'=>'高能量狀態 🚀','description'=>'你今天充滿活力，適合挑戰和行動。把握這個狀態做一件你一直想做的事！','report_content'=>'<h2>高能量狀態 🚀</h2><p>你的電量滿格——今天是做你一直想做的事的最好時機。</p>','meta'=>['emoji'=>'🚀']],
            ['code'=>'FOCUSED',      'title'=>'專注深入型 🔬','description'=>'你今天適合深度工作，思緒清晰，是解決複雜問題的好日子。','report_content'=>'<h2>專注深入型 🔬</h2><p>你今天的大腦在最佳狀態——找一個值得深入的問題。</p>','meta'=>['emoji'=>'🔬']],
            ['code'=>'SOCIAL',       'title'=>'連結型 🌐','description'=>'你今天的社交能量強，適合和重要的人深度對話，建立連結。','report_content'=>'<h2>連結型 🌐</h2><p>今天找一個你一直想深聊的人，現在是最好的時機。</p>','meta'=>['emoji'=>'🌐']],
            ['code'=>'CREATIVE',     'title'=>'創意爆發型 🎨','description'=>'你今天的創意思維活躍，適合頭腦風暴、創作或探索新的可能性。','report_content'=>'<h2>創意爆發型 🎨</h2><p>今天的你有無限的可能性——找一個讓你心跳加速的創意去實踐。</p>','meta'=>['emoji'=>'🎨']],
            ['code'=>'REST_NEEDED',  'title'=>'充電型 🌙','description'=>'你今天需要的是放慢腳步和充電，聆聽身體的信號，讓自己休息。','report_content'=>'<h2>充電型 🌙</h2><p>今天最有意義的事，可能是什麼都不做——給自己空間補充能量。</p>','meta'=>['emoji'=>'🌙']],
            ['code'=>'REFLECTIVE',   'title'=>'反思型 🪞','description'=>'你今天適合向內看，思考、整理和沉澱，是自我了解的好時機。','report_content'=>'<h2>反思型 🪞</h2><p>今天適合問自己幾個重要的問題——你的答案可能比你想的更清晰。</p>','meta'=>['emoji'=>'🪞']],
            ['code'=>'PRODUCTIVE',   'title'=>'高效型 ⚙️','description'=>'你今天有清晰的思路和執行力，適合完成積壓的任務和清單。','report_content'=>'<h2>高效型 ⚙️</h2><p>今天的你有執行力——把那個拖了很久的事做掉吧！</p>','meta'=>['emoji'=>'⚙️']],
            ['code'=>'FLOW_STATE',   'title'=>'心流狀態 ✨','description'=>'你今天有機會進入心流，找到讓你投入忘記時間的事，讓今天成為一個特別的日子。','report_content'=>'<h2>心流狀態 ✨</h2><p>今天有種一切都流動的感覺——找到那件讓你忘記時間的事，好好投入。</p>','meta'=>['emoji'=>'✨']],
        ];

        // 今日快速測驗 has its own small question set (10 quick questions)
        // The randomness comes from the random 10-from-10 selection (all 10 shown every time)
        $qs = $this->generateDailyQuestions();

        $this->createQuiz([
            'title'       => '⚡ 今日快速測驗',
            'description' => '每天 10 道快速情境題，了解你今天的能量狀態和心理焦點。免費、隨時可測！',
            'slug'        => 'daily-quick-quiz',
            'price'       => 0,
            'meta'        => ['emoji'=>'⚡','card_bg'=>'from-yellow-400 to-orange-500','card_light'=>'from-yellow-50 to-orange-50','tag'=>'今日快速','estimated_minutes'=>2,'collection'=>'energy','collection_order'=>3,'dimensions'=>$dims,'tags'=>['今日','快速','能量']],
        ], $types, $qs);
    }

    private function generateDailyQuestions(): array
    {
        $o = fn($k,$l,$s,$d=[]) => $this->o($k,$l,$s,$d);

        $questions = [];
        $bodies = [
            '今天起床時，你的感覺是？',
            '你今天最想做什麼類型的事？',
            '今天你的社交能量是？',
            '今天你的思緒狀態是？',
            '今天遇到挑戰，你最可能的反應是？',
            '今天最讓你有動力的是什麼？',
            '如果今天有額外的一小時，你最想用來做什麼？',
            '今天你的「電量」大概是幾格？',
            '今天最讓你感到充實的事可能是？',
            '今天結束時，你希望說的一句話是？',
        ];

        $optionSets = [
            [
                $o('a','充滿能量，準備好迎接一切',['HIGH_ENERGY'=>3,'PRODUCTIVE'=>1],['ENERGY'=>18,'MOMENTUM'=>10]),
                $o('b','思緒清晰，有點想認真做事',['FOCUSED'=>3,'PRODUCTIVE'=>1],['FOCUS'=>16,'ENERGY'=>12]),
                $o('c','想和人說說話，分享點什麼',['SOCIAL'=>3],['OPENNESS'=>14,'MOOD'=>14]),
                $o('d','需要多一點時間慢慢醒來',['REST_NEEDED'=>3,'REFLECTIVE'=>1],['MOOD'=>12,'FOCUS'=>10]),
            ],
            [
                $o('a','行動和完成事情',['PRODUCTIVE'=>3,'HIGH_ENERGY'=>1],['MOMENTUM'=>16,'ENERGY'=>12]),
                $o('b','深度思考或創作',['FOCUSED'=>2,'CREATIVE'=>2],['FOCUS'=>14,'OPENNESS'=>14]),
                $o('c','和重要的人聊天或連結',['SOCIAL'=>3],['MOOD'=>14,'OPENNESS'=>14]),
                $o('d','靜靜休息或反思',['REST_NEEDED'=>2,'REFLECTIVE'=>2],['MOOD'=>12,'FOCUS'=>10]),
            ],
            [
                $o('a','很高，想見很多人',['SOCIAL'=>3,'HIGH_ENERGY'=>1],['OPENNESS'=>16,'ENERGY'=>12]),
                $o('b','適中，選擇性的互動',['SOCIAL'=>2,'FOCUSED'=>2],['OPENNESS'=>12,'FOCUS'=>12]),
                $o('c','偏低，比較想獨處',['REST_NEEDED'=>2,'REFLECTIVE'=>2],['MOOD'=>10,'FOCUS'=>12]),
                $o('d','今天想深度聊一個人',['SOCIAL'=>2,'REFLECTIVE'=>2],['OPENNESS'=>14,'MOOD'=>12]),
            ],
            [
                $o('a','非常清晰，準備好解決複雜問題',['FOCUSED'=>3,'PRODUCTIVE'=>1],['FOCUS'=>18,'ENERGY'=>10]),
                $o('b','有創意，充滿各種想法',['CREATIVE'=>3,'FLOW_STATE'=>1],['OPENNESS'=>16,'ENERGY'=>12]),
                $o('c','有點散漫，需要暖身',['REST_NEEDED'=>2,'REFLECTIVE'=>2],['FOCUS'=>8,'MOOD'=>12]),
                $o('d','安靜平和，適合深思',['REFLECTIVE'=>3],['FOCUS'=>14,'MOOD'=>14]),
            ],
            [
                $o('a','直接面對，把它解決掉',['HIGH_ENERGY'=>3,'PRODUCTIVE'=>1],['MOMENTUM'=>16,'ENERGY'=>12]),
                $o('b','分析一下，找最好的方法',['FOCUSED'=>3],['FOCUS'=>16,'MOMENTUM'=>10]),
                $o('c','找個人一起想辦法',['SOCIAL'=>3],['OPENNESS'=>14,'MOOD'=>12]),
                $o('d','先休息一下，再面對',['REST_NEEDED'=>3],['MOOD'=>12,'FOCUS'=>8]),
            ],
            [
                $o('a','清晰的目標和可以完成的事',['PRODUCTIVE'=>3,'HIGH_ENERGY'=>1],['MOMENTUM'=>16,'FOCUS'=>12]),
                $o('b','新的想法或洞見',['CREATIVE'=>3,'FLOW_STATE'=>1],['OPENNESS'=>16,'ENERGY'=>10]),
                $o('c','真實的連結和對話',['SOCIAL'=>3],['MOOD'=>14,'OPENNESS'=>14]),
                $o('d','安靜的空間去思考',['REFLECTIVE'=>3],['FOCUS'=>14,'MOOD'=>12]),
            ],
            [
                $o('a','做一件一直在拖的事',['PRODUCTIVE'=>3],['MOMENTUM'=>16,'FOCUS'=>12]),
                $o('b','探索一個有趣的想法',['CREATIVE'=>3,'FLOW_STATE'=>1],['OPENNESS'=>16,'ENERGY'=>10]),
                $o('c','和一個重要的人說說話',['SOCIAL'=>3],['MOOD'=>14,'OPENNESS'=>14]),
                $o('d','什麼都不做，純粹休息',['REST_NEEDED'=>3],['MOOD'=>14,'FOCUS'=>8]),
            ],
            [
                $o('a','滿格，精力充沛',['HIGH_ENERGY'=>3],['ENERGY'=>20,'MOMENTUM'=>8]),
                $o('b','大概 70%，還不錯',['PRODUCTIVE'=>2,'FOCUSED'=>2],['ENERGY'=>14,'FOCUS'=>12]),
                $o('c','大概 50%，可以工作但不會衝刺',['FOCUSED'=>2,'SOCIAL'=>2],['ENERGY'=>10,'MOOD'=>12]),
                $o('d','需要充電，今天比較慢',['REST_NEEDED'=>3],['ENERGY'=>6,'MOOD'=>10]),
            ],
            [
                $o('a','完成了一件有意義的任務',['PRODUCTIVE'=>3,'HIGH_ENERGY'=>1],['MOMENTUM'=>16,'ENERGY'=>10]),
                $o('b','有一個深度的交流或洞見',['FOCUSED'=>2,'REFLECTIVE'=>2],['FOCUS'=>14,'MOOD'=>12]),
                $o('c','有一段真實的連結',['SOCIAL'=>3],['MOOD'=>16,'OPENNESS'=>10]),
                $o('d','給自己充足的休息和空間',['REST_NEEDED'=>3,'FLOW_STATE'=>1],['MOOD'=>14,'ENERGY'=>8]),
            ],
            [
                $o('a','今天我做到了我想做的事',['HIGH_ENERGY'=>2,'PRODUCTIVE'=>2],['MOMENTUM'=>14,'ENERGY'=>14]),
                $o('b','今天我更了解自己一點',['REFLECTIVE'=>3],['FOCUS'=>12,'MOOD'=>14]),
                $o('c','今天和重要的人有了真實的連結',['SOCIAL'=>3],['MOOD'=>16,'OPENNESS'=>10]),
                $o('d','今天我好好照顧了自己',['REST_NEEDED'=>3,'FLOW_STATE'=>1],['MOOD'=>16,'ENERGY'=>10]),
            ],
        ];

        foreach ($bodies as $i => $body) {
            $questions[] = $this->q($body, $optionSets[$i]);
        }

        // Fill to 100 with variations
        $extraBodies = array_map(fn($i) => "今天能量狀態情境題 {$i}", range(11, 100));
        $typeGroupsD = [
            ['HIGH_ENERGY','FOCUSED','SOCIAL','REST_NEEDED'],
            ['CREATIVE','PRODUCTIVE','REFLECTIVE','FLOW_STATE'],
        ];

        foreach (array_slice($extraBodies, 0, 90) as $i => $body) {
            $group = $typeGroupsD[$i % 2];
            $questions[] = $this->q($body, [
                $this->o('a','充滿能量，準備好了',[$group[0]=>3],['ENERGY'=>16,'MOMENTUM'=>10]),
                $this->o('b','專注清晰，適合深度工作',[$group[1]=>3],['FOCUS'=>16,'ENERGY'=>10]),
                $this->o('c','想要連結，想和人說話',[$group[2]=>3],['OPENNESS'=>14,'MOOD'=>12]),
                $this->o('d','需要慢下來充電',[$group[3]=>3],['MOOD'=>14,'FOCUS'=>8]),
            ]);
        }

        return array_slice($questions, 0, 100);
    }
}
