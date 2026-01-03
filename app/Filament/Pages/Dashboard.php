<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\MonthlyRiskMeter;
use App\Filament\Widgets\MonthlyRiskSummary;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $navigationLabel = 'Trading Dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            MonthlyRiskMeter::class,
            MonthlyRiskSummary::class,
        ];
    }

    public function getHeaderWidgetsColumns(): array|int
    {
        return [
            'default' => 2,
            'lg' => 3,
            '2xl' => 4,
        ];
    }

    public function getWidgets(): array
    {
        return [
            // Return an empty list to hide body widgets on the dashboard
        ];
    }
}
