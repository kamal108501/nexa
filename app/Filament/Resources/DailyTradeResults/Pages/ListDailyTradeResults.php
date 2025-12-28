<?php

namespace App\Filament\Resources\DailyTradeResults\Pages;

use App\Filament\Resources\DailyTradeResults\DailyTradeResultResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDailyTradeResults extends ListRecords
{
    protected static string $resource = DailyTradeResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
