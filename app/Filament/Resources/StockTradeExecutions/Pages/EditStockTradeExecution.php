<?php

namespace App\Filament\Resources\StockTradeExecutions\Pages;

use App\Filament\Resources\StockTradeExecutions\StockTradeExecutionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditStockTradeExecution extends EditRecord
{
    protected static string $resource = StockTradeExecutionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
