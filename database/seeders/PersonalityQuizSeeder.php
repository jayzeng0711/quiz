<?php

namespace Database\Seeders;

/**
 * 🧠 人格探索 — 100 questions, 8 result types, 5 dimensions
 * Collection: self
 * Dimensions: 開放性 / 主導性 / 感性 / 邏輯性 / 社交性
 */
class PersonalityQuizSeeder extends BaseQuizSeeder
{
    protected function quizConfig(): array
    {
        return [
            'title'       => '你的人格探索',
            'description' => '100 道多元情境題，每次隨機抽取 10 題，揭開你最核心的人格特質與行為傾向。',
            'slug'        => 'personality-explorer',
            'price'       => 19,
            'meta'        => [
                'emoji'             => '🧠',
                'card_bg'           => 'from-brand-500 to-violet-500',
                'card_light'        => 'from-brand-50 to-violet-50',
                'tag'               => '人格測驗',
                'estimated_minutes' => 5,
                'collection'        => 'self',
                'collection_order'  => 1,
                'dimensions'        => [
                    ['code' => 'OPENNESS',       'label' => '開放性', 'color' => '#4f6ef7'],
                    ['code' => 'ASSERTIVENESS',  'label' => '主導性', 'color' => '#f43f5e'],
                    ['code' => 'SENSITIVITY',    'label' => '感性',   'color' => '#10b981'],
                    ['code' => 'ANALYTICAL',     'label' => '邏輯性', 'color' => '#f59e0b'],
                    ['code' => 'SOCIABILITY',    'label' => '社交性', 'color' => '#8b5cf6'],
                ],
                'tags' => ['人格', '自我探索', '心理'],
            ],
        ];
    }

    protected function resultTypes(): array
    {
        return [
            [
                'code' => 'EXPLORER', 'title' => '開拓者 🗺️',
                'description' => '你充滿好奇心，熱愛探索未知的領域。你不滿足於現狀，總是在尋找下一個讓你興奮的事物。你的能量感染周圍的人，讓他們也願意踏出舒適圈。',
                'report_content' => '<h2>開拓者 🗺️</h2><p>你的人格核心是好奇與行動。你不只是「想試試看」，你真的會去試——而且往往比大多數人先一步看到新的可能性。</p><h3>核心特質</h3><ul><li>對新想法和陌生事物有天然的吸引力</li><li>能在不確定中保持興奮而非恐懼</li><li>創意思維強，善於找到意想不到的解法</li></ul><h3>成長提醒</h3><p>你的好奇心是禮物，但有時讓你難以深耕。練習在一件事上待長一點——深度和廣度並存，才是你最完整的樣子。</p>',
                'meta' => ['emoji' => '🗺️'],
            ],
            [
                'code' => 'GUARDIAN', 'title' => '守護者 🛡️',
                'description' => '你穩定、可靠，重視安全和延續。你是他人最信賴的依靠，用你的踏實和責任感讓周圍的人感到安心。',
                'report_content' => '<h2>守護者 🛡️</h2><p>你是那種讓事情穩定運作的人。你不需要掌聲，你的存在本身就是一種力量——讓混亂中有了秩序，讓不安中有了依靠。</p><h3>核心特質</h3><ul><li>高度可靠，言出必行</li><li>對所愛之人有強烈的保護本能</li><li>重視傳統和有意義的儀式</li></ul><h3>成長提醒</h3><p>你的穩定是美德，但偶爾讓自己也嘗試一件沒有把握的事——不確定中有時藏著最意外的驚喜。</p>',
                'meta' => ['emoji' => '🛡️'],
            ],
            [
                'code' => 'THINKER', 'title' => '思考者 🔬',
                'description' => '你喜歡深度思考，對事物的本質充滿好奇。你不輕易接受表面答案，習慣追問「為什麼」，並在思考中找到屬於你的答案。',
                'report_content' => '<h2>思考者 🔬</h2><p>你的世界是在腦袋裡建構的。你能在別人還在看表面的時候，已經在分析結構、尋找模式、挖掘更深的真相。</p><h3>核心特質</h3><ul><li>深度分析能力強，不輕易被表象說服</li><li>喜歡獨立思考，形成自己的觀點</li><li>對複雜問題有高度的耐受力和興趣</li></ul><h3>成長提醒</h3><p>思考是你的優勢，但有時想太多會讓你停在原地。試著讓行動先開始，再用思考來修正方向。</p>',
                'meta' => ['emoji' => '🔬'],
            ],
            [
                'code' => 'FEELER', 'title' => '感受者 🌊',
                'description' => '你的情感豐富而深刻，對周圍人的感受非常敏銳。你用心感受生命中的每個細節，讓關係充滿溫度和真實。',
                'report_content' => '<h2>感受者 🌊</h2><p>你的超能力是感受——你比別人更深地體驗快樂、悲傷、美麗和連結。這讓你的生命比多數人更豐富、更有色彩。</p><h3>核心特質</h3><ul><li>情緒感知力強，能快速感受到他人的狀態</li><li>藝術和美感對你有深刻的吸引力</li><li>在關係中重視深度和真實性</li></ul><h3>成長提醒</h3><p>你的感受力是天賦，但在需要理性決策時，練習把感受放在一邊暫時看數據——兩者並用，你的判斷會更全面。</p>',
                'meta' => ['emoji' => '🌊'],
            ],
            [
                'code' => 'DRIVER', 'title' => '行動者 ⚡',
                'description' => '你是天生的行動者，果斷而有效率。你不喜歡原地踏步，永遠在推進事情往前走，用你的能量帶動身邊的一切。',
                'report_content' => '<h2>行動者 ⚡</h2><p>你不是在計畫去做，你就在做。這讓你在需要行動力的世界裡，永遠比別人先走一步。</p><h3>核心特質</h3><ul><li>決策果斷，不害怕做錯</li><li>高效率，能在短時間內完成大量工作</li><li>在壓力下保持清醒，甚至更有動力</li></ul><h3>成長提醒</h3><p>速度是你的優勢，但偶爾慢下來聽聽別人的想法——有時候別人看到的那個細節，正是讓結果從好變成很好的關鍵。</p>',
                'meta' => ['emoji' => '⚡'],
            ],
            [
                'code' => 'CREATOR', 'title' => '創造者 🎨',
                'description' => '你有無窮的創意和獨特的視角。你看世界的方式與眾不同，能在平凡中發現不平凡，用你的創造力讓生活更有意思。',
                'report_content' => '<h2>創造者 🎨</h2><p>你的腦袋是一個創意發電機，隨時都在產出新的連結、新的想法、新的可能性。這讓你在需要突破的地方，永遠是最珍貴的人。</p><h3>核心特質</h3><ul><li>創意豐富，能從不相關的事物中找到連結</li><li>審美感強，對美的細節高度敏感</li><li>不喜歡重複，總是在尋找新的做法</li></ul><h3>成長提醒</h3><p>你的創意是寶，但讓它落地才是真的有用。練習把一個好想法從發想帶到完成——這個過程會讓你的創意力加倍有力。</p>',
                'meta' => ['emoji' => '🎨'],
            ],
            [
                'code' => 'CONNECTOR', 'title' => '連結者 🌐',
                'description' => '你天生擅長建立關係和連接不同的人。你能看到每個人的獨特之處，並把對的人帶到一起，創造出比個人更大的力量。',
                'report_content' => '<h2>連結者 🌐</h2><p>你是人際網絡的核心。不是因為你認識最多人，而是因為你讓每個人在你的存在中感到被看見——這讓人們願意靠近你，也願意被你連結。</p><h3>核心特質</h3><ul><li>高度同理心，讓人在你面前感到安全</li><li>善於找到人與人之間的共同點</li><li>在社交場合中有天然的影響力和吸引力</li></ul><h3>成長提醒</h3><p>你很善於讓別人感到好，記得也讓自己感到好。適時說出你的需求，讓關係是雙向流動的。</p>',
                'meta' => ['emoji' => '🌐'],
            ],
            [
                'code' => 'OBSERVER', 'title' => '觀察者 🦉',
                'description' => '你善於觀察和傾聽，在安靜中積累洞見。你不急著說話，但你說出的話往往最深刻，因為你看到了別人沒注意到的東西。',
                'report_content' => '<h2>觀察者 🦉</h2><p>你是那個房間裡最安靜、卻看得最清楚的人。你的沉靜不是缺席，而是一種深度在場——你在積累，然後在最對的時候說出最重要的話。</p><h3>核心特質</h3><ul><li>高度觀察力，能注意到細微的變化</li><li>深思熟慮，不輕易下結論</li><li>善於傾聽，讓對方感到真正被聽見</li></ul><h3>成長提醒</h3><p>你看到的很多，但讓自己也被別人看見吧。主動分享一個想法，讓你的洞察從內心走出來，進入世界。</p>',
                'meta' => ['emoji' => '🦉'],
            ],
        ];
    }

