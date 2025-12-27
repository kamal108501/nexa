<?php

namespace App\Filament\Resources\StockTradeExecutions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Columns\Summarizers\Summarizer;

class StockTradeExecutionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                /* ================= EXECUTION ROWS ================= */

                TextColumn::make('symbol.name')
                    ->label('Stock')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('stock_tip_id')
                    ->label('From Tip')
                    ->formatStateUsing(fn($state) => $state ? 'Yes' : 'No')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'gray'),

                TextColumn::make('execution_type')
                    ->badge()
                    ->color(fn($state) => $state === 'buy' ? 'success' : 'danger'),

                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('price')
                    ->money('INR')
                    ->sortable(),

                TextColumn::make('execution_date')
                    ->date()
                    ->sortable(),

                // IconColumn::make('is_active')
                // ->boolean(),
                TextColumn::make('profit_loss')
                    ->label('Profit / Loss')
                    ->state(function ($record) {
                        if ($record->execution_type !== 'sell') {
                            return '-';
                        }
                        // Get all previous buy trades for this symbol, ordered by execution_date (FIFO)
                        $buys = $record->newQuery()
                            ->where('symbol_id', $record->symbol_id)
                            ->where('execution_type', 'buy')
                            ->where('execution_date', '<=', $record->execution_date)
                            ->orderBy('execution_date', 'asc')
                            ->get();
                        $sellQty = $record->quantity;
                        $matchedQty = 0;
                        $totalBuyCost = 0;
                        foreach ($buys as $buy) {
                            $qtyToUse = min($buy->quantity, $sellQty - $matchedQty);
                            $totalBuyCost += $qtyToUse * $buy->price;
                            $matchedQty += $qtyToUse;
                            if ($matchedQty >= $sellQty) {
                                break;
                            }
                        }
                        if ($matchedQty < $sellQty || $matchedQty == 0) {
                            return '-';
                        }
                        $avgBuyPrice = $totalBuyCost / $sellQty;
                        $profitLoss = ($record->price - $avgBuyPrice) * $sellQty;
                        return number_format($profitLoss, 2);
                    })
                    ->badge()
                    ->color(fn($state) => is_numeric($state) && $state >= 0 ? 'success' : 'danger'),

                /* ================= GROUP SUMMARY ================= */

                TextColumn::make('invested_amount')
                    ->label('Invested')
                    ->state(fn() => null)
                    ->summarize([
                        Summarizer::make()->using(function ($query) {
                            return (clone $query)
                                ->where('execution_type', 'buy')
                                ->get()
                                ->sum(fn($e) => $e->quantity * $e->price);
                        }),
                    ])
                    ->money('INR'),

                TextColumn::make('realized_pnl')
                    ->label('P / L')
                    ->state(fn() => null)
                    ->summarize([
                        Summarizer::make()->using(function ($query) {

                            $buyQuery  = clone $query;
                            $sellQuery = clone $query;

                            $buyQty = $buyQuery->where('execution_type', 'buy')->sum('quantity');
                            if ($buyQty == 0) {
                                return 0;
                            }

                            $buyAmt = $buyQuery
                                ->get()
                                ->sum(fn($e) => $e->quantity * $e->price);

                            $avgBuy = $buyAmt / $buyQty;

                            $sellQty = $sellQuery->where('execution_type', 'sell')->sum('quantity');
                            $sellAmt = $sellQuery
                                ->get()
                                ->sum(fn($e) => $e->quantity * $e->price);

                            return round($sellAmt - ($avgBuy * $sellQty), 2);
                        }),
                    ])
                    ->money('INR')
                    ->badge()
                    ->color(fn($state) => $state >= 0 ? 'success' : 'danger'),

                TextColumn::make('pnl_percent')
                    ->label('P / L %')
                    ->state(fn() => null)
                    ->summarize([
                        Summarizer::make()->using(function ($query) {

                            $buyQuery  = clone $query;
                            $sellQuery = clone $query;

                            $buyQty = $buyQuery->where('execution_type', 'buy')->sum('quantity');
                            if ($buyQty == 0) {
                                return 0;
                            }

                            $buyAmt = $buyQuery
                                ->get()
                                ->sum(fn($e) => $e->quantity * $e->price);

                            $avgBuy = $buyAmt / $buyQty;

                            $sellQty = $sellQuery->where('execution_type', 'sell')->sum('quantity');
                            $sellAmt = $sellQuery
                                ->get()
                                ->sum(fn($e) => $e->quantity * $e->price);

                            $pnl = $sellAmt - ($avgBuy * $sellQty);

                            return round(($pnl / $buyAmt) * 100, 2);
                        }),
                    ])
                    ->suffix('%')
                    ->badge()
                    ->color(fn($state) => $state >= 0 ? 'success' : 'danger'),
                TextColumn::make('pnl_percent')
                    ->label('P / L %')
                    ->state(fn() => null)
                    ->summarize([
                        Summarizer::make()->using(function ($query) {

                            $buyQuery  = clone $query;
                            $sellQuery = clone $query;

                            $buyQty = $buyQuery->where('execution_type', 'buy')->sum('quantity');
                            if ($buyQty == 0) {
                                return 0;
                            }

                            $buyAmt = $buyQuery
                                ->get()
                                ->sum(fn($e) => $e->quantity * $e->price);

                            $avgBuy = $buyAmt / $buyQty;

                            $sellQty = $sellQuery->where('execution_type', 'sell')->sum('quantity');
                            $sellAmt = $sellQuery
                                ->get()
                                ->sum(fn($e) => $e->quantity * $e->price);

                            $pnl = $sellAmt - ($avgBuy * $sellQty);

                            return round(($pnl / $buyAmt) * 100, 2);
                        }),
                    ])
                    ->suffix('%')
                    ->badge()
                    ->color(fn($state) => $state >= 0 ? 'success' : 'danger'),
                TextColumn::make('pnl_percent')
                    ->label('P / L %')
                    ->state(fn() => null)
                    ->summarize([
                        Summarizer::make()->using(function ($query) {

                            $buyQuery  = clone $query;
                            $sellQuery = clone $query;

                            $buyQty = $buyQuery->where('execution_type', 'buy')->sum('quantity');
                            if ($buyQty == 0) {
                                return 0;
                            }

                            $buyAmt = $buyQuery
                                ->get()
                                ->sum(fn($e) => $e->quantity * $e->price);

                            $avgBuy = $buyAmt / $buyQty;

                            $sellQty = $sellQuery->where('execution_type', 'sell')->sum('quantity');
                            $sellAmt = $sellQuery
                                ->get()
                                ->sum(fn($e) => $e->quantity * $e->price);

                            $pnl = $sellAmt - ($avgBuy * $sellQty);

                            return round(($pnl / $buyAmt) * 100, 2);
                        }),
                    ])
                    ->suffix('%')
                    ->badge()
                    ->color(fn($state) => $state >= 0 ? 'success' : 'danger'),
                TextColumn::make('pnl_percent')
                    ->label('P / L %')
                    ->state(fn() => null)
                    ->summarize([
                        Summarizer::make()->using(function ($query) {

                            $buyQuery  = clone $query;
                            $sellQuery = clone $query;

                            $buyQty = $buyQuery->where('execution_type', 'buy')->sum('quantity');
                            if ($buyQty == 0) {
                                return 0;
                            }

                            $buyAmt = $buyQuery
                                ->get()
                                ->sum(fn($e) => $e->quantity * $e->price);

                            $avgBuy = $buyAmt / $buyQty;

                            $sellQty = $sellQuery->where('execution_type', 'sell')->sum('quantity');
                            $sellAmt = $sellQuery
                                ->get()
                                ->sum(fn($e) => $e->quantity * $e->price);

                            $pnl = $sellAmt - ($avgBuy * $sellQty);

                            return round(($pnl / $buyAmt) * 100, 2);
                        }),
                    ])
                    ->suffix('%')
                    ->badge()
                    ->color(fn($state) => $state >= 0 ? 'success' : 'danger'),

                TextColumn::make('pnl_percent')
                    ->label('P / L %')
                    ->state(fn() => null)
                    ->summarize([
                        Summarizer::make()->using(function ($query) {

                            $buyQuery  = clone $query;
                            $sellQuery = clone $query;

                            $buyQty = $buyQuery->where('execution_type', 'buy')->sum('quantity');
                            if ($buyQty == 0) {
                                return 0;
                            }

                            $buyAmt = $buyQuery
                                ->get()
                                ->sum(fn($e) => $e->quantity * $e->price);

                            $avgBuy = $buyAmt / $buyQty;

                            $sellQty = $sellQuery->where('execution_type', 'sell')->sum('quantity');
                            $sellAmt = $sellQuery
                                ->get()
                                ->sum(fn($e) => $e->quantity * $e->price);

                            $pnl = $sellAmt - ($avgBuy * $sellQty);

                            return round(($pnl / $buyAmt) * 100, 2);
                        }),
                    ])
                    ->suffix('%')
                    ->badge()
                    ->color(fn($state) => $state >= 0 ? 'success' : 'danger'),

            ])

            /* ================= GROUPING ================= */

            ->groups([
                Group::make('symbol.name')
                    ->label('Stock')
                    ->collapsible(),

                Group::make('stockTip.tip_date')
                    ->label('Tip')
                    ->collapsible(),
            ])

            /* ================= FILTERS ================= */

            ->filters([
                // TrashedFilter::make(),
            ])

            /* ================= ROW ACTIONS ================= */

            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])

            /* ================= BULK ACTIONS ================= */

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
