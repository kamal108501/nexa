<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\MonthlyRiskMeter;
use App\Filament\Widgets\MonthlyRiskSummary;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected function getHeaderWidgets(): array
    {
        return [
            MonthlyRiskSummary::class,
            MonthlyRiskMeter::class,
        ];
    }

    public function getHeaderWidgetsColumns(): array|int
    {
        return 2; // 2-column grid for header widgets
    }

    public function getWidgets(): array
    {
        return [
            // Return an empty list to hide body widgets on the dashboard
        ];
    }
}
