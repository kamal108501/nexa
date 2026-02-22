<?php

namespace App\Filament\Resources\DailyMalaCountResource\Pages;

use App\Filament\Resources\DailyMalaCountResource;
use App\Filament\Resources\DailyMalaCountResource\Widgets\MalaCalendarWidget;
use Filament\Actions;
use Filament\Resources\Pages\Page;

class ListDailyMalaCounts extends Page
{
    protected static string $resource = DailyMalaCountResource::class;

    protected string $view = 'filament.resources.daily-mala-count.pages.calendar';

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            MalaCalendarWidget::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }
}
