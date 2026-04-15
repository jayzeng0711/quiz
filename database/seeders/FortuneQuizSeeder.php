<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\ResultType;
use Illuminate\Database\Seeder;

class FortuneQuizSeeder extends Seeder
{
    public function run(): void
    {
        $quiz = Quiz::create([
            'title'       => '你現在的能量狀態是什麼？',
            'description' => '10 道反思題，感知你目前的生命能量與內在狀態，找出最適合你現在的前進方式。',
            'slug'        => 'energy-state',
            'price'       => 4900,
            'is_active'   => true,
            'meta'        => [
                'emoji'             => '🔮',
                'card_bg'           => 'from-violet-500 to-purple-600',
                'card_light'        => 'from-violet-50 to-purple-50',
                'tag'               => '近期運勢',
                'estimated_minutes' => 5,
                'tags'              => ['運勢', '能量', '自我探索'],
                'collection'        => 'energy',
                'collection_order'  => 1,
            ],
        ]);

        $types = [
            ['code'=>'RISING','title'=>'上升期 🚀','description'=>'你現在的能量正在爆發，機會接連出現，行動力旺盛。這是衝刺的好時機，你踏出的每一步都會比平時更有力道。','sort_order'=>1,'meta'=>['emoji'=>'🚀'],'report_content'=>'<h2>上升期 🚀</h2><p>你的內在引擎已經發動，現在正是加速前進的時候。</p><h3>這個階段的你</h3><ul><li>精力充沛，計畫容易推進</li><li>社交能量強，容易吸引好機會</li></ul><h3>建議</h3><ul><li>把握這段時間，處理長期擱置的重要事項</li><li>設定一個你一直想追求的目標，現在最有動力</li></ul>'],
            ['code'=>'SORTING','title'=>'整頓期 🧹','description'=>'你正在梳理自己的生活與內心，清理不再需要的東西。這個階段看似靜止，其實是為下一段高峰做準備。','sort_order'=>2,'meta'=>['emoji'=>'🧹'],'report_content'=>'<h2>整頓期 🧹</h2><p>清空是為了填入更好的東西。你現在做的整理，會讓未來的自己感謝你。</p><h3>這個階段的你</h3><ul><li>對生活有更高的要求，不再將就</li><li>重新評估關係、目標與優先順序</li></ul><h3>建議</h3><ul><li>清理生活環境，騰出物理與心理空間</li><li>斷捨離不再對你有意義的事與人</li></ul>'],
            ['code'=>'TRANSFORM','title'=>'蛻變期 🦋','description'=>'你正在經歷深層的改變，舊的你正在脫殼。這個過程也許不舒服，但你正在成為一個更完整的自己。','sort_order'=>3,'meta'=>['emoji'=>'🦋'],'report_content'=>'<h2>蛻變期 🦋</h2><p>蛻變從來不舒服，但出來的那一刻，你會明白一切都值得。</p><h3>這個階段的你</h3><ul><li>對舊有模式感到不滿，渴望改變</li><li>容易有強烈的情緒波動，這是正常現象</li></ul><h3>建議</h3><ul><li>允許自己還沒有答案，在不確定中繼續前進</li><li>找一個能見證你改變的人，讓他陪你走這段路</li></ul>'],
            ['code'=>'HARVEST','title'=>'豐收期 🌾','description'=>'你過去努力的種子正在結果，生活各方面開始有所回報。學習接受這份豐盛，讓自己好好享受。','sort_order'=>4,'meta'=>['emoji'=>'🌾'],'report_content'=>'<h2>豐收期 🌾</h2><p>你的努力有了回應。不要吝嗇讓自己感受這份喜悅。</p><h3>這個階段的你</h3><ul><li>事情開始按計畫推進，有種一切到位的感覺</li><li>容易感到感激與滿足</li></ul><h3>建議</h3><ul><li>記錄下這段時間的成就與感受，留作未來的動力</li><li>把這份豐盛分享給身邊的人</li></ul>'],
            ['code'=>'PLANTING','title'=>'播種期 🌱','description'=>'你正在埋下未來的種子，很多事還沒有結果，但你的努力和投入都是有意義的。耐心是你現在最大的功課。','sort_order'=>5,'meta'=>['emoji'=>'🌱'],'report_content'=>'<h2>播種期 🌱</h2><p>你現在做的，是在為未來澆水。雖然看不到花，但根正在長。</p><h3>這個階段的你</h3><ul><li>充滿計畫與想法，但成果還未顯現</li><li>需要信任過程，而不只是結果</li></ul><h3>建議</h3><ul><li>保持穩定的日常節奏，讓自己不依賴情緒波動而行動</li><li>設定小里程碑，讓進步可見</li></ul>'],
            ['code'=>'REST','title'=>'休息期 🌙','description'=>'你的身心正在發出需要休息的信號。放慢腳步不是退步，而是為了之後更有力量地前進。','sort_order'=>6,'meta'=>['emoji'=>'🌙'],'report_content'=>'<h2>休息期 🌙</h2><p>停下來不是失敗，是智慧。讓自己充電，你才能繼續發光。</p><h3>這個階段的你</h3><ul><li>容易感到疲倦，對事情的熱情暫時降低</li><li>需要更多的獨處時間與睡眠</li></ul><h3>建議</h3><ul><li>減少不必要的社交，優先照顧自己</li><li>做一件純粹讓你放鬆而不帶目的性的事</li></ul>'],
            ['code'=>'IGNITE','title'=>'啟動期 ⚡','description'=>'有什麼東西正在你內心被點燃，一個新的想法、目標或渴望正在形成。你感覺到了嗎？那正是你的方向。','sort_order'=>7,'meta'=>['emoji'=>'⚡'],'report_content'=>'<h2>啟動期 ⚡</h2><p>那個讓你眼睛發亮的東西，值得你認真對待。</p><h3>這個階段的你</h3><ul><li>對某件事突然有強烈的興趣或衝動</li><li>感覺生命中有什麼東西要改變了</li></ul><h3>建議</h3><ul><li>把那個讓你心跳加速的想法寫下來，開始做第一步</li><li>和一個你信任的人分享你的想法，讓它更具體</li></ul>'],
            ['code'=>'RESONANCE','title'=>'共鳴期 🎶','description'=>'你現在的頻率特別容易和對的人事物對上。人際關係、機緣、靈感，都以一種意想不到的方式出現在你生命中。','sort_order'=>8,'meta'=>['emoji'=>'🎶'],'report_content'=>'<h2>共鳴期 🎶</h2><p>你正在對的頻率上，宇宙在回應你。保持開放，讓美好的事情發生。</p><h3>這個階段的你</h3><ul><li>遇到很多有共鳴的人、書、話語</li><li>靈感豐沛，感覺被某種力量帶著走</li></ul><h3>建議</h3><ul><li>說「是」多一點，接受意外的邀約或機會</li><li>記錄下這段時間出現的巧合和靈感</li></ul>'],
        ];

        foreach ($types as $t) {
            ResultType::create(array_merge(['quiz_id' => $quiz->id], $t));
        }

        $questions = [
            ['body'=>'最近起床，你的第一個念頭通常是？','options'=>[['key'=>'a','label'=>'「今天有好多想做的事！」','scores'=>['RISING'=>3,'IGNITE'=>2]],['key'=>'b','label'=>'「能不能再睡一下⋯⋯」','scores'=>['REST'=>3,'SORTING'=>1]],['key'=>'c','label'=>'「不知道今天會帶來什麼。」','scores'=>['TRANSFORM'=>2,'RESONANCE'=>2]],['key'=>'d','label'=>'「昨天的事還沒整理好⋯⋯」','scores'=>['SORTING'=>3,'PLANTING'=>1]]]],
            ['body'=>'這個月，你對自己的感覺是？','options'=>[['key'=>'a','label'=>'充實，感覺每天都有在前進。','scores'=>['RISING'=>3,'HARVEST'=>2]],['key'=>'b','label'=>'累，但說不清楚為什麼。','scores'=>['REST'=>3,'TRANSFORM'=>1]],['key'=>'c','label'=>'有種東西快要破殼而出的感覺。','scores'=>['IGNITE'=>3,'TRANSFORM'=>2]],['key'=>'d','label'=>'平靜，像一潭水，不起什麼漣漪。','scores'=>['REST'=>2,'PLANTING'=>2]]]],
            ['body'=>'最近你和身邊的人的關係？','options'=>[['key'=>'a','label'=>'很好，遇到很多有意思的人。','scores'=>['RESONANCE'=>3,'RISING'=>1]],['key'=>'b','label'=>'需要一點空間，想靜下來。','scores'=>['REST'=>3,'SORTING'=>1]],['key'=>'c','label'=>'有幾段關係正在重新定義。','scores'=>['TRANSFORM'=>3,'SORTING'=>1]],['key'=>'d','label'=>'有一種「終於懂彼此」的深化感。','scores'=>['HARVEST'=>3,'RESONANCE'=>1]]]],
            ['body'=>'對於未來半年，你的感覺是？','options'=>[['key'=>'a','label'=>'期待，有很多計畫想實現。','scores'=>['RISING'=>3,'PLANTING'=>2]],['key'=>'b','label'=>'茫然，還沒有清晰的方向。','scores'=>['SORTING'=>3,'REST'=>1]],['key'=>'c','label'=>'雀躍，有一種「要開始了」的預感。','scores'=>['IGNITE'=>4]],['key'=>'d','label'=>'踏實，繼續做好手上的事就好。','scores'=>['HARVEST'=>3,'PLANTING'=>1]]]],
            ['body'=>'最近讓你感到最有活力的是？','options'=>[['key'=>'a','label'=>'一個新的想法或計畫。','scores'=>['IGNITE'=>3,'RISING'=>2]],['key'=>'b','label'=>'感覺到事情正在到位。','scores'=>['HARVEST'=>3,'RISING'=>1]],['key'=>'c','label'=>'一段深刻的對話或連結。','scores'=>['RESONANCE'=>3,'PLANTING'=>1]],['key'=>'d','label'=>'其實沒有特別的事，就是靜靜的。','scores'=>['REST'=>2,'SORTING'=>2]]]],
            ['body'=>'你最近對什麼事情感到厭倦或想放下？','options'=>[['key'=>'a','label'=>'一段消耗我能量的關係。','scores'=>['SORTING'=>3,'TRANSFORM'=>1]],['key'=>'b','label'=>'一個不再有意義的目標。','scores'=>['SORTING'=>4]],['key'=>'c','label'=>'沒有特別想放下什麼，感覺都還好。','scores'=>['HARVEST'=>2,'RISING'=>2]],['key'=>'d','label'=>'自己對某些事的抗拒與逃避。','scores'=>['TRANSFORM'=>3,'IGNITE'=>1]]]],
            ['body'=>'最近你的睡眠與身體狀態？','options'=>[['key'=>'a','label'=>'很好，精力充沛。','scores'=>['RISING'=>3,'HARVEST'=>1]],['key'=>'b','label'=>'容易累，需要更多休息。','scores'=>['REST'=>4]],['key'=>'c','label'=>'還可以，但偶爾感到莫名的緊繃。','scores'=>['TRANSFORM'=>3,'PLANTING'=>1]],['key'=>'d','label'=>'睡眠品質不穩，思緒容易亂。','scores'=>['TRANSFORM'=>2,'IGNITE'=>2]]]],
            ['body'=>'如果現在的你是一種天氣，你會是？','options'=>[['key'=>'a','label'=>'晴天，陽光充足，充滿能量。','scores'=>['RISING'=>3,'HARVEST'=>1]],['key'=>'b','label'=>'微雨轉晴，有一種清洗後的新鮮感。','scores'=>['TRANSFORM'=>3,'IGNITE'=>1]],['key'=>'c','label'=>'深夜的靜謐，安靜而沉澱。','scores'=>['REST'=>3,'SORTING'=>1]],['key'=>'d','label'=>'春雷，感覺有什麼要爆發了。','scores'=>['IGNITE'=>3,'RESONANCE'=>2]]]],
            ['body'=>'最近生命中有沒有什麼「巧合」或「剛好」讓你印象深刻？','options'=>[['key'=>'a','label'=>'有，而且很多，感覺有某種安排。','scores'=>['RESONANCE'=>4]],['key'=>'b','label'=>'有一兩次，讓我感到驚喜。','scores'=>['RESONANCE'=>2,'HARVEST'=>2]],['key'=>'c','label'=>'沒有特別感受到，最近比較平靜。','scores'=>['REST'=>2,'PLANTING'=>2]],['key'=>'d','label'=>'比較多的是挑戰，但讓我成長了。','scores'=>['TRANSFORM'=>3,'PLANTING'=>1]]]],
            ['body'=>'對自己說一句話，你現在最需要聽到的是？','options'=>[['key'=>'a','label'=>'「繼續走，你已經在正確的路上。」','scores'=>['PLANTING'=>3,'HARVEST'=>1]],['key'=>'b','label'=>'「可以慢下來，休息不是浪費時間。」','scores'=>['REST'=>4]],['key'=>'c','label'=>'「那個讓你心動的事，值得你去追。」','scores'=>['IGNITE'=>4]],['key'=>'d','label'=>'「放下那些不再適合你的，才有空間迎接新的。」','scores'=>['SORTING'=>3,'TRANSFORM'=>2]]]],
        ];

        foreach ($questions as $i => $q) {
            QuizQuestion::create(['quiz_id'=>$quiz->id,'body'=>$q['body'],'type'=>'single_choice','options'=>$q['options'],'sort_order'=>$i+1,'is_required'=>true]);
        }
    }
}
