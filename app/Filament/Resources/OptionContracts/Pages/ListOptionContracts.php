<?php

namespace App\Filament\Resources\OptionContracts\Pages;

use App\Filament\Resources\OptionContracts\OptionContractResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOptionContracts extends ListRecords
{
    protected static string $resource = OptionContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
