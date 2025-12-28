<?php

namespace App\Filament\Resources\DailyTradePlans\Pages;

use App\Filament\Resources\DailyTradePlans\DailyTradePlanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDailyTradePlan extends EditRecord
{
    protected static string $resource = DailyTradePlanResource::class;

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
