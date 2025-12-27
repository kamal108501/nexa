<?php

namespace App\Filament\Resources\StockTradeExecutions\Pages;

use App\Filament\Resources\StockTradeExecutions\StockTradeExecutionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockTradeExecutions extends ListRecords
{
    protected static string $resource = StockTradeExecutionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
