<?php

namespace App\Filament\Resources\DailyTradeResults\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DailyTradeResultsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tradePlan.optionContract.contract_code')
                    ->label('Contract Code')
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
                // ...existing code...
                TextColumn::make('entry_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('exit_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('points')
                    ->numeric()
                    ->sortable()
                    ->summarize([
                        Sum::make()->label('Total Points'),
                    ]),
                TextColumn::make('pnl_amount')
                    ->numeric()
                    ->sortable()
                    ->color(fn($state) => $state > 0 ? 'success' : ($state < 0 ? 'danger' : null))
                    ->summarize([
                        Sum::make()->label('Total Amount'),
                    ]),
                TextColumn::make('pnl_percent')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('result')
                    ->badge()
                    ->color(fn($state) => match (strtolower($state)) {
                        'profit' => 'success',
                        'loss' => 'danger',
                        default => 'secondary',
                    }),
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
                // Trading Symbol filter (searchable)
                \Filament\Tables\Filters\SelectFilter::make('symbol_id')
                    ->label('Trading Symbol')
                    ->relationship('tradePlan.symbol', 'name')
                    ->searchable()
                    ->query(function ($query, $value) {
                        if ($value) {
                            $query->whereHas('tradePlan', function ($q) use ($value) {
                                $q->where('symbol_id', $value);
                            });
                        }
                    }),

                // Contract Code filter, options depend on selected Trading Symbol
                \Filament\Tables\Filters\SelectFilter::make('contract_code')
                    ->label('Contract Code')
                    ->searchable()
                    ->options(function ($state, $livewire) {
                        $symbolId = $livewire->filters['symbol_id'] ?? null;
                        $query = \App\Models\OptionContract::query();
                        if ($symbolId) {
                            $query->where('symbol_id', $symbolId);
                        }
                        return $query->pluck('contract_code', 'contract_code')->toArray();
                    })
                    ->query(function ($query, $value) {
                        if ($value) {
                            $query->whereHas('tradePlan.optionContract', function ($q) use ($value) {
                                $q->where('contract_code', $value);
                            });
                        }
                    }),

                // Date range filter, single line, default to current month's first and last date
                \Filament\Tables\Filters\Filter::make('trade_date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('start')
                            ->label('Start Date')
                            ->default(now()->startOfMonth()->toDateString()),
                        \Filament\Forms\Components\DatePicker::make('end')
                            ->label('End Date')
                            ->default(now()->endOfMonth()->toDateString()),
                    ])
                    ->columns(2)
                    ->query(function ($query, $data) {
                        if ($data['start']) {
                            $query->where('trade_date', '>=', $data['start']);
                        }
                        if ($data['end']) {
                            $query->where('trade_date', '<=', $data['end']);
                        }
                    })
                    ->indicateUsing(function ($data) {
                        if ($data['start'] && $data['end']) {
                            return 'From ' . $data['start'] . ' to ' . $data['end'];
                        }
                        if ($data['start']) {
                            return 'From ' . $data['start'];
                        }
                        if ($data['end']) {
                            return 'Until ' . $data['end'];
                        }
                        return null;
                    }),
            ])
            ->defaultSort('trade_date', 'desc')
            ->searchable([
                'tradePlan.optionContract.contract_code',
                'tradePlan.symbol.name',
                'result',
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->filtersTriggerAction(fn($action) => $action->label('Apply Filters')->button()->color('primary'))
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
            ])
            ->recordClasses(fn($record) => match (true) {
                $record->pnl_amount > 0 => 'bg-green-100 text-green-900 font-semibold',
                $record->pnl_amount < 0 => 'bg-red-100 text-red-900 font-semibold',
                default => null,
            });
    }
}
