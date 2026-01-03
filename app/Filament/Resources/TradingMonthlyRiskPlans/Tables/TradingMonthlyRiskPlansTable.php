<?php

namespace App\Filament\Resources\TradingMonthlyRiskPlans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TradingMonthlyRiskPlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('user.name')
                //     ->searchable(),
                TextColumn::make('risk_year'),
                TextColumn::make('risk_month')
                    ->formatStateUsing(fn($state) => date('F', mktime(0, 0, 0, $state, 1)))
                    ->sortable(),
                TextColumn::make('base_max_loss')
                    ->label('Base Max Loss')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('profit_risk_percent')
                    ->label('Profit/Risk %')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('carry_profit_to_next_month')
                    ->label('Carry Profit')
                    ->boolean(),
                IconColumn::make('is_locked')
                    ->boolean(),
                ToggleColumn::make('is_active'),
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
                TrashedFilter::make(),
            ])
            ->recordActions([
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
