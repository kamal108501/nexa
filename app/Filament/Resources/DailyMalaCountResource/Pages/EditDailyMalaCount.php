<?php

namespace App\Filament\Resources\DailyMalaCountResource\Pages;

use App\Filament\Resources\DailyMalaCountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDailyMalaCount extends EditRecord
{
    protected static string $resource = DailyMalaCountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
