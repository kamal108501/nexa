<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\StockTradeExecution;


class ProfitLossOverTimeChart extends ChartWidget
{
    protected ?string $heading = 'Profit / Loss Over Time';

    protected static ?int $sort = 2;
    protected static bool $isDiscovered = false;

    protected function getData(): array
    {
        $trades = StockTradeExecution::selectRaw('DATE(execution_at) as date, SUM(CASE WHEN execution_type = "SELL" THEN price * quantity ELSE 0 END) - SUM(CASE WHEN execution_type = "BUY" THEN price * quantity ELSE 0 END) as pnl')
            ->groupByRaw('DATE(execution_at)')
            ->orderBy('date')
            ->get();

        $labels = $trades->pluck('date')->toArray();
        $data = $trades->pluck('pnl')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Profit / Loss',
                    'data' => $data,
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#10b981',
                    'fill' => false,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
