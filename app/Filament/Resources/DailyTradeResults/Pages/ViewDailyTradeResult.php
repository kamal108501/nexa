<?php

namespace App\Filament\Resources\DailyTradeResults\Pages;

use App\Filament\Resources\DailyTradeResults\DailyTradeResultResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDailyTradeResult extends ViewRecord
{
    protected static string $resource = DailyTradeResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