    protected function questions(): array
    {
        // Helper shorthand
        $o = fn($k,$l,$s,$d=[]) => $this->opt($k,$l,$s,$d);

        return [
// ── Block 1: 面對新挑戰與機會 ──────────────────────────────────────────
['body'=>'朋友邀你去一個你完全不熟悉的地方旅行，行程全未定。你的第一反應是？','options'=>[$o('a','立刻答應，這種不確定感讓我更興奮',['EXPLORER'=>3],['OPENNESS'=>18,'SOCIABILITY'=>10]),$o('b','考慮一下，想先了解大概的安排再決定',['GUARDIAN'=>2,'THINKER'=>2],['ANALYTICAL'=>14,'OPENNESS'=>8]),$o('c','有點猶豫，我喜歡有計畫的旅行',['GUARDIAN'=>3],['ANALYTICAL'=>16,'ASSERTIVENESS'=>6]),$o('d','問朋友大家的想法，看看大家期待什麼',['CONNECTOR'=>3],['SOCIABILITY'=>16,'SENSITIVITY'=>10])]],

['body'=>'公司突然宣布一個新的大型計畫需要有人主導。你會？','options'=>[$o('a','主動舉手，這是展現自己的好機會',['DRIVER'=>3,'ASSERTIVENESS'=>1],['ASSERTIVENESS'=>18,'OPENNESS'=>10]),$o('b','等更多資訊再決定要不要加入',['THINKER'=>3],['ANALYTICAL'=>16,'OPENNESS'=>8]),$o('c','如果我的技能合適，願意支持但不想主導',['GUARDIAN'=>3],['ANALYTICAL'=>14,'SENSITIVITY'=>10]),$o('d','和幾個同事討論，看看誰最適合',['CONNECTOR'=>3],['SOCIABILITY'=>16,'SENSITIVITY'=>10])]],

['body'=>'有機會學一個完全跨領域的新技能，但需要半年時間。你會怎麼評估？','options'=>[$o('a','直覺覺得有趣就報名，細節後面再說',['EXPLORER'=>3],['OPENNESS'=>20,'ASSERTIVENESS'=>8]),$o('b','詳細評估時間成本和未來應用',['THINKER'=>3,'GUARDIAN'=>1],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('c','問問身邊學過的人，聽聽他們的經驗',['CONNECTOR'=>2,'FEELER'=>2],['SOCIABILITY'=>14,'SENSITIVITY'=>12]),$o('d','衡量這半年能帶來什麼改變再決定',['DRIVER'=>2,'THINKER'=>2],['ANALYTICAL'=>16,'ASSERTIVENESS'=>10])]],

['body'=>'你正在做一件很重要的事，有人突然打斷你說有更緊急的事需要你。你會？','options'=>[$o('a','立刻放下現在的事，處理緊急狀況',['DRIVER'=>3],['ASSERTIVENESS'=>16,'OPENNESS'=>12]),$o('b','先說完這個段落，再去處理',['THINKER'=>2,'GUARDIAN'=>2],['ANALYTICAL'=>14,'ASSERTIVENESS'=>12]),$o('c','感覺有點不舒服，但還是去了',['FEELER'=>3],['SENSITIVITY'=>16,'SOCIABILITY'=>12]),$o('d','問一下緊急到什麼程度，判斷優先順序',['THINKER'=>3],['ANALYTICAL'=>18,'ASSERTIVENESS'=>10])]],

['body'=>'你在一個派對上遇到了一個說話非常有趣的陌生人。你最自然的反應是？','options'=>[$o('a','主動繼續聊，想認識他更多',['CONNECTOR'=>3,'EXPLORER'=>1],['SOCIABILITY'=>18,'OPENNESS'=>10]),$o('b','聊幾句就退開，給彼此空間',['OBSERVER'=>3],['ANALYTICAL'=>12,'SENSITIVITY'=>10]),$o('c','問他的工作和興趣，想了解他是什麼樣的人',['CONNECTOR'=>2,'THINKER'=>2],['SOCIABILITY'=>14,'ANALYTICAL'=>12]),$o('d','享受當下的對話，不想太多',['FEELER'=>3],['SENSITIVITY'=>14,'SOCIABILITY'=>12])]],

// ── Block 2: 壓力與挑戰 ────────────────────────────────────────────────
['body'=>'當你面對一個根本不知道從哪裡開始的大問題，你通常怎麼做？','options'=>[$o('a','從任何一個小地方動手，邊做邊找方向',['DRIVER'=>3,'EXPLORER'=>1],['ASSERTIVENESS'=>16,'OPENNESS'=>12]),$o('b','花時間分析問題結構，再決定切入點',['THINKER'=>3],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('c','找人討論，聽聽別人怎麼看',['CONNECTOR'=>3],['SOCIABILITY'=>16,'SENSITIVITY'=>10]),$o('d','先讓情緒沉澱，等靈感來了再說',['FEELER'=>2,'OBSERVER'=>2],['SENSITIVITY'=>14,'OPENNESS'=>12])]],

['body'=>'你接到了一個你不確定自己能完成的任務。你的內心OS是？','options'=>[$o('a','挑戰讓我更有動力，試試看',['DRIVER'=>3,'EXPLORER'=>1],['ASSERTIVENESS'=>18,'OPENNESS'=>10]),$o('b','先評估風險，想清楚再決定是否接',['THINKER'=>3],['ANALYTICAL'=>18,'ASSERTIVENESS'=>8]),$o('c','有點緊張，但如果有人一起就好了',['FEELER'=>2,'CONNECTOR'=>2],['SENSITIVITY'=>14,'SOCIABILITY'=>12]),$o('d','找出自己缺乏的資源，補足後再行動',['GUARDIAN'=>3],['ANALYTICAL'=>14,'ASSERTIVENESS'=>12])]],

['body'=>'截止日前一天，你發現自己落後了很多。你最先做的事是？','options'=>[$o('a','立刻開始加速，先做最核心的部分',['DRIVER'=>3],['ASSERTIVENESS'=>18,'ANALYTICAL'=>10]),$o('b','重新計算剩下時間和工作量，重排優先順序',['THINKER'=>3,'GUARDIAN'=>1],['ANALYTICAL'=>18,'ASSERTIVENESS'=>10]),$o('c','告訴相關人員狀況，尋求支援',['CONNECTOR'=>2,'FEELER'=>2],['SOCIABILITY'=>14,'SENSITIVITY'=>12]),$o('d','先深呼吸，讓自己冷靜後再執行',['OBSERVER'=>2,'FEELER'=>2],['SENSITIVITY'=>14,'ANALYTICAL'=>12])]],

['body'=>'你剛剛說了一句話讓別人看起來很受傷。你最自然的反應是？','options'=>[$o('a','立刻道歉，確認對方沒事',['FEELER'=>3,'CONNECTOR'=>1],['SENSITIVITY'=>18,'SOCIABILITY'=>10]),$o('b','想一下自己說了什麼，確認是否真的有問題',['THINKER'=>3],['ANALYTICAL'=>16,'SENSITIVITY'=>10]),$o('c','有點不知所措，不確定要說什麼',['OBSERVER'=>3],['SENSITIVITY'=>14,'ANALYTICAL'=>10]),$o('d','私下找對方聊，了解他的感受',['CONNECTOR'=>3,'FEELER'=>1],['SOCIABILITY'=>14,'SENSITIVITY'=>14])]],

['body'=>'你最近一直無法專注在手邊的事，你認為最可能的原因是？','options'=>[$o('a','有太多有趣的事情讓我分心',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>16,'SOCIABILITY'=>12]),$o('b','這件事本身沒有讓我足夠投入',['DRIVER'=>2,'THINKER'=>2],['ASSERTIVENESS'=>14,'ANALYTICAL'=>14]),$o('c','我還沒想清楚這件事的意義',['THINKER'=>3],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('d','有些情緒還沒處理，讓我無法安靜下來',['FEELER'=>3],['SENSITIVITY'=>18,'SOCIABILITY'=>8])]],

// ── Block 3: 關係與溝通 ────────────────────────────────────────────────
['body'=>'一個朋友向你傾訴一個複雜的問題，你最自然的回應是？','options'=>[$o('a','一邊聽一邊分析，提出可能的解法',['THINKER'=>3],['ANALYTICAL'=>16,'SOCIABILITY'=>10]),$o('b','先聽完，問他現在感覺怎樣',['FEELER'=>3,'CONNECTOR'=>1],['SENSITIVITY'=>18,'SOCIABILITY'=>10]),$o('c','問更多細節，試著完整理解情況',['OBSERVER'=>2,'THINKER'=>2],['ANALYTICAL'=>14,'SENSITIVITY'=>12]),$o('d','分享自己類似的經歷，讓他知道他不孤單',['CONNECTOR'=>3,'FEELER'=>1],['SOCIABILITY'=>16,'SENSITIVITY'=>12])]],

['body'=>'你和一個重要的人意見嚴重不合。你傾向如何處理？','options'=>[$o('a','直接說清楚我的立場，我不怕衝突',['DRIVER'=>3,'ASSERTIVENESS'=>1],['ASSERTIVENESS'=>18,'ANALYTICAL'=>10]),$o('b','找對的時機和對的方式說',['CONNECTOR'=>2,'THINKER'=>2],['SOCIABILITY'=>12,'ANALYTICAL'=>14]),$o('c','先讓事情冷靜下來，再找機會談',['OBSERVER'=>3],['SENSITIVITY'=>12,'ANALYTICAL'=>14]),$o('d','試著先理解對方的立場，再表達自己',['FEELER'=>3],['SENSITIVITY'=>16,'SOCIABILITY'=>12])]],

['body'=>'你發現一個朋友最近明顯狀態不對，但他沒有主動說。你會？','options'=>[$o('a','主動問他最近怎麼了，讓他知道你在',['CONNECTOR'=>3,'FEELER'=>1],['SENSITIVITY'=>16,'SOCIABILITY'=>14]),$o('b','觀察多一些再決定要不要問',['OBSERVER'=>3],['SENSITIVITY'=>12,'ANALYTICAL'=>14]),$o('c','製造一個輕鬆的場合讓他自然說出來',['CONNECTOR'=>2,'FEELER'=>2],['SOCIABILITY'=>14,'SENSITIVITY'=>14]),$o('d','尊重他的節奏，等他準備好再說',['OBSERVER'=>2,'GUARDIAN'=>2],['SENSITIVITY'=>12,'ANALYTICAL'=>12])]],

['body'=>'和一個你不熟的人相處一整天後，你通常的感受是？','options'=>[$o('a','充電——見到新的人讓我精神更好',['CONNECTOR'=>3,'EXPLORER'=>1],['SOCIABILITY'=>18,'OPENNESS'=>10]),$o('b','有點累但也有收穫',['OBSERVER'=>2,'THINKER'=>2],['ANALYTICAL'=>12,'SENSITIVITY'=>12]),$o('c','有趣，但需要一個人的時間恢復',['OBSERVER'=>3],['SENSITIVITY'=>12,'ANALYTICAL'=>14]),$o('d','依對方而定，有些人讓我充電，有些讓我放電',['FEELER'=>3],['SENSITIVITY'=>16,'SOCIABILITY'=>10])]],

['body'=>'你和一群人意見不一，你的意見是少數。你通常怎麼做？','options'=>[$o('a','說出我的觀點，即使是少數',['DRIVER'=>3,'EXPLORER'=>1],['ASSERTIVENESS'=>18,'OPENNESS'=>10]),$o('b','重新審視我的想法，也許多數有道理',['THINKER'=>3],['ANALYTICAL'=>16,'OPENNESS'=>10]),$o('c','說出來，但方式溫和，讓大家自己判斷',['CONNECTOR'=>2,'FEELER'=>2],['SOCIABILITY'=>12,'SENSITIVITY'=>14]),$o('d','沉默觀察，等更好的時機再說',['OBSERVER'=>3],['SENSITIVITY'=>12,'ANALYTICAL'=>14])]],

// ── Block 4: 創意與表達 ────────────────────────────────────────────────
['body'=>'如果你有一個下午完全自由，什麼都不必做，你最想做的是？','options'=>[$o('a','做一件我一直想嘗試但沒時間做的事',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8]),$o('b','一個人靜靜思考或閱讀',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>16,'SENSITIVITY'=>10]),$o('c','和朋友出去玩',['CONNECTOR'=>3],['SOCIABILITY'=>18,'OPENNESS'=>10]),$o('d','做一件創意的事，像畫畫或寫作',['CREATOR'=>3,'FEELER'=>1],['OPENNESS'=>16,'SENSITIVITY'=>12])]],

['body'=>'你對「完美」的追求程度是？','options'=>[$o('a','完美是不存在的，夠好就好',['DRIVER'=>3,'EXPLORER'=>1],['ASSERTIVENESS'=>14,'OPENNESS'=>14]),$o('b','我對品質有高標準，不喜歡馬虎',['THINKER'=>3,'GUARDIAN'=>1],['ANALYTICAL'=>18,'ASSERTIVENESS'=>8]),$o('c','在我在乎的事上追求完美，其他則順其自然',['CREATOR'=>2,'FEELER'=>2],['SENSITIVITY'=>14,'ANALYTICAL'=>12]),$o('d','完美對我來說是一種感覺，而不是標準',['FEELER'=>3],['SENSITIVITY'=>16,'OPENNESS'=>12])]],

['body'=>'你最常用什麼方式表達自己？','options'=>[$o('a','行動——我做給你看',['DRIVER'=>3],['ASSERTIVENESS'=>16,'OPENNESS'=>12]),$o('b','文字——我喜歡寫出來',['THINKER'=>2,'OBSERVER'=>2],['ANALYTICAL'=>14,'SENSITIVITY'=>12]),$o('c','說話——我享受當下的交流',['CONNECTOR'=>3],['SOCIABILITY'=>18,'SENSITIVITY'=>10]),$o('d','藝術或創意——我用作品表達',['CREATOR'=>3,'FEELER'=>1],['OPENNESS'=>16,'SENSITIVITY'=>12])]],

['body'=>'你在一個需要即興發言的場合。你通常的感覺是？','options'=>[$o('a','沒問題，我反應很快',['DRIVER'=>3,'CONNECTOR'=>1],['ASSERTIVENESS'=>16,'SOCIABILITY'=>12]),$o('b','有點緊張，但也能撐過去',['THINKER'=>2,'GUARDIAN'=>2],['ANALYTICAL'=>12,'SENSITIVITY'=>12]),$o('c','享受這種當下的張力',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>16,'SOCIABILITY'=>12]),$o('d','希望先有一點時間準備',['OBSERVER'=>3,'THINKER'=>1],['ANALYTICAL'=>16,'SENSITIVITY'=>10])]],

['body'=>'別人說你最特別的一點是什麼？（你認為他們最可能說的）','options'=>[$o('a','我的能量和行動力',['DRIVER'=>3],['ASSERTIVENESS'=>18,'OPENNESS'=>10]),$o('b','我的深度和洞察力',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>18,'SENSITIVITY'=>8]),$o('c','我讓人感到溫暖和被理解',['FEELER'=>3,'CONNECTOR'=>1],['SENSITIVITY'=>16,'SOCIABILITY'=>12]),$o('d','我的創意和不一樣的角度',['CREATOR'=>3,'EXPLORER'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8])]],

// ── Block 5: 決策風格 ──────────────────────────────────────────────────
['body'=>'你在做一個重要決定時，最依賴的是？','options'=>[$o('a','直覺，感覺對就做',['DRIVER'=>2,'EXPLORER'=>2],['OPENNESS'=>14,'ASSERTIVENESS'=>14]),$o('b','分析，把資料整理清楚再決定',['THINKER'=>3,'GUARDIAN'=>1],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('c','感受，哪個選項讓我感到對',['FEELER'=>3],['SENSITIVITY'=>18,'OPENNESS'=>10]),$o('d','諮詢，聽聽身邊信任的人的意見',['CONNECTOR'=>3],['SOCIABILITY'=>16,'SENSITIVITY'=>10])]],

['body'=>'你做了一個決定，後來發現它是錯的。你如何面對？','options'=>[$o('a','接受然後快速調整，下一步怎麼做更重要',['DRIVER'=>3],['ASSERTIVENESS'=>16,'OPENNESS'=>12]),$o('b','分析哪裡出了問題，確保下次不再犯',['THINKER'=>3],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('c','花一點時間和自己的情緒待著，再往前',['FEELER'=>3],['SENSITIVITY'=>16,'OPENNESS'=>10]),$o('d','和信任的人聊聊，從不同角度理解',['CONNECTOR'=>2,'OBSERVER'=>2],['SOCIABILITY'=>14,'SENSITIVITY'=>12])]],

['body'=>'面對一個你沒有答案的問題，你最自然的態度是？','options'=>[$o('a','我不知道，但我想找出來',['EXPLORER'=>3,'THINKER'=>1],['OPENNESS'=>18,'ANALYTICAL'=>10]),$o('b','先承認不知道，再慢慢研究',['THINKER'=>3,'GUARDIAN'=>1],['ANALYTICAL'=>16,'OPENNESS'=>10]),$o('c','讓問題懸著，有時間沉澱後自然有答案',['OBSERVER'=>3],['SENSITIVITY'=>12,'ANALYTICAL'=>14]),$o('d','問別人，集思廣益',['CONNECTOR'=>3],['SOCIABILITY'=>16,'OPENNESS'=>12])]],

['body'=>'你通常在什麼情況下做出最好的決策？','options'=>[$o('a','在行動中，邊做邊調整',['DRIVER'=>3,'EXPLORER'=>1],['ASSERTIVENESS'=>16,'OPENNESS'=>12]),$o('b','在充分思考之後',['THINKER'=>3],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('c','在感覺到對的時候',['FEELER'=>3],['SENSITIVITY'=>18,'OPENNESS'=>8]),$o('d','在和人充分討論之後',['CONNECTOR'=>3],['SOCIABILITY'=>16,'ANALYTICAL'=>10])]],

['body'=>'對你來說，「成功」最接近哪個描述？','options'=>[$o('a','達到我設定的目標',['DRIVER'=>3],['ASSERTIVENESS'=>16,'ANALYTICAL'=>12]),$o('b','找到真正屬於我的生活方式',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>16,'SENSITIVITY'=>12]),$o('c','對我在乎的人有正面的影響',['CONNECTOR'=>3,'FEELER'=>1],['SOCIABILITY'=>14,'SENSITIVITY'=>14]),$o('d','深入理解我自己和這個世界',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>16,'OPENNESS'=>12])]],

// ── Block 6: 自我認知 ──────────────────────────────────────────────────
['body'=>'你最容易在哪種環境中找到靈感？','options'=>[$o('a','在外面探索，新的地點和人',['EXPLORER'=>3],['OPENNESS'=>18,'SOCIABILITY'=>10]),$o('b','在安靜的獨處中',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>16,'SENSITIVITY'=>10]),$o('c','在和人的對話中',['CONNECTOR'=>3],['SOCIABILITY'=>18,'OPENNESS'=>10]),$o('d','在創作的過程中',['CREATOR'=>3,'FEELER'=>1],['OPENNESS'=>16,'SENSITIVITY'=>12])]],

['body'=>'別人最誤解你的地方是？','options'=>[$o('a','他們以為我不在乎，其實我只是行動快',['DRIVER'=>3],['ASSERTIVENESS'=>16,'SENSITIVITY'=>10]),$o('b','他們以為我冷漠，其實我在觀察',['OBSERVER'=>3,'THINKER'=>1],['ANALYTICAL'=>14,'SENSITIVITY'=>12]),$o('c','他們以為我太敏感，其實我只是感受深',['FEELER'=>3],['SENSITIVITY'=>18,'OPENNESS'=>8]),$o('d','他們以為我不穩定，其實我在探索',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8])]],

