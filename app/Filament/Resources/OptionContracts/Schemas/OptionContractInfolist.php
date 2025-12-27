<?php

namespace App\Filament\Resources\OptionContracts\Schemas;

use App\Models\OptionContract;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OptionContractInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('symbol_id')
                    ->numeric(),
                TextEntry::make('expiry_date')
                    ->date(),
                TextEntry::make('strike_price')
                    ->money('INR'),
                TextEntry::make('option_type')
                    ->badge(),
                TextEntry::make('lot_size')
                    ->numeric(),
                TextEntry::make('tick_size')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('contract_code')
                    ->placeholder('-'),
                IconEntry::make('is_weekly')
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
                    ->visible(fn(OptionContract $record): bool => $record->trashed()),
            ]);
    }
}
