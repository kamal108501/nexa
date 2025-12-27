<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\StockTradeExecution;


class ProfitLossOverTimeChart extends ChartWidget
{
    protected ?string $heading = 'Profit / Loss Over Time';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $trades = StockTradeExecution::selectRaw('DATE(execution_date) as date, SUM(CASE WHEN execution_type = "sell" THEN price * quantity ELSE 0 END) - SUM(CASE WHEN execution_type = "buy" THEN price * quantity ELSE 0 END) as pnl')
            ->groupByRaw('DATE(execution_date)')
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
