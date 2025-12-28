<?php

namespace App\Filament\Resources\DailyTradePlans\Pages;

use App\Filament\Resources\DailyTradePlans\DailyTradePlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDailyTradePlans extends ListRecords
{
    protected static string $resource = DailyTradePlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
