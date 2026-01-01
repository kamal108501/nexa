<?php

namespace App\Filament\Resources\TradingMonthlyRiskPlans\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class TradingMonthlyRiskPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([

            Hidden::make('user_id')
                ->default(fn() => Auth::id())
                ->required(),

            Select::make('risk_month')
                ->label('Month')
                ->options([
                    1 => 'January',
                    2 => 'February',
                    3 => 'March',
                    4 => 'April',
                    5 => 'May',
                    6 => 'June',
                    7 => 'July',
                    8 => 'August',
                    9 => 'September',
                    10 => 'October',
                    11 => 'November',
                    12 => 'December',
                ])
                ->default(now()->month)
                ->required(),

            TextInput::make('risk_year')
                ->numeric()
                ->default(now()->year)
                ->required(),

            TextInput::make('base_max_loss')
                ->numeric()
                ->required()
                ->minValue(0),

            TextInput::make('profit_risk_percent')
                ->numeric()
                ->default(10)
                ->minValue(0)
                ->maxValue(100)
                ->required(),

            Toggle::make('carry_profit_to_next_month')
                ->default(true),

            Toggle::make('is_active')
                ->default(true),
        ]);
    }
}