['body'=>'你在什麼情況下最有能量？','options'=>[$o('a','在完成一件有挑戰的事之後',['DRIVER'=>3],['ASSERTIVENESS'=>16,'OPENNESS'=>12]),$o('b','在深度獨處和思考之後',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>16,'SENSITIVITY'=>10]),$o('c','在和真正懂我的人相處之後',['CONNECTOR'=>2,'FEELER'=>2],['SOCIABILITY'=>14,'SENSITIVITY'=>14]),$o('d','在創造出讓自己滿意的東西之後',['CREATOR'=>3],['OPENNESS'=>16,'SENSITIVITY'=>12])]],

['body'=>'你認為你在群體中的角色是？','options'=>[$o('a','推進者——讓事情往前走',['DRIVER'=>3],['ASSERTIVENESS'=>18,'OPENNESS'=>8]),$o('b','思考者——提出不同角度',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>16,'OPENNESS'=>10]),$o('c','連結者——讓人與人之間更有凝聚力',['CONNECTOR'=>3],['SOCIABILITY'=>18,'SENSITIVITY'=>10]),$o('d','創造者——帶來新的可能性',['CREATOR'=>3,'EXPLORER'=>1],['OPENNESS'=>16,'ASSERTIVENESS'=>10])]],

['body'=>'你對自己最滿意的一個特質是？','options'=>[$o('a','我的行動力和執行力',['DRIVER'=>3],['ASSERTIVENESS'=>18,'OPENNESS'=>8]),$o('b','我的分析和邏輯思維',['THINKER'=>3],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('c','我的同理心和情感深度',['FEELER'=>3,'CONNECTOR'=>1],['SENSITIVITY'=>18,'SOCIABILITY'=>10]),$o('d','我的創意和獨特視角',['CREATOR'=>3,'EXPLORER'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8])]],

// ── Block 7: 學習與成長 ────────────────────────────────────────────────
['body'=>'學習新事物時，你最喜歡的方式是？','options'=>[$o('a','直接嘗試，從錯誤中學',['DRIVER'=>3,'EXPLORER'=>1],['ASSERTIVENESS'=>14,'OPENNESS'=>14]),$o('b','先理解理論，再應用',['THINKER'=>3],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('c','跟著有經驗的人學',['CONNECTOR'=>2,'GUARDIAN'=>2],['SOCIABILITY'=>14,'ANALYTICAL'=>12]),$o('d','邊學邊創作自己的詮釋',['CREATOR'=>3,'FEELER'=>1],['OPENNESS'=>16,'SENSITIVITY'=>12])]],

['body'=>'你最大的成長通常來自？','options'=>[$o('a','把自己推出舒適圈的挑戰',['EXPLORER'=>3,'DRIVER'=>1],['OPENNESS'=>16,'ASSERTIVENESS'=>12]),$o('b','深度反思和自我檢視',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>16,'SENSITIVITY'=>12]),$o('c','重要的關係和人的影響',['CONNECTOR'=>2,'FEELER'=>2],['SOCIABILITY'=>12,'SENSITIVITY'=>16]),$o('d','創作或表達的過程',['CREATOR'=>3,'FEELER'=>1],['OPENNESS'=>16,'SENSITIVITY'=>12])]],

['body'=>'你覺得自己最需要成長的面向是？','options'=>[$o('a','更有耐心，不要總是要快',['DRIVER'=>3],['ASSERTIVENESS'=>16,'SENSITIVITY'=>10]),$o('b','更有彈性，不要太鑽牛角尖',['THINKER'=>3],['ANALYTICAL'=>16,'OPENNESS'=>10]),$o('c','更有界線，不要太顧及別人感受',['FEELER'=>3,'CONNECTOR'=>1],['SENSITIVITY'=>16,'SOCIABILITY'=>10]),$o('d','更能落地，把創意化為行動',['CREATOR'=>3,'EXPLORER'=>1],['OPENNESS'=>16,'ASSERTIVENESS'=>10])]],

['body'=>'別人給你批評的時候，你最自然的反應是？','options'=>[$o('a','評估這個批評有沒有道理',['THINKER'=>3],['ANALYTICAL'=>18,'ASSERTIVENESS'=>8]),$o('b','先有情緒，然後消化，再看看有沒有道理',['FEELER'=>3],['SENSITIVITY'=>18,'OPENNESS'=>8]),$o('c','直接問對方怎麼看，想完整理解',['CONNECTOR'=>2,'THINKER'=>2],['SOCIABILITY'=>12,'ANALYTICAL'=>14]),$o('d','記下來，之後自己慢慢想',['OBSERVER'=>3,'THINKER'=>1],['ANALYTICAL'=>14,'SENSITIVITY'=>12])]],

['body'=>'面對讚美時，你通常怎麼回應？','options'=>[$o('a','謝謝，然後繼續做下一件事',['DRIVER'=>3],['ASSERTIVENESS'=>14,'OPENNESS'=>12]),$o('b','感謝，但私下會分析自己是否真的做好了',['THINKER'=>3],['ANALYTICAL'=>16,'SENSITIVITY'=>10]),$o('c','開心但有點不好意思',['FEELER'=>2,'OBSERVER'=>2],['SENSITIVITY'=>14,'SOCIABILITY'=>10]),$o('d','感到溫暖，想把這個感受也給回去',['CONNECTOR'=>3,'FEELER'=>1],['SOCIABILITY'=>14,'SENSITIVITY'=>14])]],

// ── Block 8: 生活哲學 ──────────────────────────────────────────────────
['body'=>'你對「改變」的態度是？','options'=>[$o('a','改變讓我興奮，我歡迎它',['EXPLORER'=>3,'DRIVER'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>10]),$o('b','改變需要理由，我需要看到它的意義',['THINKER'=>3,'GUARDIAN'=>1],['ANALYTICAL'=>16,'OPENNESS'=>10]),$o('c','改變讓我有點不安，但我知道它是必要的',['GUARDIAN'=>3],['ANALYTICAL'=>14,'SENSITIVITY'=>12]),$o('d','改變讓我感受豐富，即使不舒服也值得',['FEELER'=>3,'EXPLORER'=>1],['SENSITIVITY'=>14,'OPENNESS'=>14])]],

['body'=>'你最認同哪個關於人生的說法？','options'=>[$o('a','人生是要去做的，不是等著發生的',['DRIVER'=>3,'EXPLORER'=>1],['ASSERTIVENESS'=>16,'OPENNESS'=>12]),$o('b','人生是要去理解的，懂得越多活得越好',['THINKER'=>3],['ANALYTICAL'=>18,'OPENNESS'=>10]),$o('c','人生是要去感受的，深度比長度重要',['FEELER'=>3],['SENSITIVITY'=>18,'OPENNESS'=>10]),$o('d','人生是要去連結的，孤獨的意義是有限的',['CONNECTOR'=>3],['SOCIABILITY'=>18,'SENSITIVITY'=>10])]],

['body'=>'你最怕什麼？','options'=>[$o('a','無聊，一成不變的生活',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8]),$o('b','做出後悔的決定',['GUARDIAN'=>3,'THINKER'=>1],['ANALYTICAL'=>16,'SENSITIVITY'=>10]),$o('c','和重要的人疏遠',['CONNECTOR'=>3,'FEELER'=>1],['SOCIABILITY'=>14,'SENSITIVITY'=>14]),$o('d','被誤解，說不出心裡真正的感受',['FEELER'=>3,'OBSERVER'=>1],['SENSITIVITY'=>18,'OPENNESS'=>8])]],

