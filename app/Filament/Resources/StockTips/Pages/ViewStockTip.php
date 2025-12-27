<?php

namespace App\Filament\Resources\StockTips\Pages;

use App\Filament\Resources\StockTips\StockTipResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStockTip extends ViewRecord
{
    protected static string $resource = StockTipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
