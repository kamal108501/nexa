<?php

namespace App\Filament\Resources\StockTips\Widgets;

use App\Models\StockTip;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StockTipsStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Active Tips', StockTip::where('status', 'active')->count())
                ->description('Currently active')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),

            Stat::make('Completed', StockTip::where('status', 'completed')->count())
                ->description('Target achieved')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('SL Hit', StockTip::where('status', 'sl_hit')->count())
                ->description('Stop loss triggered')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Expired', StockTip::where('status', 'expired')->count())
                ->description('Expired tips')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
