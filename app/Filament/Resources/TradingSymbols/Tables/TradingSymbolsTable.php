<?php

namespace App\Filament\Resources\TradingSymbols\Tables;

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

class TradingSymbolsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('symbol_code')->label('Symbol Code')->searchable()->sortable(),
                TextColumn::make('name')->label('Name')->searchable()->sortable(),
                TextColumn::make('exchange')->label('Exchange')->sortable(),
                TextColumn::make('segment')->label('Segment')->badge()->sortable(),
                TextColumn::make('lot_size')->label('Lot Size')->numeric()->sortable(),
                TextColumn::make('tick_size')->label('Tick Size')->numeric()->sortable(),
                ToggleColumn::make('is_active')->label('Active')->alignCenter(),
                // TextColumn::make('created_by')->label('Created By')->sortable()->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('updated_by')->label('Updated By')->sortable()->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('deleted_by')->label('Deleted By')->sortable()->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('created_at')->label('Created At')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('updated_at')->label('Updated At')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('deleted_at')->label('Deleted At')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('symbol_code', 'asc')
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
