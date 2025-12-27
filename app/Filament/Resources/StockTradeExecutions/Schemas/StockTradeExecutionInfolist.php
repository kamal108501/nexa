<?php

namespace App\Filament\Resources\StockTradeExecutions\Schemas;

use App\Models\StockTradeExecution;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StockTradeExecutionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('symbol_id')
                    ->numeric(),
                TextEntry::make('stock_tip_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('execution_type')
                    ->badge(),
                TextEntry::make('quantity')
                    ->numeric(),
                TextEntry::make('price')
                    ->money('INR'),
                TextEntry::make('execution_date')
                    ->date(),
                TextEntry::make('execution_notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
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
                    ->visible(fn(StockTradeExecution $record): bool => $record->trashed()),
            ]);
    }
}
