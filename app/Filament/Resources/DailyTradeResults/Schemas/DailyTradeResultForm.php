<?php

namespace App\Filament\Resources\DailyTradeResults\Schemas;

use App\Models\DailyTradePlan;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class DailyTradeResultForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Hidden::make('created_by')
                ->default(fn() => Auth::id())
                ->required(),

            Section::make('Trade Result')
                ->columnSpanFull()
                ->schema([
                    Grid::make([
                        'default' => 1,
                        'md' => 3,
                    ])->schema([

                        // Trade selection
                        Select::make('daily_trade_plan_id')
                            ->label('Option Contract (from plans)')
                            ->options(function () {
                                return \App\Models\DailyTradePlan::query()
                                    ->with('optionContract')
                                    ->whereHas('optionContract')
                                    ->get()
                                    ->mapWithKeys(fn($plan) => [
                                        $plan->id => $plan->optionContract?->contract_code,
                                    ])->filter();
                            })
                            ->searchable()
                            ->required(),

                        // Dates & times
                        DatePicker::make('trade_date')
                            ->required()
                            ->default(now())
                            ->dehydrated(true),

                        DateTimePicker::make('entry_time')
                            ->timezone('Asia/Kolkata')
                            ->dehydrateStateUsing(fn($state) => $state ? Carbon::parse($state, 'Asia/Kolkata')->setTimezone('UTC') : null)
                            ->default(now()),

                        DateTimePicker::make('exit_time')
                            ->timezone('Asia/Kolkata')
                            ->dehydrateStateUsing(fn($state) => $state ? Carbon::parse($state, 'Asia/Kolkata')->setTimezone('UTC') : null)
                            ->default(now()->addMinutes(15)),

                        // Prices
                        TextInput::make('entry_price')
                            ->required()
                            ->numeric()
                            ->lazy()
                            ->afterStateUpdated(
                                fn($state, $set, $get) => self::recalculatePnL($set, $get)
                            ),

                        TextInput::make('exit_price')
                            ->required()
                            ->numeric()
                            ->lazy()
                            ->afterStateUpdated(
                                fn($state, $set, $get) => self::recalculatePnL($set, $get)
                            ),

                        // Auto-calculated fields
                        TextInput::make('points')
                            ->disabled()
                            ->dehydrated(true)
                            ->placeholder('Auto'),

                        TextInput::make('pnl_amount')
                            ->disabled()
                            ->dehydrated(true)
                            ->placeholder('Auto'),

                        TextInput::make('pnl_percent')
                            ->disabled()
                            ->dehydrated(true)
                            ->placeholder('Auto'),

                        // Result (auto)
                        Select::make('result')
                            ->options([
                                'profit' => 'Profit',
                                'loss'   => 'Loss',
                            ])
                            ->disabled()
                            ->dehydrated(true)
                            ->required(),
                    ]),
                ]),
        ]);
    }

    /**
     * Recalculate points, pnl amount, pnl percent and result
     */
    private static function recalculatePnL(callable $set, callable $get): void
    {
        $entry = (float) ($get('entry_price') ?? 0);
        $exit  = (float) ($get('exit_price') ?? 0);
        $planId = $get('daily_trade_plan_id');

        $lotSize = 1;

        if ($planId) {
            $plan = DailyTradePlan::find($planId);
            if ($plan && $plan->optionContract) {
                $lotSize = (float) $plan->optionContract->lot_size;
            }
        }

        $points = $exit - $entry;
        $pnlAmount = $points * $lotSize;
        $pnlPercent = $entry != 0
            ? round(($points / $entry) * 100, 2)
            : 0;

        $set('points', round($points, 2));
        $set('pnl_amount', round($pnlAmount, 2));
        $set('pnl_percent', $pnlPercent);

        // Auto result
        $set('result', $pnlAmount >= 0 ? 'profit' : 'loss');
    }
}
