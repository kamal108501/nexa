<?php

namespace App\Filament\Resources\StockTips\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class StockTipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('symbol.name')
                    ->label('Stock')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tip_date')
                    ->label('Tip Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('buy_price')
                    ->label('Buy Price')
                    ->money('INR')
                    ->sortable(),
                TextColumn::make('stop_loss')
                    ->label('Stop Loss')
                    ->money('INR')
                    ->sortable(),
                TextColumn::make('target_price')
                    ->label('Target')
                    ->money('INR')
                    ->sortable(),
                TextColumn::make('holding_days')
                    ->label('Hold Days')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expiry_date')
                    ->label('Expiry')
                    ->date()
                    ->sortable(),
                TextColumn::make('expected_return_percent')
                    ->label('Expected Return (%)')
                    ->numeric()
                    ->sortable()
                    ->suffix('%'),
                TextColumn::make('term')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'short_term' => 'info',
                        'long_term' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => ucwords(str_replace('_', ' ', $state)))
                    ->sortable(),
                TextColumn::make('exit_price')
                    ->label('Exit Price')
                    ->money('INR')
                    ->sortable(),
                TextColumn::make('exit_date')
                    ->label('Exit Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'active' => 'info',
                        'completed' => 'success',
                        'sl_hit' => 'danger',
                        'expired' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => ucfirst(str_replace('_', ' ', $state)))
                    ->sortable(),
                // TextColumn::make('created_by')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('updated_by')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('deleted_by')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('deleted_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // TrashedFilter::make(),
            ])
            ->recordActions([
                // ViewAction::make(),
                EditAction::make()
                    ->disabled(fn($record) => $record->status !== 'active'),
                DeleteAction::make()
                    ->disabled(fn($record) => $record->status !== 'active'),
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
