<?php

namespace App\Filament\Resources\DailyTradeResults\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DailyTradeResultForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('daily_trade_plan_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('trade_date')
                    ->required(),
                DateTimePicker::make('entry_time'),
                DateTimePicker::make('exit_time'),
                TextInput::make('entry_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('exit_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('points')
                    ->required()
                    ->numeric(),
                TextInput::make('pnl_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('pnl_percent')
                    ->required()
                    ->numeric(),
                Select::make('result')
                    ->options(['profit' => 'Profit', 'loss' => 'Loss'])
                    ->required(),
                TextInput::make('created_by')
                    ->numeric()
                    ->default(null),
                TextInput::make('updated_by')
                    ->numeric()
                    ->default(null),
                TextInput::make('deleted_by')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
