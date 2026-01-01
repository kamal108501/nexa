<?php

namespace App\Filament\Resources\TradingMonthlyRiskPlans\Pages;

use App\Filament\Resources\TradingMonthlyRiskPlans\TradingMonthlyRiskPlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTradingMonthlyRiskPlans extends ListRecords
{
    protected static string $resource = TradingMonthlyRiskPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
