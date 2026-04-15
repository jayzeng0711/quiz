<?php

namespace Database\Seeders;

use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

/**
 * Replaces generic "很有共鳴/有時候/偶爾/不太" options on the 30 deep
 * latent-personality questions with specific, contextual answers.
 * Result type codes: MYSTIC, CONTROLLER, EMPATH, INNOVATOR, ANCHOR, SEEKER, PERFORMER, DREAMER
 */
class FixLatentPersonalityOptionsSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            '你覺得自己最根本的渴望是什麼？' => [
                ['key'=>'a','label'=>'被完全理解——有人能看見我說不出口的那部分','scores'=>['MYSTIC'=>3,'EMPATH'=>1],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>10]],
                ['key'=>'b','label'=>'把一件真正有意義的事做到最好','scores'=>['ANCHOR'=>2,'CONTROLLER'=>2],'dim_scores'=>['STABILITY'=>14,'CONTROL'=>12]],
                ['key'=>'c','label'=>'找到那個「這就是我應該在這裡的原因」','scores'=>['SEEKER'=>3,'DREAMER'=>1],'dim_scores'=>['INTUITION'=>16,'CREATIVITY'=>10]],
                ['key'=>'d','label'=>'讓世界因為我的存在有一點點不同','scores'=>['INNOVATOR'=>2,'DREAMER'=>2],'dim_scores'=>['CREATIVITY'=>14,'VISION'=>12]],
            ],
            '你在哪種時刻最感到迷失？' => [
                ['key'=>'a','label'=>'當我不清楚自己為什麼在做這件事的時候','scores'=>['SEEKER'=>3],'dim_scores'=>['INTUITION'=>18,'DEPTH'=>8]],
                ['key'=>'b','label'=>'當事情失控、一切都不按計畫走的時候','scores'=>['CONTROLLER'=>3],'dim_scores'=>['CONTROL'=>18,'STABILITY'=>8]],
                ['key'=>'c','label'=>'當我感覺和重要的人之間有距離的時候','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'當我不得不做一個沒有創意、只是重複的工作的時候','scores'=>['INNOVATOR'=>2,'DREAMER'=>2],'dim_scores'=>['CREATIVITY'=>16,'INTUITION'=>10]],
            ],
            '你最深的快樂來自哪裡？' => [
                ['key'=>'a','label'=>'某個深夜，一個想法突然清晰了','scores'=>['MYSTIC'=>2,'SEEKER'=>2],'dim_scores'=>['INTUITION'=>16,'DEPTH'=>12]],
                ['key'=>'b','label'=>'一件事情按計畫完成，而且做得很好','scores'=>['CONTROLLER'=>3,'ANCHOR'=>1],'dim_scores'=>['CONTROL'=>14,'STABILITY'=>12]],
                ['key'=>'c','label'=>'看到你在乎的人因為你而好一點了','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'d','label'=>'你創造了一個從來沒有人想到的解法','scores'=>['INNOVATOR'=>3,'DREAMER'=>1],'dim_scores'=>['CREATIVITY'=>18,'INTUITION'=>8]],
            ],
            '你如何知道一個人值得信任？' => [
                ['key'=>'a','label'=>'直覺，第一次見面我就知道了','scores'=>['MYSTIC'=>3,'EMPATH'=>1],'dim_scores'=>['INTUITION'=>18,'DEPTH'=>10]],
                ['key'=>'b','label'=>'時間和行動——他們說到做到','scores'=>['ANCHOR'=>3],'dim_scores'=>['STABILITY'=>18,'CONTROL'=>8]],
                ['key'=>'c','label'=>'他們在我脆弱的時候還是在的','scores'=>['EMPATH'=>2,'ANCHOR'=>2],'dim_scores'=>['DEPTH'=>16,'STABILITY'=>10]],
                ['key'=>'d','label'=>'他們對我誠實，即使真相不好聽','scores'=>['SEEKER'=>2,'CONTROLLER'=>2],'dim_scores'=>['INTUITION'=>14,'CONTROL'=>12]],
            ],
            '你在關係中最害怕的是什麼？' => [
                ['key'=>'a','label'=>'被看穿之後，對方選擇離開','scores'=>['MYSTIC'=>3,'PERFORMER'=>1],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'b','label'=>'失去主動權，對關係失去掌控感','scores'=>['CONTROLLER'=>3],'dim_scores'=>['CONTROL'=>18,'STABILITY'=>8]],
                ['key'=>'c','label'=>'深深在乎，但對方沒有同等回應','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'關係讓我停止成長，被困在固定的模式裡','scores'=>['DREAMER'=>2,'SEEKER'=>2],'dim_scores'=>['CREATIVITY'=>14,'INTUITION'=>12]],
            ],
            '你認為自己的哪個特質是「雙面刃」？' => [
                ['key'=>'a','label'=>'我看得太深——讓我有洞察力，也讓我太敏感','scores'=>['MYSTIC'=>3,'EMPATH'=>1],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'b','label'=>'我對細節的執著——讓結果好，也讓我難以放手','scores'=>['CONTROLLER'=>3],'dim_scores'=>['CONTROL'=>16,'STABILITY'=>10]],
                ['key'=>'c','label'=>'我太在乎別人——讓我很有同理心，也很容易被消耗','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'我的理想主義——讓我有動力，也讓我常常失望','scores'=>['DREAMER'=>2,'SEEKER'=>2],'dim_scores'=>['CREATIVITY'=>16,'INTUITION'=>10]],
            ],
            '你如何面對自己的缺點？' => [
                ['key'=>'a','label'=>'我會深入分析它從哪裡來，理解它','scores'=>['SEEKER'=>3,'MYSTIC'=>1],'dim_scores'=>['INTUITION'=>16,'DEPTH'=>12]],
                ['key'=>'b','label'=>'我立刻制定計畫改進，不喜歡讓問題停著','scores'=>['CONTROLLER'=>3,'ANCHOR'=>1],'dim_scores'=>['CONTROL'=>16,'STABILITY'=>10]],
                ['key'=>'c','label'=>'我先感受它帶來的難受，讓自己慢慢接受','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'d','label'=>'我把它重新框架——這個缺點也許有它的用處','scores'=>['INNOVATOR'=>2,'PERFORMER'=>2],'dim_scores'=>['CREATIVITY'=>14,'INTUITION'=>12]],
            ],
            '你覺得「真實的自己」在什麼時候最容易出現？' => [
                ['key'=>'a','label'=>'在深夜，獨自坐著，沒有人需要我扮演任何角色','scores'=>['MYSTIC'=>3,'SEEKER'=>1],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>10]],
                ['key'=>'b','label'=>'在我完全掌握一件事、知道自己做得很好的時候','scores'=>['CONTROLLER'=>3],'dim_scores'=>['CONTROL'=>16,'STABILITY'=>10]],
                ['key'=>'c','label'=>'在和一個真正懂我的人說話的時候','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'在創作或探索某個讓我著迷的東西的時候','scores'=>['DREAMER'=>2,'INNOVATOR'=>2],'dim_scores'=>['CREATIVITY'=>16,'INTUITION'=>10]],
            ],
            '你相信改變一個人是可能的嗎？' => [
                ['key'=>'a','label'=>'相信，但改變需要先從自己的深層開始','scores'=>['MYSTIC'=>2,'SEEKER'=>2],'dim_scores'=>['DEPTH'=>14,'INTUITION'=>14]],
                ['key'=>'b','label'=>'相信，而且我相信我可以幫助別人改變','scores'=>['EMPATH'=>2,'INNOVATOR'=>2],'dim_scores'=>['DEPTH'=>12,'CREATIVITY'=>14]],
                ['key'=>'c','label'=>'相信，但只有他們自己真的想改才可能','scores'=>['ANCHOR'=>2,'CONTROLLER'=>2],'dim_scores'=>['STABILITY'=>14,'CONTROL'=>12]],
                ['key'=>'d','label'=>'相信，而且我認為每個人都有沒被看見的可能性','scores'=>['DREAMER'=>3],'dim_scores'=>['CREATIVITY'=>16,'INTUITION'=>10]],
            ],
            '你最不願意承認的自己的一面是什麼？' => [
                ['key'=>'a','label'=>'我有時候希望不被需要，只是消失一段時間','scores'=>['MYSTIC'=>3,'ANCHOR'=>1],'dim_scores'=>['DEPTH'=>16,'STABILITY'=>10]],
                ['key'=>'b','label'=>'我的控制欲有時讓我傷害了我在乎的人','scores'=>['CONTROLLER'=>3],'dim_scores'=>['CONTROL'=>18,'STABILITY'=>8]],
                ['key'=>'c','label'=>'我太渴望被愛，有時候讓自己委屈自己','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'d','label'=>'我有時候活在自己的世界裡，讓別人感到疏遠','scores'=>['DREAMER'=>2,'MYSTIC'=>2],'dim_scores'=>['CREATIVITY'=>14,'DEPTH'=>12]],
            ],
            '你在什麼情況下會感到嫉妒？' => [
                ['key'=>'a','label'=>'當別人被深刻理解，而我感到不被看見的時候','scores'=>['MYSTIC'=>3,'EMPATH'=>1],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'b','label'=>'當別人得到了我努力爭取但沒得到的位置','scores'=>['CONTROLLER'=>3],'dim_scores'=>['CONTROL'=>16,'STABILITY'=>10]],
                ['key'=>'c','label'=>'當我在乎的人把注意力給了別人','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'當別人做到了我一直想做但還沒做的事','scores'=>['DREAMER'=>2,'SEEKER'=>2],'dim_scores'=>['CREATIVITY'=>14,'INTUITION'=>12]],
            ],
            '你如何應對被拒絕的感受？' => [
                ['key'=>'a','label'=>'我讓自己深深感受它，再慢慢找到它的意義','scores'=>['MYSTIC'=>2,'EMPATH'=>2],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'b','label'=>'我分析哪裡出了問題，快速調整再出發','scores'=>['CONTROLLER'=>3,'INNOVATOR'=>1],'dim_scores'=>['CONTROL'=>16,'CREATIVITY'=>10]],
                ['key'=>'c','label'=>'我先讓自己難受，然後找人說說話','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'我告訴自己「這不是真正適合我的」，繼續找','scores'=>['DREAMER'=>2,'ANCHOR'=>2],'dim_scores'=>['CREATIVITY'=>12,'STABILITY'=>14]],
            ],
            '你覺得自己最常誤解的地方是什麼？' => [
                ['key'=>'a','label'=>'別人以為我冷漠，但我只是在觀察和感受','scores'=>['MYSTIC'=>3],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'b','label'=>'別人以為我在乎規則，但我只是在乎結果','scores'=>['CONTROLLER'=>3],'dim_scores'=>['CONTROL'=>16,'STABILITY'=>10]],
                ['key'=>'c','label'=>'別人以為我太脆弱，但我只是感受得比較深','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'別人以為我不實際，但我只是看到了他們還沒看到的可能','scores'=>['DREAMER'=>2,'INNOVATOR'=>2],'dim_scores'=>['CREATIVITY'=>16,'INTUITION'=>10]],
            ],
            '你在什麼時候最容易妥協？' => [
                ['key'=>'a','label'=>'當我感覺對方比我更需要贏這場對話的時候','scores'=>['EMPATH'=>3,'MYSTIC'=>1],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'b','label'=>'當妥協讓整件事更有效率推進的時候','scores'=>['CONTROLLER'=>3,'ANCHOR'=>1],'dim_scores'=>['CONTROL'=>16,'STABILITY'=>10]],
                ['key'=>'c','label'=>'當我看到衝突對雙方都造成了傷害的時候','scores'=>['EMPATH'=>2,'ANCHOR'=>2],'dim_scores'=>['DEPTH'=>14,'STABILITY'=>12]],
                ['key'=>'d','label'=>'幾乎不妥協，除非對方給了我真正說服我的理由','scores'=>['SEEKER'=>2,'CONTROLLER'=>2],'dim_scores'=>['INTUITION'=>14,'CONTROL'=>12]],
            ],
            '你如何定義「內心的平靜」？' => [
                ['key'=>'a','label'=>'當我和自己的感受和解，不再和它對抗','scores'=>['MYSTIC'=>2,'EMPATH'=>2],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'b','label'=>'當所有事情在掌控之中，沒有懸而未決的問題','scores'=>['CONTROLLER'=>3],'dim_scores'=>['CONTROL'=>18,'STABILITY'=>8]],
                ['key'=>'c','label'=>'當身邊重要的關係是穩定和真實的','scores'=>['EMPATH'=>2,'ANCHOR'=>2],'dim_scores'=>['DEPTH'=>14,'STABILITY'=>12]],
                ['key'=>'d','label'=>'當我清楚地知道自己在做的事是有意義的','scores'=>['SEEKER'=>3],'dim_scores'=>['INTUITION'=>16,'DEPTH'=>10]],
            ],
            '你最抗拒哪種類型的人？為什麼？' => [
                ['key'=>'a','label'=>'表面人——從不說真話，永遠只說你想聽的','scores'=>['MYSTIC'=>2,'SEEKER'=>2],'dim_scores'=>['DEPTH'=>14,'INTUITION'=>12]],
                ['key'=>'b','label'=>'不可靠的人——說到做不到，讓計畫崩潰','scores'=>['CONTROLLER'=>2,'ANCHOR'=>2],'dim_scores'=>['CONTROL'=>14,'STABILITY'=>12]],
                ['key'=>'c','label'=>'冷漠的人——對別人的感受毫不在意','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'d','label'=>'思想封閉的人——拒絕任何可能性','scores'=>['INNOVATOR'=>2,'DREAMER'=>2],'dim_scores'=>['CREATIVITY'=>16,'INTUITION'=>10]],
            ],
            '你對死亡的態度是？' => [
                ['key'=>'a','label'=>'它讓我更認真地問：我現在的生命是否真實？','scores'=>['MYSTIC'=>2,'SEEKER'=>2],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'b','label'=>'它讓我想在有限的時間裡把能做的都做完','scores'=>['CONTROLLER'=>2,'ANCHOR'=>2],'dim_scores'=>['CONTROL'=>12,'STABILITY'=>14]],
                ['key'=>'c','label'=>'它讓我更珍惜每一段有深度的關係','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'d','label'=>'它讓我更勇敢去追那些我一直在等的夢想','scores'=>['DREAMER'=>3],'dim_scores'=>['CREATIVITY'=>16,'INTUITION'=>10]],
            ],
            '你覺得愛是什麼？' => [
                ['key'=>'a','label'=>'看見一個人真實的樣子，仍然選擇靠近','scores'=>['MYSTIC'=>2,'EMPATH'=>2],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'b','label'=>'每天的行動和承諾，而不是感覺','scores'=>['ANCHOR'=>3],'dim_scores'=>['STABILITY'=>18,'CONTROL'=>8]],
                ['key'=>'c','label'=>'那種在某個人面前可以完全做自己的感覺','scores'=>['EMPATH'=>2,'MYSTIC'=>2],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'兩個人一起成長，讓彼此成為更完整的自己','scores'=>['DREAMER'=>2,'SEEKER'=>2],'dim_scores'=>['CREATIVITY'=>12,'INTUITION'=>14]],
            ],
            '你認為自己最深的傷是什麼？' => [
                ['key'=>'a','label'=>'曾經被人看穿了，卻被評判或遺棄','scores'=>['MYSTIC'=>3,'EMPATH'=>1],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'b','label'=>'努力做到最好，但還是沒被認可','scores'=>['CONTROLLER'=>3],'dim_scores'=>['CONTROL'=>16,'STABILITY'=>10]],
                ['key'=>'c','label'=>'深深在乎的人，讓我感到不被需要','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'d','label'=>'我一直相信的那個可能性，被現實打碎了','scores'=>['DREAMER'=>3],'dim_scores'=>['CREATIVITY'=>16,'DEPTH'=>10]],
            ],
            '你如何對待自己的陰暗面？' => [
                ['key'=>'a','label'=>'我試著理解它，和它坐在一起，而不是趕走它','scores'=>['MYSTIC'=>3,'EMPATH'=>1],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'b','label'=>'我管理它，設立規則不讓它影響我的行動','scores'=>['CONTROLLER'=>3],'dim_scores'=>['CONTROL'=>18,'STABILITY'=>8]],
                ['key'=>'c','label'=>'我讓自己感受它，然後找人分享，讓它不那麼沉重','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'我把它轉化為創作或新的想法的來源','scores'=>['INNOVATOR'=>2,'DREAMER'=>2],'dim_scores'=>['CREATIVITY'=>16,'DEPTH'=>10]],
            ],
            '你覺得「接受」和「放棄」的界線在哪裡？' => [
                ['key'=>'a','label'=>'當我的靈魂說「這不再是我的路」的時候','scores'=>['MYSTIC'=>3],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'b','label'=>'當繼續的代價超過了合理的範圍的時候','scores'=>['CONTROLLER'=>2,'ANCHOR'=>2],'dim_scores'=>['CONTROL'=>14,'STABILITY'=>12]],
                ['key'=>'c','label'=>'當繼續下去會傷害到我在乎的人的時候','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'當我發現更好的可能性在另一個方向的時候','scores'=>['DREAMER'=>2,'INNOVATOR'=>2],'dim_scores'=>['CREATIVITY'=>14,'INTUITION'=>12]],
            ],
            '你最容易在哪種情況下感到憤怒？' => [
                ['key'=>'a','label'=>'當我看到虛偽和表面，卻沒有人指出來的時候','scores'=>['MYSTIC'=>2,'SEEKER'=>2],'dim_scores'=>['DEPTH'=>14,'INTUITION'=>12]],
                ['key'=>'b','label'=>'當原本清楚的計畫因為別人的疏失而崩潰的時候','scores'=>['CONTROLLER'=>3],'dim_scores'=>['CONTROL'=>18,'STABILITY'=>8]],
                ['key'=>'c','label'=>'當我感受到有人被不公平對待的時候','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'當明明可以更好，卻因為惰性或恐懼而停留在原地的時候','scores'=>['INNOVATOR'=>2,'DREAMER'=>2],'dim_scores'=>['CREATIVITY'=>14,'INTUITION'=>12]],
            ],
            '你認為自己還沒有完全開發的潛力是什麼？' => [
                ['key'=>'a','label'=>'把我的洞見和感知轉化為他人可以理解的形式','scores'=>['MYSTIC'=>2,'PERFORMER'=>2],'dim_scores'=>['DEPTH'=>14,'INTUITION'=>12]],
                ['key'=>'b','label'=>'放手讓別人去做，而不是所有事都親力親為','scores'=>['CONTROLLER'=>3],'dim_scores'=>['CONTROL'=>14,'STABILITY'=>12]],
                ['key'=>'c','label'=>'說出我自己的需求，而不只是照顧別人的','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'讓我的夢想真的落地，而不是一直停在想像裡','scores'=>['DREAMER'=>3],'dim_scores'=>['CREATIVITY'=>18,'DEPTH'=>8]],
            ],
            '你在夜深人靜時最常想什麼？' => [
                ['key'=>'a','label'=>'那些我說不出口，但一直藏在心裡的感受','scores'=>['MYSTIC'=>3,'EMPATH'=>1],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'b','label'=>'明天有什麼沒做完的，要怎麼安排','scores'=>['CONTROLLER'=>3,'ANCHOR'=>1],'dim_scores'=>['CONTROL'=>16,'STABILITY'=>10]],
                ['key'=>'c','label'=>'今天和某個人的對話，有沒有哪裡讓他不舒服','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'那個我一直沒有勇氣追的夢，如果開始了會怎樣','scores'=>['DREAMER'=>3],'dim_scores'=>['CREATIVITY'=>18,'DEPTH'=>8]],
            ],
            '如果你能改變自己的一件事，會是什麼？' => [
                ['key'=>'a','label'=>'我希望我能更容易讓人走近我','scores'=>['MYSTIC'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'b','label'=>'我希望我能在控制和放鬆之間找到更好的平衡','scores'=>['CONTROLLER'=>3],'dim_scores'=>['CONTROL'=>16,'STABILITY'=>10]],
                ['key'=>'c','label'=>'我希望我能不那麼容易因為別人的感受而動搖','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'我希望我能更快把想法付諸行動，而不是一直在想像','scores'=>['DREAMER'=>3],'dim_scores'=>['CREATIVITY'=>18,'DEPTH'=>8]],
            ],
            '你認為你現在的生活方式反映了真正的你嗎？' => [
                ['key'=>'a','label'=>'有些部分是的，但有些最深的部分還藏著','scores'=>['MYSTIC'=>3,'SEEKER'=>1],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'b','label'=>'大部分是——我很清楚自己在做什麼、為什麼做','scores'=>['CONTROLLER'=>2,'ANCHOR'=>2],'dim_scores'=>['CONTROL'=>14,'STABILITY'=>12]],
                ['key'=>'c','label'=>'在關係裡是的，但在其他方面我還在找','scores'=>['EMPATH'=>2,'SEEKER'=>2],'dim_scores'=>['DEPTH'=>14,'INTUITION'=>12]],
                ['key'=>'d','label'=>'不完全——真正的我比現在的生活更大、更自由','scores'=>['DREAMER'=>3],'dim_scores'=>['CREATIVITY'=>18,'DEPTH'=>8]],
            ],
            '你對「傷害我的人」最常有的感受是什麼？' => [
                ['key'=>'a','label'=>'想理解他們為什麼這樣做——傷害背後的傷害','scores'=>['MYSTIC'=>2,'EMPATH'=>2],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'b','label'=>'需要一段時間處理，然後設立清楚的界線','scores'=>['CONTROLLER'=>2,'ANCHOR'=>2],'dim_scores'=>['CONTROL'=>12,'STABILITY'=>14]],
                ['key'=>'c','label'=>'難過，但最終會試著找到原諒的可能','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'d','label'=>'把那個傷轉化為某種讓我成長的燃料','scores'=>['INNOVATOR'=>2,'DREAMER'=>2],'dim_scores'=>['CREATIVITY'=>14,'DEPTH'=>12]],
            ],
            '你覺得自己最深的愛是給了什麼或誰？' => [
                ['key'=>'a','label'=>'給了某些人，他們看見了我沒辦法對任何人說的那個我','scores'=>['MYSTIC'=>3,'EMPATH'=>1],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'b','label'=>'給了我一直在建設、保護的那些事和人','scores'=>['ANCHOR'=>3,'CONTROLLER'=>1],'dim_scores'=>['STABILITY'=>18,'CONTROL'=>8]],
                ['key'=>'c','label'=>'給了那些在我生命中需要被照顧的人','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'給了那個一直在追求的、還沒完全到達的理想','scores'=>['DREAMER'=>2,'SEEKER'=>2],'dim_scores'=>['CREATIVITY'=>14,'INTUITION'=>12]],
            ],
            '如果你的童年可以重來，你想改變什麼？' => [
                ['key'=>'a','label'=>'我希望有一個能真正懂我的人，讓我不用那麼早學會保護自己','scores'=>['MYSTIC'=>3,'EMPATH'=>1],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'b','label'=>'我希望有更清楚的規則和結構，讓我有安全感去探索','scores'=>['CONTROLLER'=>2,'ANCHOR'=>2],'dim_scores'=>['CONTROL'=>12,'STABILITY'=>14]],
                ['key'=>'c','label'=>'我希望有更多被愛和被接受的確認，不需要靠自己猜測','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'我希望有人告訴我，我的夢想不是不切實際的','scores'=>['DREAMER'=>3],'dim_scores'=>['CREATIVITY'=>18,'DEPTH'=>8]],
            ],
            '你認為你的靈魂是什麼顏色？' => [
                ['key'=>'a','label'=>'深紫或深藍——複雜、有深度、說不完','scores'=>['MYSTIC'=>3,'SEEKER'=>1],'dim_scores'=>['DEPTH'=>18,'INTUITION'=>8]],
                ['key'=>'b','label'=>'深灰或黑——力量感、清醒、有厚度','scores'=>['CONTROLLER'=>2,'ANCHOR'=>2],'dim_scores'=>['CONTROL'=>14,'STABILITY'=>12]],
                ['key'=>'c','label'=>'玫瑰或暖橘——溫暖、感性、會發光的那種','scores'=>['EMPATH'=>3],'dim_scores'=>['DEPTH'=>16,'INTUITION'=>10]],
                ['key'=>'d','label'=>'彩色或金色——流動的、充滿可能性的','scores'=>['DREAMER'=>2,'INNOVATOR'=>2],'dim_scores'=>['CREATIVITY'=>16,'INTUITION'=>10]],
            ],
        ];

        $updated = 0;
        foreach ($questions as $body => $options) {
            $rows = QuizQuestion::where('body', $body)->get();
            foreach ($rows as $q) {
                $q->update(['options' => $options]);
                $updated++;
            }
        }

        $this->command->info("✅ Updated {$updated} latent-personality questions with specific contextual options");
    }
}
