<?php

namespace App\Filament\Resources\StockTips\Pages;

use App\Filament\Resources\StockTips\StockTipResource;
use App\Filament\Widgets\StockTipsStatsWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockTips extends ListRecords
{
    protected static string $resource = StockTipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StockTipsStatsWidget::class,
        ];
    }
}
