<?php

namespace App\Filament\Resources\StockTipResults\Pages;

use App\Filament\Resources\StockTipResults\StockTipResultResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStockTipResult extends EditRecord
{
    protected static string $resource = StockTipResultResource::class;

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
