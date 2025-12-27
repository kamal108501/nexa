<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ProfitLossOverTimeChart;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\TradesPerStockChart;

class Dashboard extends BaseDashboard
{
    protected function getHeaderWidgets(): array
    {
        return [
            TradesPerStockChart::class,
            ProfitLossOverTimeChart::class,
        ];
    }
}
