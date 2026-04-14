<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\ResultType;
use Illuminate\Database\Seeder;

class FriendshipQuizSeeder extends Seeder
{
    public function run(): void
    {
        $quiz = Quiz::create([
            'title'       => '你在朋友圈中扮演什麼角色？',
            'description' => '10 道友情情境題，找出你在友誼中最自然的姿態，了解你如何連結、支持，並影響你身邊的人。',
            'slug'        => 'friendship-role',
            'price'       => 4900,
            'is_active'   => true,
            'meta'        => [
                'emoji'             => '🫂',
                'card_bg'           => 'from-emerald-500 to-teal-500',
                'card_light'        => 'from-emerald-50 to-teal-50',
                'tag'               => '友情測驗',
                'estimated_minutes' => 5,
                'tags'              => ['友情', '社交風格', '人際關係'],
            ],
        ]);

        $types = [
            ['code'=>'SUNSHINE','title'=>'開心果 ☀️','description'=>'你是朋友圈的陽光，無論去哪裡都能炒熱氣氛，讓大家感到開心。你的笑聲和幽默是聚會中不可缺少的調味料。','sort_order'=>1,'meta'=>['emoji'=>'☀️'],'report_content'=>'<h2>開心果 ☀️</h2><p>你的存在本身就是禮物。你讓身邊的人笑，讓沉悶的空氣流動起來，這是一種少有的天賦。</p><h3>友情優勢</h3><ul><li>讓朋友在你身邊感到輕鬆愉快</li><li>善於活絡氣氛，打破尷尬</li></ul><h3>成長方向</h3><ul><li>在朋友需要認真傾訴時，放下搞笑的慣性，靜靜陪伴</li></ul>'],
            ['code'=>'GUARDIAN','title'=>'守護者 🛡️','description'=>'你是朋友的堅實後盾，總是在他們需要的時候出現。你默默付出，讓朋友知道「我在」這兩個字的重量。','sort_order'=>2,'meta'=>['emoji'=>'🛡️'],'report_content'=>'<h2>守護者 🛡️</h2><p>你的忠誠和可靠是無價的。你是那種「只要我在，你不用怕」的朋友。</p><h3>友情優勢</h3><ul><li>值得信任，讓朋友感到安全</li><li>在危機時刻能夠穩住局面</li></ul><h3>成長方向</h3><ul><li>學習也向朋友開口求助，讓關係更對等</li></ul>'],
            ['code'=>'LISTENER','title'=>'傾聽者 🌙','description'=>'你是朋友最好的樹洞。你有耐心、有同理心，讓每個向你傾訴的人都感到被看見、被理解。','sort_order'=>3,'meta'=>['emoji'=>'🌙'],'report_content'=>'<h2>傾聽者 🌙</h2><p>你的沉靜是一種力量。當世界太吵，朋友會來找你，因為你能讓他們慢下來。</p><h3>友情優勢</h3><ul><li>朋友在你面前感到安全，願意說出真心話</li><li>善於理解他人的感受</li></ul><h3>成長方向</h3><ul><li>也分享自己的故事，讓朋友有機會回饋你</li></ul>'],
            ['code'=>'PLANNER','title'=>'策劃師 📋','description'=>'你是那個把想法變成現實的人。聚會、旅行、慶生，因為有你，大家的計畫才不會永遠停在「改天吧」。','sort_order'=>4,'meta'=>['emoji'=>'📋'],'report_content'=>'<h2>策劃師 📋</h2><p>你讓美好的事情真正發生。沒有你，很多快樂時光可能永遠只是「我們有天要去」。</p><h3>友情優勢</h3><ul><li>把大家的想法整合成可執行的計畫</li><li>細心照顧每個人的需求與喜好</li></ul><h3>成長方向</h3><ul><li>有時放手讓事情自然流動，享受計畫外的驚喜</li></ul>'],
            ['code'=>'EXPLORER','title'=>'探險家 🗺️','description'=>'你是朋友圈裡的開拓者，總是帶來新的想法、新的地點、新的體驗。和你在一起，生活永遠不無聊。','sort_order'=>5,'meta'=>['emoji'=>'🗺️'],'report_content'=>'<h2>探險家 🗺️</h2><p>你讓朋友的生命地圖不斷擴大。你帶來的不只是新地點，還有新的視角與可能性。</p><h3>友情優勢</h3><ul><li>帶給朋友新鮮感與活力</li><li>鼓勵朋友走出舒適圈</li></ul><h3>成長方向</h3><ul><li>在朋友需要穩定時，也能給予踏實的陪伴</li></ul>'],
            ['code'=>'SAGE','title'=>'智囊 🧠','description'=>'朋友遇到問題，第一個想到的就是你。你擅長分析狀況，給出有深度的建議，是大家的「人生顧問」。','sort_order'=>6,'meta'=>['emoji'=>'🧠'],'report_content'=>'<h2>智囊 🧠</h2><p>你的洞見讓朋友在迷霧中找到方向。你的建議不只解決問題，還讓人成長。</p><h3>友情優勢</h3><ul><li>能夠客觀看待問題，給出理性建議</li><li>幫助朋友看到他們看不到的盲點</li></ul><h3>成長方向</h3><ul><li>在給建議前，先問問朋友是想要「被傾聽」還是「被解決」</li></ul>'],
            ['code'=>'PEACEMAKER','title'=>'和事佬 🕊️','description'=>'朋友之間有摩擦，你總是那個居中調解的人。你有能力讓雙方都感到被理解，讓關係回到平靜。','sort_order'=>7,'meta'=>['emoji'=>'🕊️'],'report_content'=>'<h2>和事佬 🕊️</h2><p>你是朋友圈的黏著劑。沒有你，很多關係可能早已裂縫加深。</p><h3>友情優勢</h3><ul><li>善於找到各方的共同點</li><li>讓衝突在你手中化解為理解</li></ul><h3>成長方向</h3><ul><li>在調解別人的同時，也要有自己的立場和界線</li></ul>'],
            ['code'=>'WILD_CARD','title'=>'神秘自在族 🌀','description'=>'你是那個不按常理出牌的朋友，充滿驚喜又捉摸不定。朋友對你充滿好奇，從不知道和你在一起下一秒會發生什麼。','sort_order'=>8,'meta'=>['emoji'=>'🌀'],'report_content'=>'<h2>神秘自在族 🌀</h2><p>你的不可預測是一種魔力。朋友永遠不知道你會帶來什麼，但知道一定不平凡。</p><h3>友情優勢</h3><ul><li>讓友誼充滿驚喜和新鮮感</li><li>不在乎世俗眼光，帶朋友看見不同可能</li></ul><h3>成長方向</h3><ul><li>讓朋友更了解你，敞開一點讓人走近</li></ul>'],
        ];

        foreach ($types as $t) {
            ResultType::create(array_merge(['quiz_id' => $quiz->id], $t));
        }

        $questions = [
            ['body'=>'朋友群組說要出去玩，但討論了半天沒有人下決定。你會？','options'=>[['key'=>'a','label'=>'跳出來定時間地點，直接定案。','scores'=>['PLANNER'=>4]],['key'=>'b','label'=>'表示隨意，等大家的結論。','scores'=>['LISTENER'=>2,'WILD_CARD'=>2]],['key'=>'c','label'=>'提一個大家沒想過的有趣地點。','scores'=>['EXPLORER'=>3,'SUNSHINE'=>1]],['key'=>'d','label'=>'私下問比較主動的朋友，一起推動這件事。','scores'=>['GUARDIAN'=>2,'PLANNER'=>2]]]],
            ['body'=>'朋友深夜傳訊息說心情很差。你的第一反應是？','options'=>[['key'=>'a','label'=>'立刻打電話，問他怎麼了。','scores'=>['GUARDIAN'=>3,'LISTENER'=>2]],['key'=>'b','label'=>'傳一個溫暖的訊息：「我在，你說吧。」','scores'=>['LISTENER'=>4]],['key'=>'c','label'=>'傳幾個搞笑的圖，讓他先笑一笑。','scores'=>['SUNSHINE'=>3,'WILD_CARD'=>1]],['key'=>'d','label'=>'問他想聊嗎，還是只是需要有人知道。','scores'=>['SAGE'=>3,'LISTENER'=>2]]]],
            ['body'=>'兩個好朋友吵架了，都來找你訴苦。你怎麼辦？','options'=>[['key'=>'a','label'=>'各自聽他們說，然後嘗試讓他們重新溝通。','scores'=>['PEACEMAKER'=>4]],['key'=>'b','label'=>'聽著，但不站任何一邊。','scores'=>['LISTENER'=>3,'SAGE'=>1]],['key'=>'c','label'=>'幫雙方分析問題所在，提出解決方案。','scores'=>['SAGE'=>3,'PLANNER'=>1]],['key'=>'d','label'=>'讓他們知道你很在乎，但這件事需要他們自己解決。','scores'=>['GUARDIAN'=>2,'WILD_CARD'=>2]]]],
            ['body'=>'慶生活動，你最喜歡負責哪個環節？','options'=>[['key'=>'a','label'=>'整個活動的策劃與安排。','scores'=>['PLANNER'=>4]],['key'=>'b','label'=>'負責炒熱氣氛、帶動大家的情緒。','scores'=>['SUNSHINE'=>4]],['key'=>'c','label'=>'準備一個出人意料的驚喜。','scores'=>['WILD_CARD'=>3,'EXPLORER'=>1]],['key'=>'d','label'=>'確保每個人都玩得開心，沒有人被冷落。','scores'=>['GUARDIAN'=>3,'PEACEMAKER'=>1]]]],
            ['body'=>'朋友說想去一個你覺得不太安全的地方旅行，你會？','options'=>[['key'=>'a','label'=>'提出擔憂，但如果他堅持，幫他做好準備。','scores'=>['GUARDIAN'=>3,'PLANNER'=>1]],['key'=>'b','label'=>'立刻查資料，分析利弊給他看。','scores'=>['SAGE'=>3,'PLANNER'=>1]],['key'=>'c','label'=>'問他願不願意換個一樣有趣的地點。','scores'=>['PEACEMAKER'=>2,'EXPLORER'=>2]],['key'=>'d','label'=>'說要跟他一起去，多一個人也比較安全。','scores'=>['GUARDIAN'=>4]]]],
            ['body'=>'你最享受的朋友聚會方式是？','options'=>[['key'=>'a','label'=>'大群人的熱鬧聚會，越多人越好。','scores'=>['SUNSHINE'=>3,'EXPLORER'=>1]],['key'=>'b','label'=>'幾個要好的朋友，深夜長聊。','scores'=>['LISTENER'=>3,'SAGE'=>1]],['key'=>'c','label'=>'一起做某件有意義或刺激的事。','scores'=>['EXPLORER'=>3,'PLANNER'=>1]],['key'=>'d','label'=>'不管做什麼，有你在乎的人就好。','scores'=>['GUARDIAN'=>3,'COMPANION'=>1,'LISTENER'=>1]]]],
            ['body'=>'朋友要做一個你覺得會後悔的決定，你會？','options'=>[['key'=>'a','label'=>'誠實說出你的想法，但尊重他的選擇。','scores'=>['SAGE'=>3,'GUARDIAN'=>1]],['key'=>'b','label'=>'問他為什麼這樣決定，了解他的思考後再說話。','scores'=>['LISTENER'=>3,'SAGE'=>1]],['key'=>'c','label'=>'支持他，出了事再一起面對。','scores'=>['GUARDIAN'=>3,'SUNSHINE'=>1]],['key'=>'d','label'=>'先不說，等他自己體會。','scores'=>['WILD_CARD'=>3,'LISTENER'=>1]]]],
            ['body'=>'朋友問你：「我怎麼了？」你通常給的是？','options'=>[['key'=>'a','label'=>'分析他的行為模式，提出具體觀察。','scores'=>['SAGE'=>4]],['key'=>'b','label'=>'說：「你其實超厲害的，只是你自己沒發現。」','scores'=>['SUNSHINE'=>3,'GUARDIAN'=>1]],['key'=>'c','label'=>'問他自己覺得怎麼了，引導他自己找答案。','scores'=>['LISTENER'=>3,'SAGE'=>1]],['key'=>'d','label'=>'帶他去做一件新的事，轉換心情再說。','scores'=>['EXPLORER'=>3,'WILD_CARD'=>1]]]],
            ['body'=>'朋友說你是他生命中很重要的人，你的第一反應？','options'=>[['key'=>'a','label'=>'回他：「你也是！」然後感到溫暖。','scores'=>['GUARDIAN'=>2,'LISTENER'=>2]],['key'=>'b','label'=>'有點不習慣，但心裡很感動。','scores'=>['WILD_CARD'=>2,'SAGE'=>2]],['key'=>'c','label'=>'思考你為他做過什麼，確認自己值得這句話。','scores'=>['GIVER'=>3,'PLANNER'=>1]],['key'=>'d','label'=>'立刻想到你們一起走過的種種，充滿感謝。','scores'=>['COMPANION'=>3,'SUNSHINE'=>1]]]],
            ['body'=>'用一句話描述你對友情的信念？','options'=>[['key'=>'a','label'=>'「好的朋友不需要天天聯絡，但永遠在你需要時出現。」','scores'=>['GUARDIAN'=>4]],['key'=>'b','label'=>'「友誼是一起笑、一起哭過的那些時刻。」','scores'=>['LISTENER'=>2,'SUNSHINE'=>2]],['key'=>'c','label'=>'「真正的朋友是敢告訴你真相的人。」','scores'=>['SAGE'=>3,'GUARDIAN'=>1]],['key'=>'d','label'=>'「和朋友在一起，每次都像新的冒險。」','scores'=>['EXPLORER'=>3,'WILD_CARD'=>1]]]],
        ];

        foreach ($questions as $i => $q) {
            QuizQuestion::create([
                'quiz_id'=>$quiz->id,'body'=>$q['body'],'type'=>'single_choice',
                'options'=>$q['options'],'sort_order'=>$i+1,'is_required'=>true,
            ]);
        }
    }
}
