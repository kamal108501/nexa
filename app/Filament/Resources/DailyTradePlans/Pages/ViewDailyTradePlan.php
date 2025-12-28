<?php

namespace App\Filament\Resources\DailyTradePlans\Pages;

use App\Filament\Resources\DailyTradePlans\DailyTradePlanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDailyTradePlan extends ViewRecord
{
    protected static string $resource = DailyTradePlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
