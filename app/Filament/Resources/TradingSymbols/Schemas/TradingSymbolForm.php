<?php

namespace App\Filament\Resources\TradingSymbols\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TradingSymbolForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('symbol_code')
                    ->required(),
                TextInput::make('symbol_name')
                    ->required(),
                Select::make('instrument_category')
                    ->options(['equity' => 'Equity', 'index' => 'Index', 'commodity' => 'Commodity'])
                    ->required(),
                Select::make('instrument_type')
                    ->options(['stock' => 'Stock', 'future' => 'Future', 'option' => 'Option'])
                    ->required(),
                Select::make('exchange')
                    ->options(['NSE' => 'N s e', 'MCX' => 'M c x'])
                    ->required(),
                TextInput::make('lot_size')
                    ->numeric()
                    ->default(null),
                TextInput::make('tick_size')
                    ->numeric()
                    ->default(null),
                Toggle::make('is_tradable')
                    ->required(),
                Toggle::make('is_active')
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
