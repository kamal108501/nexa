<?php

namespace App\Filament\Resources\StockTipResults\Schemas;

use App\Models\StockTipResult;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StockTipResultInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('stockTip.id')
                    ->label('Stock tip'),
                TextEntry::make('exit_price')
                    ->money(),
                TextEntry::make('exit_date')
                    ->date(),
                TextEntry::make('pnl_amount')
                    ->numeric(),
                TextEntry::make('pnl_percent')
                    ->numeric(),
                TextEntry::make('exit_reason')
                    ->badge(),
                IconEntry::make('is_correct')
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
                    ->visible(fn (StockTipResult $record): bool => $record->trashed()),
            ]);
    }
}
