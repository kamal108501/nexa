<?php

namespace App\Filament\Resources\StockTipResults\Pages;

use App\Filament\Resources\StockTipResults\StockTipResultResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStockTipResult extends ViewRecord
{
    protected static string $resource = StockTipResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
