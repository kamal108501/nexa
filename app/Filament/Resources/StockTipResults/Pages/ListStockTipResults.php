<?php

namespace App\Filament\Resources\StockTipResults\Pages;

use App\Filament\Resources\StockTipResults\StockTipResultResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockTipResults extends ListRecords
{
    protected static string $resource = StockTipResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