['body'=>'一天結束時，什麼讓你感到「今天值了」？','options'=>[$o('a','完成了一件有意義的任務',['DRIVER'=>3,'GUARDIAN'=>1],['ASSERTIVENESS'=>16,'ANALYTICAL'=>10]),$o('b','想通了一個讓我困惑已久的問題',['THINKER'=>3],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('c','有一段讓我感到真實連結的對話',['CONNECTOR'=>3,'FEELER'=>1],['SOCIABILITY'=>14,'SENSITIVITY'=>14]),$o('d','創造了或體驗了讓我感動的事',['CREATOR'=>2,'FEELER'=>2],['OPENNESS'=>14,'SENSITIVITY'=>16])]],

['body'=>'你認為真正了解你的人，最了解你哪一面？','options'=>[$o('a','我在行動和壓力下的清醒',['DRIVER'=>3],['ASSERTIVENESS'=>16,'ANALYTICAL'=>12]),$o('b','我在深夜才願意說的想法',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>14,'SENSITIVITY'=>14]),$o('c','我在你低落時還是選擇在的樣子',['CONNECTOR'=>3,'FEELER'=>1],['SOCIABILITY'=>12,'SENSITIVITY'=>16]),$o('d','我對美和意義的敏感',['FEELER'=>3,'CREATOR'=>1],['SENSITIVITY'=>16,'OPENNESS'=>12])]],

