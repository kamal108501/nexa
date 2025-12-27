<?php

namespace App\Filament\Resources\StockTipResults\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StockTipResultForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('stock_tip_id')
                    ->relationship('stockTip', 'id')
                    ->required(),
                TextInput::make('exit_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                DatePicker::make('exit_date')
                    ->required(),
                TextInput::make('pnl_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('pnl_percent')
                    ->required()
                    ->numeric(),
                Select::make('exit_reason')
                    ->options(['target_hit' => 'Target hit', 'sl_hit' => 'Sl hit', 'time_expired' => 'Time expired'])
                    ->required(),
                Toggle::make('is_correct')
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
