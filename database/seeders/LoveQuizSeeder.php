<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\ResultType;
use Illuminate\Database\Seeder;

class LoveQuizSeeder extends Seeder
{
    public function run(): void
    {
        $quiz = Quiz::create([
            'title'       => '你的愛情依附風格',
            'description' => '10 道戀愛情境題，揭開你在愛情中的相處模式，了解你如何依附、如何給予與接受愛。',
            'slug'        => 'love-attachment-style',
            'price'       => 19,
            'is_active'   => true,
            'meta'        => [
                'emoji'             => '💕',
                'card_bg'           => 'from-rose-500 to-pink-500',
                'card_light'        => 'from-rose-50 to-pink-50',
                'tag'               => '愛情測驗',
                'estimated_minutes' => 5,
                'tags'              => ['愛情', '依附風格', '親密關係'],
                'collection'        => 'relationship',
                'collection_order'  => 1,
            ],
        ]);

        $this->seedResultTypes($quiz);
        $this->seedQuestions($quiz);
    }

    private function seedResultTypes(Quiz $quiz): void
    {
        $types = [
            [
                'code'  => 'SECURE',
                'title' => '安全依附型 🌟',
                'description' => '你在愛情中自在而穩定，能夠給予伴侶充分的安全感，也能自在地接受對方的愛。你相信自己值得被愛，也相信伴侶是可信賴的。',
                'report_content' => '<h2>安全依附型 🌟</h2><p>你是愛情中最健康的狀態。你不依賴伴侶來定義自我價值，也不因關係中的小波折而失去安全感。</p><h3>戀愛優勢</h3><ul><li>能夠清楚表達需求與感受</li><li>衝突後容易和好，不記恨</li><li>給予伴侶足夠的空間與信任</li></ul><h3>成長方向</h3><ul><li>留意伴侶的依附風格，給予對方所需的支持方式</li></ul>',
                'sort_order' => 1,
                'meta' => ['emoji' => '🌟'],
            ],
            [
                'code'  => 'ANXIOUS',
                'title' => '焦慮依附型 🌊',
                'description' => '你在愛情中容易感到不安，擔心伴侶不夠愛你或會離開你。你渴望親密，卻也因這份渴望而容易過度依賴對方的回應。',
                'report_content' => '<h2>焦慮依附型 🌊</h2><p>你的心對愛情是敞開的，這正是你最美的地方——但敞開的門有時也讓風雨直接打進來。</p><h3>戀愛優勢</h3><ul><li>情感豐富，善於表達愛意</li><li>對伴侶的感受非常敏銳</li><li>願意為關係全力投入</li></ul><h3>成長方向</h3><ul><li>練習在伴侶沒有立刻回應時，先照顧自己的情緒</li><li>培養獨立的興趣與人際關係，降低對伴侶的情緒依賴</li></ul>',
                'sort_order' => 2,
                'meta' => ['emoji' => '🌊'],
            ],
            [
                'code'  => 'AVOIDANT',
                'title' => '逃避依附型 🦋',
                'description' => '你習慣保持情感距離，面對親密關係時容易感到窒息。你重視獨立，雖然渴望連結，但又害怕太深入的情感會讓你失去自我。',
                'report_content' => '<h2>逃避依附型 🦋</h2><p>你的獨立不是冷漠，是過去學到的保護自己的方式。真正的親密，需要你一步一步重新學習相信。</p><h3>戀愛優勢</h3><ul><li>不黏人，給伴侶充足的個人空間</li><li>情緒穩定，不容易被激怒</li><li>重視品質而非數量的關係</li></ul><h3>成長方向</h3><ul><li>練習在安全的環境中說出自己的感受</li><li>允許自己需要對方，這不是示弱而是勇氣</li></ul>',
                'sort_order' => 3,
                'meta' => ['emoji' => '🦋'],
            ],
            [
                'code'  => 'ROMANTIC',
                'title' => '浪漫理想型 🌸',
                'description' => '你對愛情有豐富的想像與期待，相信真愛的存在。你的愛情觀充滿詩意，容易被愛情中的美好瞬間所打動。',
                'report_content' => '<h2>浪漫理想型 🌸</h2><p>你是愛情的詩人，能在平凡的日常中發現浪漫的細節。這份敏感讓你的愛情充滿色彩。</p><h3>戀愛優勢</h3><ul><li>善於製造驚喜與浪漫氛圍</li><li>對伴侶的付出充滿創意</li><li>珍視每一段感情的特別之處</li></ul><h3>成長方向</h3><ul><li>學習欣賞平淡中的深情</li><li>與伴侶溝通現實的期待值</li></ul>',
                'sort_order' => 4,
                'meta' => ['emoji' => '🌸'],
            ],
            [
                'code'  => 'RATIONAL',
                'title' => '理性主導型 🧩',
                'description' => '你在愛情中習慣用邏輯思考，理性評估關係的發展。你重視相互的成長與配合度，而不只是一時的激情。',
                'report_content' => '<h2>理性主導型 🧩</h2><p>你把愛情當成一段需要共同維護的關係，而不是一場夢。這份清醒讓你的關係更穩固長久。</p><h3>戀愛優勢</h3><ul><li>溝通理性清晰，不容易陷入爭吵漩渦</li><li>對關係有長遠的規劃</li><li>能夠在情緒激動時保持冷靜</li></ul><h3>成長方向</h3><ul><li>練習讓自己偶爾感性一點，讓對方感受到你的溫度</li></ul>',
                'sort_order' => 5,
                'meta' => ['emoji' => '🧩'],
            ],
            [
                'code'  => 'FREE_SPIRIT',
                'title' => '自由靈魂型 🌈',
                'description' => '你重視個人自由，即使在關係中也需要保留自己的獨立空間。你享受愛情，但不願讓愛成為束縛。',
                'report_content' => '<h2>自由靈魂型 🌈</h2><p>愛對你來說是生命的一部分，而不是全部。這種平衡讓你的關係充滿生機，也讓伴侶有空間呼吸。</p><h3>戀愛優勢</h3><ul><li>不控制伴侶，給予充分的自由</li><li>對關係不設限，能夠適應不同的愛情模式</li><li>內心豐盛，不依賴伴侶填補空缺</li></ul><h3>成長方向</h3><ul><li>練習在關鍵時刻承諾與投入，讓對方感到被重視</li></ul>',
                'sort_order' => 6,
                'meta' => ['emoji' => '🌈'],
            ],
            [
                'code'  => 'GIVER',
                'title' => '付出型 💝',
                'description' => '你在愛情中傾向付出多於接受，以對方的需求為優先。你的愛深沉而無私，但有時也因付出太多而感到疲憊。',
                'report_content' => '<h2>付出型 💝</h2><p>你的愛是行動，是默默的守護與照顧。這份愛深沉而有力，但要記得：你也值得被好好愛。</p><h3>戀愛優勢</h3><ul><li>體貼入微，善於照顧伴侶</li><li>對關係高度投入與負責</li><li>讓伴侶感到被珍視與呵護</li></ul><h3>成長方向</h3><ul><li>學習也接受對方的付出，不要總是推開善意</li><li>設立健康的界線，保護自己的能量</li></ul>',
                'sort_order' => 7,
                'meta' => ['emoji' => '💝'],
            ],
            [
                'code'  => 'COMPANION',
                'title' => '陪伴共生型 🌿',
                'description' => '你重視和伴侶一起成長的過程，愛對你來說是一種並肩前行的夥伴關係。你享受平淡而真實的陪伴勝過激情。',
                'report_content' => '<h2>陪伴共生型 🌿</h2><p>你的愛是日積月累的溫暖，像一棵樹慢慢生長。你的關係不靠激情維繫，而靠彼此的理解與陪伴。</p><h3>戀愛優勢</h3><ul><li>關係穩定長久，不容易因小事動搖</li><li>善於在日常中維繫感情</li><li>讓伴侶有強烈的歸屬感與安心感</li></ul><h3>成長方向</h3><ul><li>偶爾製造驚喜，讓關係保持新鮮感</li></ul>',
                'sort_order' => 8,
                'meta' => ['emoji' => '🌿'],
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
                'body' => '和喜歡的人傳訊息，對方已讀卻遲遲沒回覆。你會怎麼想？',
                'options' => [
                    ['key'=>'a','label'=>'應該是在忙，等他有空自然會回。','scores'=>['SECURE'=>3,'RATIONAL'=>1]],
                    ['key'=>'b','label'=>'是不是我說了什麼讓他不開心？開始反覆看自己的訊息。','scores'=>['ANXIOUS'=>3,'ROMANTIC'=>1]],
                    ['key'=>'c','label'=>'還好，我也不急著聊。','scores'=>['AVOIDANT'=>3,'FREE_SPIRIT'=>2]],
                    ['key'=>'d','label'=>'有點在意，但繼續做自己的事，待會再看看。','scores'=>['SECURE'=>2,'COMPANION'=>2]],
                ],
            ],
            [
                'body' => '伴侶說想要更多的「個人空間」。你的第一反應是？',
                'options' => [
                    ['key'=>'a','label'=>'完全理解，我也需要自己的時間。','scores'=>['AVOIDANT'=>3,'FREE_SPIRIT'=>3]],
                    ['key'=>'b','label'=>'有點失落，但尊重他的需求。','scores'=>['SECURE'=>2,'GIVER'=>2]],
                    ['key'=>'c','label'=>'心裡有點慌，開始懷疑是不是關係出了問題。','scores'=>['ANXIOUS'=>4]],
                    ['key'=>'d','label'=>'一起討論什麼叫「適當的個人空間」，找到共識。','scores'=>['RATIONAL'=>3,'SECURE'=>1]],
                ],
            ],
            [
                'body' => '你最期待愛情中的哪種時刻？',
                'options' => [
                    ['key'=>'a','label'=>'一起窩在沙發上什麼都不說，就覺得很幸福。','scores'=>['COMPANION'=>4,'SECURE'=>1]],
                    ['key'=>'b','label'=>'他突然做了一件很浪漫的驚喜。','scores'=>['ROMANTIC'=>4,'ANXIOUS'=>1]],
                    ['key'=>'c','label'=>'深夜的長談，聊到彼此最深的秘密。','scores'=>['SECURE'=>2,'GIVER'=>2]],
                    ['key'=>'d','label'=>'兩人一起完成了某個共同目標。','scores'=>['RATIONAL'=>3,'COMPANION'=>2]],
                ],
            ],
            [
                'body' => '分手後，你通常怎麼處理這段感情？',
                'options' => [
                    ['key'=>'a','label'=>'給自己一段時間沉澱，再慢慢回到生活軌道。','scores'=>['SECURE'=>3,'RATIONAL'=>1]],
                    ['key'=>'b','label'=>'很難放下，會一直反思哪裡出了問題。','scores'=>['ANXIOUS'=>4]],
                    ['key'=>'c','label'=>'比較容易切斷，不太願意回頭看。','scores'=>['AVOIDANT'=>4]],
                    ['key'=>'d','label'=>'把心情轉化成對未來的動力，認識新的可能。','scores'=>['FREE_SPIRIT'=>3,'ROMANTIC'=>1]],
                ],
            ],
            [
                'body' => '你喜歡的人說你「太依賴」他。你會怎麼回應？',
                'options' => [
                    ['key'=>'a','label'=>'認真聽他說，一起討論如何調整相處方式。','scores'=>['SECURE'=>3,'RATIONAL'=>2]],
                    ['key'=>'b','label'=>'心裡很受傷，不知道怎麼辦才好。','scores'=>['ANXIOUS'=>3,'GIVER'=>1]],
                    ['key'=>'c','label'=>'退一步，給彼此更多距離。','scores'=>['AVOIDANT'=>3,'FREE_SPIRIT'=>1]],
                    ['key'=>'d','label'=>'反思自己的行為，嘗試培養更多獨立性。','scores'=>['RATIONAL'=>2,'COMPANION'=>2]],
                ],
            ],
            [
                'body' => '當你喜歡一個人，你通常怎麼表達？',
                'options' => [
                    ['key'=>'a','label'=>'直接說出口，或用行動表明心意。','scores'=>['SECURE'=>3,'ROMANTIC'=>1]],
                    ['key'=>'b','label'=>'用小細節和日常關心慢慢展現。','scores'=>['COMPANION'=>3,'GIVER'=>2]],
                    ['key'=>'c','label'=>'等對方先表示，我不太主動說。','scores'=>['AVOIDANT'=>3,'RATIONAL'=>1]],
                    ['key'=>'d','label'=>'製造特別的時刻，讓他感受到你的心意。','scores'=>['ROMANTIC'=>4]],
                ],
            ],
            [
                'body' => '伴侶最近比較忙，你們聯絡的頻率降低了。你的感受？',
                'options' => [
                    ['key'=>'a','label'=>'理解，也剛好有自己的事情做。','scores'=>['AVOIDANT'=>3,'FREE_SPIRIT'=>2]],
                    ['key'=>'b','label'=>'有點在意，但相信他是真的很忙。','scores'=>['SECURE'=>3,'COMPANION'=>1]],
                    ['key'=>'c','label'=>'開始不安，很想主動聯繫但又怕打擾他。','scores'=>['ANXIOUS'=>4]],
                    ['key'=>'d','label'=>'趁這段時間充實自己，等他有空再約。','scores'=>['SECURE'=>2,'RATIONAL'=>2]],
                ],
            ],
            [
                'body' => '你心目中理想的週末兩人時光是？',
                'options' => [
                    ['key'=>'a','label'=>'一起去探索新的地方或嘗試新事物。','scores'=>['FREE_SPIRIT'=>3,'ROMANTIC'=>2]],
                    ['key'=>'b','label'=>'在家裡，各自做自己的事，偶爾聊兩句。','scores'=>['AVOIDANT'=>3,'COMPANION'=>2]],
                    ['key'=>'c','label'=>'從早到晚黏在一起，做什麼都好。','scores'=>['ANXIOUS'=>2,'GIVER'=>2,'ROMANTIC'=>1]],
                    ['key'=>'d','label'=>'有計畫地安排活動，又有屬於彼此的深聊時光。','scores'=>['RATIONAL'=>2,'SECURE'=>2,'COMPANION'=>1]],
                ],
            ],
            [
                'body' => '你最害怕在愛情中發生什麼事？',
                'options' => [
                    ['key'=>'a','label'=>'被拋棄或被冷落。','scores'=>['ANXIOUS'=>4]],
                    ['key'=>'b','label'=>'失去自我，被關係束縛住。','scores'=>['AVOIDANT'=>3,'FREE_SPIRIT'=>2]],
                    ['key'=>'c','label'=>'彼此之間失去了真誠與信任。','scores'=>['SECURE'=>3,'COMPANION'=>1]],
                    ['key'=>'d','label'=>'兩人停止成長，陷入一成不變。','scores'=>['RATIONAL'=>3,'ROMANTIC'=>1]],
                ],
            ],
            [
                'body' => '用一句話形容你對愛情的信念？',
                'options' => [
                    ['key'=>'a','label'=>'「愛是相互的成長，也是彼此的陪伴。」','scores'=>['COMPANION'=>3,'SECURE'=>2]],
                    ['key'=>'b','label'=>'「愛就是那種心跳加速、無法自拔的感覺。」','scores'=>['ROMANTIC'=>4,'ANXIOUS'=>1]],
                    ['key'=>'c','label'=>'「愛是一份選擇，不是一種依賴。」','scores'=>['FREE_SPIRIT'=>3,'RATIONAL'=>2]],
                    ['key'=>'d','label'=>'「愛是在對方最需要的時候，你就在那裡。」','scores'=>['GIVER'=>3,'SECURE'=>2]],
                ],
            ],
        ];

        foreach ($questions as $i => $q) {
            QuizQuestion::create([
                'quiz_id'    => $quiz->id,
                'body'       => $q['body'],
                'type'       => 'single_choice',
                'options'    => $q['options'],
                'sort_order' => $i + 1,
                'is_required'=> true,
            ]);
        }
    }
}