// ── Block 9: 行動與計畫 ────────────────────────────────────────────────
['body'=>'你開始一個新計畫時，最常犯的錯誤是？','options'=>[$o('a','想太少，直接衝',['DRIVER'=>3],['ASSERTIVENESS'=>16,'OPENNESS'=>12]),$o('b','想太多，遲遲不開始',['THINKER'=>3],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('c','太在意別人的看法',['CONNECTOR'=>2,'FEELER'=>2],['SOCIABILITY'=>12,'SENSITIVITY'=>14]),$o('d','走到一半又被新的想法吸引',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8])]],

['body'=>'對你來說，「完成」比「完美」更重要嗎？','options'=>[$o('a','是的，完成才能前進',['DRIVER'=>3],['ASSERTIVENESS'=>16,'OPENNESS'=>12]),$o('b','不，品質比速度重要',['THINKER'=>3,'GUARDIAN'=>1],['ANALYTICAL'=>16,'ASSERTIVENESS'=>10]),$o('c','看情況，有些事完美更重要',['OBSERVER'=>2,'THINKER'=>2],['ANALYTICAL'=>14,'SENSITIVITY'=>12]),$o('d','完成是過程，完美是方向',['CREATOR'=>3],['OPENNESS'=>14,'ANALYTICAL'=>14])]],

