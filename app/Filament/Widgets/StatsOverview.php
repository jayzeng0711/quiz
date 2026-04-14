<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\QuizAttempt;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Order::where('status', 'paid')->sum('amount');
        $completedToday = QuizAttempt::whereDate('completed_at', today())->count();
        $conversionRate = ($total = QuizAttempt::where('status', 'completed')->orWhere('status', 'paid')->count())
            ? round(Order::where('status', 'paid')->count() / $total * 100, 1)
            : 0;

        return [
            Stat::make('總收入', 'NT$' . number_format($totalRevenue / 100))
                ->description('累計付款完成訂單')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('今日完成人數', $completedToday)
                ->description('完成測驗（含付費）')
                ->icon('heroicon-o-users'),

            Stat::make('付費轉換率', $conversionRate . '%')
                ->description('完成測驗後付費比例')
                ->icon('heroicon-o-arrow-trending-up')
                ->color($conversionRate >= 10 ? 'success' : 'warning'),
        ];
    }
}
