<?php

namespace App\Filament\Resources\DailyTradeResults\Schemas;

use App\Models\DailyTradeResult;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DailyTradeResultInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('daily_trade_plan_id')
                    ->numeric(),
                TextEntry::make('trade_date')
                    ->date(),
                TextEntry::make('entry_time')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('exit_time')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('entry_price')
                    ->money(),
                TextEntry::make('exit_price')
                    ->money(),
                TextEntry::make('points')
                    ->numeric(),
                TextEntry::make('pnl_amount')
                    ->numeric(),
                TextEntry::make('pnl_percent')
                    ->numeric(),
                TextEntry::make('result')
                    ->badge(),
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
                    ->visible(fn (DailyTradeResult $record): bool => $record->trashed()),
            ]);
    }
}
