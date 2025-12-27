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
                TextInput::make('symbol_code')->label('Symbol Code')->required(),
                TextInput::make('name')->label('Name')->required(),
                TextInput::make('segment')->label('Segment')->required(),
                TextInput::make('lot_size')->label('Lot Size')->required()->numeric()->default(null),
                // TextInput::make('tick_size')->label('Tick Size')->numeric()->default(null),
                // Toggle::make('is_active')->label('Active')->required(),
                // TextInput::make('created_by')->label('Created By')->numeric()->default(null),
                // TextInput::make('updated_by')->label('Updated By')->numeric()->default(null),
                // TextInput::make('deleted_by')->label('Deleted By')->numeric()->default(null),
            ]);
    }
}
