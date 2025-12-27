<?php

namespace App\Filament\Resources\StockTradeExecutions\Pages;

use App\Filament\Resources\StockTradeExecutions\StockTradeExecutionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStockTradeExecution extends ViewRecord
{
    protected static string $resource = StockTradeExecutionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
