<?php

namespace App\Filament\Resources\TradingSymbols\Pages;

use App\Filament\Resources\TradingSymbols\TradingSymbolResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTradingSymbol extends ViewRecord
{
    protected static string $resource = TradingSymbolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
