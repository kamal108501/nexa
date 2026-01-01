<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MonthlyRiskSummary extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 1;

    protected function getStats(): array
    {
        $stats = auth()->user()?->currentRiskStats;

        if (! $stats) {
            return [];
        }

        $net = $stats->total_profit - $stats->total_loss;
        $isPositive = $net >= 0;

        $valueColor = $isPositive
            ? 'text-success-600 dark:text-success-400'
            : 'text-danger-600 dark:text-danger-400';

        return [
            Stat::make('Net P&L', number_format($net, 2))
                ->color($isPositive ? 'success' : 'danger')
                ->description($isPositive ? 'Net profit' : 'Net loss')
                ->descriptionColor($isPositive ? 'success' : 'danger')
                ->extraAttributes(['class' => $valueColor]),
        ];
    }
}
