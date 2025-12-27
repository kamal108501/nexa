<?php

namespace App\Filament\Resources\OptionContracts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class OptionContractsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('symbol.symbol_code')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expiry_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('strike_price')
                    ->money('INR')
                    ->sortable(),
                TextColumn::make('option_type')
                    ->badge()
                    ->formatStateUsing(fn($state) => strtoupper($state))
                    ->color(fn($state) => $state === 'CE' ? 'success' : 'danger'),
                // ->icon(
                //     fn($state) => $state === 'CE'
                //         ? 'heroicon-o-arrow-trending-up'
                //         : 'heroicon-o-arrow-trending-down'
                // ),
                TextColumn::make('lot_size')
                    ->numeric()
                    ->sortable(),
                // TextColumn::make('tick_size')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('contract_code')
                    ->searchable(),
                IconColumn::make('is_weekly')
                    ->boolean(),
                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->alignCenter(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                // ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
