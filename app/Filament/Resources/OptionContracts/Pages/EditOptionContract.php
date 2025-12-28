<?php

namespace App\Filament\Resources\OptionContracts\Pages;

use App\Filament\Resources\OptionContracts\OptionContractResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOptionContract extends EditRecord
{
    protected static string $resource = OptionContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
