<?php

namespace App\Filament\Resources\TradingMonthlyRiskPlans\Schemas;

use App\Models\TradingMonthlyRiskPlan;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TradingMonthlyRiskPlanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('risk_year'),
                TextEntry::make('risk_month')
                    ->numeric(),
                TextEntry::make('base_max_loss')
                    ->numeric(),
                TextEntry::make('profit_risk_percent')
                    ->numeric(),
                IconEntry::make('carry_profit_to_next_month')
                    ->boolean(),
                IconEntry::make('is_locked')
                    ->boolean(),
                IconEntry::make('is_active')
                    ->boolean(),
                // TextEntry::make('created_by')
                //     ->numeric()
                //     ->placeholder('-'),
                // TextEntry::make('updated_by')
                //     ->numeric()
                //     ->placeholder('-'),
                // TextEntry::make('deleted_by')
                //     ->numeric()
                //     ->placeholder('-'),
                // TextEntry::make('created_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('updated_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('deleted_at')
                //     ->dateTime()
                //     ->visible(fn (TradingMonthlyRiskPlan $record): bool => $record->trashed()),
            ]);
    }
}
