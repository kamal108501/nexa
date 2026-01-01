<?php

namespace App\Filament\Resources\TradingMonthlyRiskPlans\Pages;

use App\Filament\Resources\TradingMonthlyRiskPlans\TradingMonthlyRiskPlanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTradingMonthlyRiskPlan extends ViewRecord
{
    protected static string $resource = TradingMonthlyRiskPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
