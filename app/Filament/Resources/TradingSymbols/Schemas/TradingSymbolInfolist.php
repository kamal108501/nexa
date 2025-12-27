<?php

namespace App\Filament\Resources\TradingSymbols\Schemas;

use App\Models\TradingSymbol;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TradingSymbolInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('symbol_code'),
                TextEntry::make('symbol_name'),
                TextEntry::make('instrument_category')
                    ->badge(),
                TextEntry::make('instrument_type')
                    ->badge(),
                TextEntry::make('exchange')
                    ->badge(),
                TextEntry::make('lot_size')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tick_size')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('is_tradable')
                    ->boolean(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('updated_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('deleted_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (TradingSymbol $record): bool => $record->trashed()),
            ]);
    }
}
