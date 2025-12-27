<?php

namespace App\Filament\Resources\TradingSymbols\Pages;

use App\Filament\Resources\TradingSymbols\TradingSymbolResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTradingSymbols extends ListRecords
{
    protected static string $resource = TradingSymbolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
