<?php

namespace App\Filament\Resources\DailyTradeResults\Pages;

use App\Filament\Resources\DailyTradeResults\DailyTradeResultResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDailyTradeResult extends EditRecord
{
    protected static string $resource = DailyTradeResultResource::class;

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
