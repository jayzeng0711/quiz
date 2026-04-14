<?php

namespace App\Http\Controllers;

use App\Models\Quiz;

class QuizController extends Controller
{
    /**
     * Collection definitions — order and display config for the homepage.
     */
    private const COLLECTIONS = [
        'self' => [
            'emoji' => '🌟',
            'title' => '認識自己',
            'desc'  => '深入了解你的人格特質、行為模式與潛在性格',
            'bg'    => 'from-brand-500 to-violet-500',
            'light' => 'from-brand-50 to-violet-50',
            'order' => 1,
        ],
        'relationship' => [
            'emoji' => '🌈',
            'title' => '關係探索',
            'desc'  => '了解你在愛情、友情與社交互動中的獨特方式',
            'bg'    => 'from-rose-500 to-pink-500',
            'light' => 'from-rose-50 to-pink-50',
            'order' => 2,
        ],
        'career' => [
            'emoji' => '💼',
            'title' => '職涯與生活',
            'desc'  => '探索你的職場風格、人生方向與情緒管理模式',
            'bg'    => 'from-amber-500 to-orange-500',
            'light' => 'from-amber-50 to-orange-50',
            'order' => 3,
        ],
        'energy' => [
            'emoji' => '✨',
            'title' => '能量與魅力',
            'desc'  => '感知你現在的能量狀態與個人魅力特質',
            'bg'    => 'from-emerald-500 to-teal-500',
            'light' => 'from-emerald-50 to-teal-50',
            'order' => 4,
        ],
    ];

    public function show()
    {
        $quizzes = Quiz::where('is_active', true)
            ->withCount('questions')
            ->get();

        // Group quizzes by collection slug, ordered by collection_order within each group
        $grouped = $quizzes
            ->sortBy(fn ($q) => $q->meta['collection_order'] ?? 99)
            ->groupBy(fn ($q) => $q->meta['collection'] ?? 'other');

        // Build ordered collections with their quizzes
        $collections = collect(self::COLLECTIONS)
            ->sortBy('order')
            ->map(function ($config, $slug) use ($grouped) {
                return array_merge($config, [
                    'slug'   => $slug,
                    'quizzes'=> $grouped->get($slug, collect()),
                ]);
            })
            ->filter(fn ($c) => $c['quizzes']->isNotEmpty());

        return view('quiz.show', compact('collections'));
    }
}
