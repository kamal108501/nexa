<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\StockTradeExecution;

class TradesPerStockChart extends ChartWidget
{
    protected ?string $heading = 'Trades Per Stock';
    protected static ?int $sort = 1;
    protected static bool $isDiscovered = false;

    protected function getData(): array
    {
        $trades = StockTradeExecution::with('symbol')
            ->selectRaw('trading_symbol_id, COUNT(*) as count')
            ->groupBy('trading_symbol_id')
            ->get();

        $labels = $trades->map(fn($trade) => $trade->symbol->name ?? 'Unknown')->toArray();
        $data = $trades->map(fn($trade) => $trade->count)->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Trades',
                    'data' => $data,
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
