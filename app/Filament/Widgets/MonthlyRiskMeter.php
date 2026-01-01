<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MonthlyRiskMeter extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 1;

    protected function getStats(): array
    {
        $stats = auth()->user()?->currentRiskStats;

        if (! $stats || $stats->current_allowed_loss <= 0) {
            return [
                Stat::make('Monthly Risk', '0.00')
                    ->description('No risk plan configured')
                    ->color('gray'),
            ];
        }

        // Remaining loss is the PRIMARY number (matches Net P&L style)
        $remaining = $stats->remaining_loss_balance;

        // Net loss used for percentage
        $netLoss = max(0, $stats->total_loss - $stats->total_profit);

        $usedPercent = min(
            100,
            round(
                ($netLoss / $stats->current_allowed_loss) * 100,
                2
            )
        );

        $isBlocked = $stats->trading_blocked;
        $isWarning = $remaining <= ($stats->current_allowed_loss * 0.2);

        $color = $isBlocked
            ? 'danger'
            : ($isWarning ? 'warning' : 'success');

        $description = $isBlocked
            ? 'Trading blocked'
            : ($isWarning
                ? $usedPercent . '% used · Risk near limit'
                : $usedPercent . '% used · Trading allowed'
            );

        return [
            Stat::make('Monthly Risk', number_format($remaining, 2))
                ->description($description)
                ->color($color),
        ];
    }
}
