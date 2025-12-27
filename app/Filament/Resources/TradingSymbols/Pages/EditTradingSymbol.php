<?php

namespace App\Filament\Resources\TradingSymbols\Pages;

use App\Filament\Resources\TradingSymbols\TradingSymbolResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTradingSymbol extends EditRecord
{
    protected static string $resource = TradingSymbolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