['body'=>'你通常如何保持動力？','options'=>[$o('a','設定目標，看著它一個個被達成',['DRIVER'=>3,'GUARDIAN'=>1],['ASSERTIVENESS'=>16,'ANALYTICAL'=>12]),$o('b','找到意義，知道我為什麼做這件事',['THINKER'=>2,'OBSERVER'=>2],['ANALYTICAL'=>14,'SENSITIVITY'=>12]),$o('c','有人一起，不想讓人失望',['CONNECTOR'=>3],['SOCIABILITY'=>16,'SENSITIVITY'=>12]),$o('d','讓自己保持對這件事的好奇',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8])]],

['body'=>'你放棄一件事的最常見原因是？','options'=>[$o('a','找到了更值得做的事',['EXPLORER'=>3,'DRIVER'=>1],['OPENNESS'=>16,'ASSERTIVENESS'=>12]),$o('b','它失去了讓我興奮的意義',['THINKER'=>2,'FEELER'=>2],['ANALYTICAL'=>12,'SENSITIVITY'=>14]),$o('c','我感覺到它對周圍的人造成了負擔',['FEELER'=>3,'CONNECTOR'=>1],['SENSITIVITY'=>16,'SOCIABILITY'=>12]),$o('d','它不再有新的可能性讓我探索',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8])]],

['body'=>'給未來的自己一句話，你會說什麼？','options'=>[$o('a','繼續行動，不要停下來',['DRIVER'=>3],['ASSERTIVENESS'=>18,'OPENNESS'=>8]),$o('b','繼續思考，答案比你想的更深',['THINKER'=>3],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('c','繼續感受，不要讓生活變得麻木',['FEELER'=>3],['SENSITIVITY'=>18,'OPENNESS'=>8]),$o('d','繼續探索，下一個轉角可能是最好的驚喜',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8])]],

// ── Block 10: 世界觀 ───────────────────────────────────────────────────
['body'=>'你認為世界上最需要更多的是？','options'=>[$o('a','行動力——太多人只是在說',['DRIVER'=>3],['ASSERTIVENESS'=>16,'OPENNESS'=>12]),$o('b','深度思考——很多問題被想得太淺',['THINKER'=>3],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('c','同理心——很多人需要被真正理解',['FEELER'=>3,'CONNECTOR'=>1],['SENSITIVITY'=>16,'SOCIABILITY'=>12]),$o('d','好奇心——讓人更願意接受不一樣',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8])]],

['body'=>'你在哪種情況下最容易感到喜悅？','options'=>[$o('a','突破了一個我以為做不到的事',['DRIVER'=>3,'EXPLORER'=>1],['ASSERTIVENESS'=>14,'OPENNESS'=>14]),$o('b','終於想通了一個複雜的問題',['THINKER'=>3],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('c','和人有一段真實而深刻的連結',['CONNECTOR'=>3,'FEELER'=>1],['SOCIABILITY'=>14,'SENSITIVITY'=>14]),$o('d','創造了讓自己感動的東西',['CREATOR'=>3,'FEELER'=>1],['OPENNESS'=>14,'SENSITIVITY'=>14])]],

['body'=>'你相信人是可以改變的嗎？','options'=>[$o('a','相信，而且我用行動證明過',['DRIVER'=>3,'EXPLORER'=>1],['ASSERTIVENESS'=>14,'OPENNESS'=>14]),$o('b','相信，但改變需要真正理解自己',['THINKER'=>3],['ANALYTICAL'=>16,'OPENNESS'=>10]),$o('c','相信，但需要對的環境和關係',['CONNECTOR'=>2,'FEELER'=>2],['SOCIABILITY'=>12,'SENSITIVITY'=>14]),$o('d','相信，改變本身就是生命最有意思的部分',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8])]],

['body'=>'你如何看待「失敗」？','options'=>[$o('a','失敗是調整方向的數據',['DRIVER'=>3,'THINKER'=>1],['ASSERTIVENESS'=>14,'ANALYTICAL'=>14]),$o('b','失敗是讓我更了解自己的機會',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>14,'SENSITIVITY'=>12]),$o('c','失敗讓我難受，但也讓我更有深度',['FEELER'=>3],['SENSITIVITY'=>18,'OPENNESS'=>8]),$o('d','失敗是探索的必要代價',['EXPLORER'=>3],['OPENNESS'=>18,'ASSERTIVENESS'=>10])]],

['body'=>'你最敬佩的人通常具有什麼特質？','options'=>[$o('a','把想法變成現實的行動力',['DRIVER'=>3],['ASSERTIVENESS'=>16,'OPENNESS'=>12]),$o('b','深刻的洞見和獨到的思考',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>16,'OPENNESS'=>10]),$o('c','對人的真誠和深厚的情感',['FEELER'=>3,'CONNECTOR'=>1],['SENSITIVITY'=>16,'SOCIABILITY'=>10]),$o('d','無畏地創造和探索的精神',['CREATOR'=>3,'EXPLORER'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8])]],

// ── Block 11: (questions 51-60) ────────────────────────────────────────
['body'=>'你覺得自己更像哪種學習者？','options'=>[$o('a','實作型：做中學',['DRIVER'=>3,'EXPLORER'=>1],['ASSERTIVENESS'=>14,'OPENNESS'=>14]),$o('b','概念型：先理解大架構',['THINKER'=>3],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('c','社交型：和別人討論和互動中學',['CONNECTOR'=>3],['SOCIABILITY'=>16,'OPENNESS'=>10]),$o('d','反思型：安靜消化後才真正吸收',['OBSERVER'=>3,'THINKER'=>1],['ANALYTICAL'=>14,'SENSITIVITY'=>12])]],

['body'=>'你和一個和你完全不同類型的人在一起，你的感受是？','options'=>[$o('a','有趣，差異讓對話更豐富',['EXPLORER'=>3,'CONNECTOR'=>1],['OPENNESS'=>16,'SOCIABILITY'=>12]),$o('b','有點費力，但也能學到東西',['THINKER'=>2,'OBSERVER'=>2],['ANALYTICAL'=>12,'SENSITIVITY'=>12]),$o('c','取決於他是否願意真誠交流',['CONNECTOR'=>2,'FEELER'=>2],['SOCIABILITY'=>12,'SENSITIVITY'=>14]),$o('d','讓我好奇他為什麼是這樣',['THINKER'=>3,'EXPLORER'=>1],['ANALYTICAL'=>14,'OPENNESS'=>14])]],

['body'=>'你對「規則」的態度比較接近？','options'=>[$o('a','規則是用來打破的（只要有更好的理由）',['EXPLORER'=>3,'DRIVER'=>1],['OPENNESS'=>16,'ASSERTIVENESS'=>12]),$o('b','規則有它的道理，先了解再說',['THINKER'=>3,'GUARDIAN'=>1],['ANALYTICAL'=>16,'OPENNESS'=>10]),$o('c','規則如果傷害人，就不是好規則',['FEELER'=>3],['SENSITIVITY'=>16,'OPENNESS'=>12]),$o('d','規則提供架構，但創意在邊界裡也能發揮',['CREATOR'=>3],['OPENNESS'=>14,'ANALYTICAL'=>14])]],

['body'=>'「真實」對你而言最重要的是什麼？','options'=>[$o('a','做你說的，說你做的',['DRIVER'=>3,'GUARDIAN'=>1],['ASSERTIVENESS'=>14,'ANALYTICAL'=>14]),$o('b','誠實地面對自己的想法和感受',['THINKER'=>2,'OBSERVER'=>2],['ANALYTICAL'=>14,'SENSITIVITY'=>12]),$o('c','在關係中不戴面具',['CONNECTOR'=>2,'FEELER'=>2],['SOCIABILITY'=>12,'SENSITIVITY'=>16]),$o('d','忠於自己的本質，不為外界改變',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>16,'ASSERTIVENESS'=>12])]],

