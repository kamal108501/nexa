<?php

namespace App\Filament\Resources\TradingMonthlyRiskPlans\Pages;

use App\Filament\Resources\TradingMonthlyRiskPlans\TradingMonthlyRiskPlanResource;
use App\Models\TradingMonthlyRiskStat;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\QueryException;

class CreateTradingMonthlyRiskPlan extends CreateRecord
{
    protected static string $resource = TradingMonthlyRiskPlanResource::class;

    /**
     * After a risk plan is created,
     * automatically create the monthly risk stats row.
     */
    protected function afterCreate(): void
    {
        TradingMonthlyRiskStat::firstOrCreate(
            [
                'user_id'      => $this->record->user_id,
                'risk_plan_id' => $this->record->id,
                'risk_year'    => $this->record->risk_year,
                'risk_month'   => $this->record->risk_month,
            ],
            [
                'total_profit'           => 0,
                'total_loss'             => 0,
                'current_allowed_loss'   => $this->record->base_max_loss,
                'remaining_loss_balance' => $this->record->base_max_loss,
                'trading_blocked'        => false,
                'is_active'              => true,
                'created_by'             => auth()->id(),
            ]
        );
    }

    /**
     * Handle duplicate month/year plan gracefully.
     */
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        try {
            return parent::handleRecordCreation($data);
        } catch (QueryException $e) {
            // MySQL duplicate entry error
            if ($e->getCode() === '23000') {
                Notification::make()
                    ->title('Risk plan already exists')
                    ->body('A trading risk plan for this month already exists.')
                    ->danger()
                    ->send();

                $this->halt();
            }

            throw $e;
        }
    }
}
