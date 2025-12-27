<?php

namespace App\Filament\Resources\OptionContracts\Pages;

use App\Filament\Resources\OptionContracts\OptionContractResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOptionContract extends ViewRecord
{
    protected static string $resource = OptionContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
