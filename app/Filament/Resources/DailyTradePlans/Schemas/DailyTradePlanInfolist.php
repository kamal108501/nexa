<?php

namespace App\Filament\Resources\DailyTradePlans\Schemas;

use App\Models\DailyTradePlan;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DailyTradePlanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('trade_date')
                    ->date(),
                TextEntry::make('symbol.name')
                    ->label('Symbol'),
                TextEntry::make('optionContract.id')
                    ->label('Option contract'),
                TextEntry::make('current_price')
                    ->money(),
                TextEntry::make('planned_entry_price')
                    ->money(),
                TextEntry::make('stop_loss')
                    ->numeric(),
                TextEntry::make('target_price')
                    ->money(),
                TextEntry::make('quantity')
                    ->numeric(),
                TextEntry::make('expected_profit')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('expected_loss')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('expected_return_percent')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
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
                    ->visible(fn (DailyTradePlan $record): bool => $record->trashed()),
            ]);
    }
}
