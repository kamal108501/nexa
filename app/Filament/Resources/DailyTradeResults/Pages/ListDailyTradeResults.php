<?php

namespace App\Filament\Resources\DailyTradeResults\Pages;

use App\Filament\Resources\DailyTradeResults\DailyTradeResultResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;

class ListDailyTradeResults extends ListRecords
{
    /**
     * Override to prevent filters from syncing to the URL query string.
     */
    public ?array $tableFilters = null;

    protected static string $resource = DailyTradeResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    /**
     * Hide the default Apply / Reset buttons in the filters form.
     */
    protected function getTableFiltersFormActions(): array
    {
        return [];
    }

    /**
     * Fallback for inline filters form actions in case the table-specific hook is bypassed.
     */
    protected function getFiltersFormActions(): array
    {
        return [];
    }

    /**
     * Hide the Filters trigger button itself (AboveContent layout).
     */
    protected function getTableFiltersTriggerAction(): ?Action
    {
        return null;
    }
}
