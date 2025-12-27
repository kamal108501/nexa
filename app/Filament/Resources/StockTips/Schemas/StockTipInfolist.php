<?php

namespace App\Filament\Resources\StockTips\Schemas;

use App\Models\StockTip;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StockTipInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('symbol.name')
                    ->label('Symbol'),
                TextEntry::make('tip_date')
                    ->date(),
                TextEntry::make('buy_price')
                    ->money('INR'),
                TextEntry::make('stop_loss')
                    ->numeric(),
                TextEntry::make('target_price')
                    ->money('INR'),
                TextEntry::make('holding_days')
                    ->numeric(),
                TextEntry::make('expiry_date')
                    ->date(),
                TextEntry::make('expected_return_percent')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                // TextEntry::make('created_by')
                //     ->numeric()
                //     ->placeholder('-'),
                // TextEntry::make('updated_by')
                //     ->numeric()
                //     ->placeholder('-'),
                // TextEntry::make('deleted_by')
                //     ->numeric()
                //     ->placeholder('-'),
                // TextEntry::make('created_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('updated_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('deleted_at')
                //     ->dateTime()
                //     ->visible(fn (StockTip $record): bool => $record->trashed()),
            ]);
    }
}