['body'=>'你在哪種工作環境下最有生產力？','options'=>[$o('a','有挑戰目標、快節奏的環境',['DRIVER'=>3],['ASSERTIVENESS'=>16,'OPENNESS'=>12]),$o('b','有安靜、可以深度思考的環境',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>16,'SENSITIVITY'=>10]),$o('c','有合作和溝通、充滿人際互動的環境',['CONNECTOR'=>3],['SOCIABILITY'=>18,'OPENNESS'=>8]),$o('d','有創意空間、允許嘗試和犯錯的環境',['CREATOR'=>3,'EXPLORER'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8])]],

// ── Block 12: (questions 61-70) ────────────────────────────────────────
['body'=>'休假對你來說最重要的功能是？','options'=>[$o('a','讓我充電，準備好再出發',['DRIVER'=>3,'GUARDIAN'=>1],['ASSERTIVENESS'=>14,'OPENNESS'=>12]),$o('b','讓我思考和整理自己',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>16,'SENSITIVITY'=>10]),$o('c','讓我和重要的人深度連結',['CONNECTOR'=>2,'FEELER'=>2],['SOCIABILITY'=>14,'SENSITIVITY'=>14]),$o('d','讓我探索新的地方和體驗',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>18,'SOCIABILITY'=>8])]],

['body'=>'你最享受孤獨還是陪伴？','options'=>[$o('a','兩者都要，但陪伴給我更多動力',['DRIVER'=>2,'CONNECTOR'=>2],['ASSERTIVENESS'=>12,'SOCIABILITY'=>14]),$o('b','孤獨——我在獨處時最像自己',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>14,'SENSITIVITY'=>12]),$o('c','陪伴——真實的連結讓我感到完整',['CONNECTOR'=>3,'FEELER'=>1],['SOCIABILITY'=>16,'SENSITIVITY'=>14]),$o('d','孤獨讓我充電，陪伴讓我有意義',['FEELER'=>2,'OBSERVER'=>2],['SENSITIVITY'=>14,'SOCIABILITY'=>12])]],

['body'=>'關於「錢」，你的態度比較接近？','options'=>[$o('a','錢是工具，重要的是用它做什麼',['DRIVER'=>3,'EXPLORER'=>1],['ASSERTIVENESS'=>14,'OPENNESS'=>14]),$o('b','錢給我安全感和選擇的自由',['GUARDIAN'=>3,'THINKER'=>1],['ANALYTICAL'=>14,'ASSERTIVENESS'=>12]),$o('c','錢不是最重要的，但沒有很難自在',['FEELER'=>2,'CONNECTOR'=>2],['SENSITIVITY'=>12,'SOCIABILITY'=>12]),$o('d','錢讓我能追求我想要的體驗',['EXPLORER'=>3],['OPENNESS'=>16,'ASSERTIVENESS'=>12])]],

['body'=>'你更喜歡哪種閱讀體驗？','options'=>[$o('a','從書中學到可以立刻應用的知識',['DRIVER'=>3,'GUARDIAN'=>1],['ASSERTIVENESS'=>14,'ANALYTICAL'=>14]),$o('b','被帶入一個深度的思考系統',['THINKER'=>3],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('c','和書中人物有情感共鳴',['FEELER'=>3],['SENSITIVITY'=>18,'OPENNESS'=>8]),$o('d','被帶到一個完全不同的世界',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>18,'SENSITIVITY'=>8])]],

['body'=>'你在群體中通常是哪種角色？','options'=>[$o('a','帶頭的那個，或至少是推動者',['DRIVER'=>3,'ASSERTIVENESS'=>1],['ASSERTIVENESS'=>18,'SOCIABILITY'=>10]),$o('b','在角落觀察，偶爾說出最關鍵的話',['OBSERVER'=>3,'THINKER'=>1],['ANALYTICAL'=>14,'SENSITIVITY'=>12]),$o('c','那個讓每個人都感到被包含的人',['CONNECTOR'=>3,'FEELER'=>1],['SOCIABILITY'=>16,'SENSITIVITY'=>12]),$o('d','帶來意外角度或讓人耳目一新的那個',['CREATOR'=>3,'EXPLORER'=>1],['OPENNESS'=>16,'SOCIABILITY'=>10])]],

// ── Block 13: (questions 71-80) ────────────────────────────────────────
['body'=>'你如何看待「過去」？','options'=>[$o('a','過去是起點，我更在意現在要去哪',['DRIVER'=>3],['ASSERTIVENESS'=>14,'OPENNESS'=>14]),$o('b','過去是教材，我從中學習但不停留',['THINKER'=>3,'GUARDIAN'=>1],['ANALYTICAL'=>16,'OPENNESS'=>10]),$o('c','過去的感受是真實的，我會讓自己充分感受',['FEELER'=>3],['SENSITIVITY'=>18,'OPENNESS'=>8]),$o('d','過去塑造了我，但不定義我',['EXPLORER'=>2,'OBSERVER'=>2],['OPENNESS'=>14,'SENSITIVITY'=>12])]],

['body'=>'在意見衝突中，你更傾向？','options'=>[$o('a','堅持我的立場，直到有充分理由讓我改變',['DRIVER'=>3],['ASSERTIVENESS'=>18,'ANALYTICAL'=>8]),$o('b','仔細聽對方，評估邏輯是否更好',['THINKER'=>3],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('c','先理解彼此的感受，再討論誰對',['FEELER'=>3,'CONNECTOR'=>1],['SENSITIVITY'=>14,'SOCIABILITY'=>12]),$o('d','尋找兩者都能接受的新角度',['CREATOR'=>2,'CONNECTOR'=>2],['OPENNESS'=>14,'SOCIABILITY'=>12])]],

['body'=>'你如何處理你不喜歡但必須做的事？','options'=>[$o('a','快點做完，然後繼續我想做的事',['DRIVER'=>3],['ASSERTIVENESS'=>16,'OPENNESS'=>10]),$o('b','找出一個讓它有意義的理由',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>14,'SENSITIVITY'=>12]),$o('c','找一個人一起做，比較有動力',['CONNECTOR'=>3],['SOCIABILITY'=>16,'SENSITIVITY'=>10]),$o('d','把它變成一個創意或遊戲',['CREATOR'=>3,'EXPLORER'=>1],['OPENNESS'=>16,'ASSERTIVENESS'=>10])]],

['body'=>'你最害怕的失去是？','options'=>[$o('a','失去行動的能力或機會',['DRIVER'=>3,'GUARDIAN'=>1],['ASSERTIVENESS'=>14,'SENSITIVITY'=>12]),$o('b','失去思考的清晰和判斷力',['THINKER'=>3],['ANALYTICAL'=>18,'SENSITIVITY'=>8]),$o('c','失去重要的人際連結',['CONNECTOR'=>3,'FEELER'=>1],['SOCIABILITY'=>12,'SENSITIVITY'=>16]),$o('d','失去探索和成長的可能性',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8])]],

['body'=>'你如何看待「規律的日常」？','options'=>[$o('a','效率的基礎，我需要它',['DRIVER'=>3,'GUARDIAN'=>1],['ASSERTIVENESS'=>14,'ANALYTICAL'=>14]),$o('b','讓我可以把精力放在重要的思考上',['THINKER'=>3],['ANALYTICAL'=>16,'OPENNESS'=>10]),$o('c','需要，但也需要自發性的打破',['EXPLORER'=>2,'FEELER'=>2],['OPENNESS'=>14,'SENSITIVITY'=>12]),$o('d','限制了我的創意和探索',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8])]],

