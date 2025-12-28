<?php

namespace App\Filament\Resources\DailyTradePlans\Tables;

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

use function Symfony\Component\Clock\now;

class DailyTradePlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trade_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('symbol.name')
                    ->searchable(),
                TextColumn::make('optionContract.contract_code')
                    ->label('Option Contract')
                    ->searchable(),
                TextColumn::make('current_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('planned_entry_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stop_loss')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('target_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expected_profit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expected_loss')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expected_return_percent')
                    ->label('Expected Return (%)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
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
                EditAction::make(),
                DeleteAction::make(),
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
