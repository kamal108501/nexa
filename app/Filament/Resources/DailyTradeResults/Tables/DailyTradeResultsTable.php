<?php

namespace App\Filament\Resources\DailyTradeResults\Tables;

use App\Models\OptionContract;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
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
                    ->date(timezone: 'Asia/Kolkata')
                    ->sortable(),

                TextColumn::make('entry_time')
                    ->dateTime(timezone: 'Asia/Kolkata')
                    ->sortable(),

                TextColumn::make('exit_time')
                    ->dateTime(timezone: 'Asia/Kolkata')
                    ->sortable(),

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
                    ->color(
                        fn($state) =>
                        $state > 0 ? 'success' : ($state < 0 ? 'danger' : null)
                    )
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
                        'loss'   => 'danger',
                        default  => 'secondary',
                    }),
            ])

            /* ---------------- FILTERS ---------------- */
            ->filters([
                // 🔹 Trading Symbol
                SelectFilter::make('symbol_id')
                    ->label('Trading Symbol')
                    ->relationship('tradePlan.symbol', 'name')
                    ->searchable()
                    ->query(function ($query, $value) {
                        if ($value) {
                            $query->whereHas(
                                'tradePlan',
                                fn($q) =>
                                $q->where('symbol_id', $value)
                            );
                        }
                    }),

                // 🔹 Contract Code (depends on symbol)
                SelectFilter::make('contract_code')
                    ->label('Contract Code')
                    ->searchable()
                    ->options(function ($state, $livewire) {
                        $symbolId = $livewire->filters['symbol_id'] ?? null;

                        $query = OptionContract::query();

                        if ($symbolId) {
                            $query->where('symbol_id', $symbolId);
                        }

                        return $query
                            ->orderBy('contract_code')
                            ->pluck('contract_code', 'contract_code')
                            ->toArray();
                    })
                    ->query(function ($query, $value) {
                        if ($value) {
                            $query->whereHas(
                                'tradePlan.optionContract',
                                fn($q) => $q->where('contract_code', $value)
                            );
                        }
                    }),

                Filter::make('trade_date')
                    ->form([
                        DatePicker::make('start')
                            ->label('Start Date')
                            ->default(now()->startOfMonth()),

                        DatePicker::make('end')
                            ->label('End Date')
                            ->default(now()->endOfMonth()),
                    ])
                    ->columns(2)
                    ->query(function ($query, $data) {
                        if (! empty($data['start'])) {
                            $query->whereDate('trade_date', '>=', $data['start']);
                        }

                        if (! empty($data['end'])) {
                            $query->whereDate('trade_date', '<=', $data['end']);
                        }
                    })
                    ->indicateUsing(function ($data) {
                        if ($data['start'] && $data['end']) {
                            return "From {$data['start']} to {$data['end']}";
                        }

                        if ($data['start']) {
                            return "From {$data['start']}";
                        }

                        if ($data['end']) {
                            return "Until {$data['end']}";
                        }

                        return null;
                    }),
            ])

            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->filtersTriggerAction(null)
            ->deferFilters(false)

            ->defaultSort('trade_date', 'desc')
            ->searchable([
                'tradePlan.optionContract.contract_code',
                'tradePlan.symbol.name',
                'result',
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
            ])

            /* ------------ ROW STYLING ------------ */
            ->recordClasses(fn($record) => match (true) {
                $record->pnl_amount > 0 => 'bg-green-100 text-green-900 font-semibold',
                $record->pnl_amount < 0 => 'bg-red-100 text-red-900 font-semibold',
                default => null,
            });
    }
}
