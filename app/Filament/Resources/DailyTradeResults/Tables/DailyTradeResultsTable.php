<?php

namespace App\Filament\Resources\DailyTradeResults\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DailyTradeResultsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('daily_trade_plan_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('trade_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('entry_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('exit_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('entry_price')
                    ->money()
                    ->sortable(),
                TextColumn::make('exit_price')
                    ->money()
                    ->sortable(),
                TextColumn::make('points')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pnl_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pnl_percent')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('result')
                    ->badge(),
                TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('deleted_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
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