// ── Block 14: (questions 81-90) ────────────────────────────────────────
['body'=>'有人問你要不要接一個沒有薪水但很有意義的計畫，你的考量是？','options'=>[$o('a','意義夠大的話，值得',['FEELER'=>2,'EXPLORER'=>2],['SENSITIVITY'=>12,'OPENNESS'=>14]),$o('b','先評估付出和回報的比例',['THINKER'=>3,'GUARDIAN'=>1],['ANALYTICAL'=>16,'ASSERTIVENESS'=>10]),$o('c','看這個計畫是否和我在乎的人或事有關',['CONNECTOR'=>3,'FEELER'=>1],['SOCIABILITY'=>12,'SENSITIVITY'=>14]),$o('d','直覺說值得就做，後面的事後面再說',['DRIVER'=>3,'EXPLORER'=>1],['ASSERTIVENESS'=>14,'OPENNESS'=>14])]],

['body'=>'你覺得自己的內心世界和外在表現差距大嗎？','options'=>[$o('a','差距不大，我比較直接',['DRIVER'=>3],['ASSERTIVENESS'=>16,'OPENNESS'=>12]),$o('b','差距很大，外面看起來平靜，裡面很熱鬧',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>12,'SENSITIVITY'=>14]),$o('c','看關係而定，親近的人看到的才是真實的我',['FEELER'=>2,'CONNECTOR'=>2],['SENSITIVITY'=>14,'SOCIABILITY'=>12]),$o('d','我自己也不完全知道，有時讓自己感到驚訝',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>16,'SENSITIVITY'=>12])]],

['body'=>'你認為自己在哪個時間點做最好的思考？','options'=>[$o('a','在行動中，思考和行動同時發生',['DRIVER'=>3],['ASSERTIVENESS'=>16,'OPENNESS'=>10]),$o('b','在安靜的早晨或深夜，不受打擾',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>16,'SENSITIVITY'=>10]),$o('c','在和人討論的過程中，想法自然出現',['CONNECTOR'=>3],['SOCIABILITY'=>16,'OPENNESS'=>12]),$o('d','在漫無目的地做別的事的時候',['CREATOR'=>3,'EXPLORER'=>1],['OPENNESS'=>16,'SENSITIVITY'=>12])]],

['body'=>'你覺得現代社會最缺少的是？','options'=>[$o('a','更多願意承擔責任的人',['DRIVER'=>3,'GUARDIAN'=>1],['ASSERTIVENESS'=>16,'ANALYTICAL'=>10]),$o('b','更多願意深度思考的人',['THINKER'=>3],['ANALYTICAL'=>18,'OPENNESS'=>8]),$o('c','更多願意真誠連結的人',['CONNECTOR'=>3,'FEELER'=>1],['SOCIABILITY'=>14,'SENSITIVITY'=>14]),$o('d','更多願意打破框架的人',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8])]],

['body'=>'如果可以給 10 歲的自己一個建議，你會說？','options'=>[$o('a','勇敢行動，你比你以為的更有能力',['DRIVER'=>3],['ASSERTIVENESS'=>16,'OPENNESS'=>12]),$o('b','多問多想，這個世界比你看到的更複雜也更有趣',['THINKER'=>3,'EXPLORER'=>1],['ANALYTICAL'=>14,'OPENNESS'=>14]),$o('c','珍惜那些讓你感到真實的人',['CONNECTOR'=>2,'FEELER'=>2],['SOCIABILITY'=>12,'SENSITIVITY'=>16]),$o('d','保持好奇心，不要讓長大磨掉你的驚嘆號',['EXPLORER'=>3,'CREATOR'=>1],['OPENNESS'=>18,'SENSITIVITY'=>10])]],

// ── Block 15: (questions 91-100) ───────────────────────────────────────
['body'=>'對你來說，「信任」是怎麼建立的？','options'=>[$o('a','通過行動和時間，說到做到',['DRIVER'=>3,'GUARDIAN'=>1],['ASSERTIVENESS'=>14,'ANALYTICAL'=>12]),$o('b','通過真誠和透明的溝通',['THINKER'=>2,'CONNECTOR'=>2],['ANALYTICAL'=>12,'SOCIABILITY'=>14]),$o('c','通過情感的共鳴和真實的理解',['FEELER'=>3,'CONNECTOR'=>1],['SENSITIVITY'=>16,'SOCIABILITY'=>12]),$o('d','通過一起經歷有意義的事',['EXPLORER'=>2,'CONNECTOR'=>2],['OPENNESS'=>12,'SOCIABILITY'=>14])]],

['body'=>'你最享受哪種類型的對話？','options'=>[$o('a','目標明確、有決策產出的對話',['DRIVER'=>3],['ASSERTIVENESS'=>14,'ANALYTICAL'=>14]),$o('b','深度探索、沒有標準答案的對話',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>16,'OPENNESS'=>10]),$o('c','真誠坦率、彼此看見的對話',['FEELER'=>3,'CONNECTOR'=>1],['SENSITIVITY'=>14,'SOCIABILITY'=>14]),$o('d','天馬行空、在意想不到的地方落地的對話',['CREATOR'=>3,'EXPLORER'=>1],['OPENNESS'=>16,'SOCIABILITY'=>10])]],

['body'=>'你如何看待「隨波逐流」？','options'=>[$o('a','浪費時間，我有更重要的事要做',['DRIVER'=>3],['ASSERTIVENESS'=>16,'ANALYTICAL'=>12]),$o('b','危險，沒有自己思考的人容易被操控',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>16,'OPENNESS'=>10]),$o('c','取決於這股流是否有值得的方向',['CONNECTOR'=>2,'FEELER'=>2],['SOCIABILITY'=>12,'SENSITIVITY'=>14]),$o('d','有時候隨著流動能去到意想不到的地方',['EXPLORER'=>3],['OPENNESS'=>18,'SENSITIVITY'=>10])]],

['body'=>'你在什麼時候最能感受到「活著真好」？','options'=>[$o('a','在全力以赴並且看到成果的時候',['DRIVER'=>3,'EXPLORER'=>1],['ASSERTIVENESS'=>14,'OPENNESS'=>14]),$o('b','在理解了一個很深的道理的時候',['THINKER'=>3,'OBSERVER'=>1],['ANALYTICAL'=>16,'SENSITIVITY'=>10]),$o('c','在和某人有深刻連結的時候',['CONNECTOR'=>3,'FEELER'=>1],['SOCIABILITY'=>12,'SENSITIVITY'=>16]),$o('d','在創造了讓自己感動的東西的時候',['CREATOR'=>3,'FEELER'=>1],['OPENNESS'=>14,'SENSITIVITY'=>16])]],

['body'=>'最後，你覺得自己這輩子最想留下的是什麼？','options'=>[$o('a','一些真的被完成的、有影響力的事',['DRIVER'=>3,'GUARDIAN'=>1],['ASSERTIVENESS'=>14,'ANALYTICAL'=>12]),$o('b','對某個領域的深刻理解和貢獻',['THINKER'=>3],['ANALYTICAL'=>16,'OPENNESS'=>12]),$o('c','對重要的人的深遠影響和連結',['CONNECTOR'=>3,'FEELER'=>1],['SOCIABILITY'=>12,'SENSITIVITY'=>16]),$o('d','一些從未有人想到過的東西',['CREATOR'=>3,'EXPLORER'=>1],['OPENNESS'=>18,'ASSERTIVENESS'=>8])],
],
        ];
    }
}
