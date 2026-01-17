<?php

namespace App\Filament\Resources\StockTradeExecutions\Schemas;

use App\Models\TradingSymbol;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class StockTradeExecutionForm
{
    private static function isWithinTradingWindow($value): bool
    {
        if (! $value) {
            return true;
        }

        $dt = Carbon::parse($value, 'Asia/Kolkata');
        $start = Carbon::parse($dt->toDateString() . ' 09:15:00', 'Asia/Kolkata');
        $end   = Carbon::parse($dt->toDateString() . ' 15:30:00', 'Asia/Kolkata');

        return $dt->between($start, $end, true);
    }

    private static function clampToTradingWindow($value)
    {
        if (! $value) {
            return null;
        }

        $dt = Carbon::parse($value, 'Asia/Kolkata');
        $start = Carbon::parse($dt->toDateString() . ' 09:15:00', 'Asia/Kolkata');
        $end   = Carbon::parse($dt->toDateString() . ' 15:30:00', 'Asia/Kolkata');

        if ($dt->lt($start)) {
            return $start;
        }

        if ($dt->gt($end)) {
            return $end;
        }

        return $dt;
    }
    // Add helper methods for expiry and expected return, copied from StockTipForm
    protected static function updateExpiryDate(callable $set, callable $get): void
    {
        $tipDate = $get('tip_date');
        $days = (int) $get('holding_days');
        if ($tipDate && $days > 0) {
            $expiry = \Carbon\Carbon::parse($tipDate)->addDays($days);
            $set('expiry_date', $expiry->toDateString());
        } else {
            $set('expiry_date', null);
        }
    }

    protected static function updateExpectedReturn(callable $set, callable $get): void
    {
        $buy = (float) $get('buy_price');
        $target = (float) $get('target_price');
        if ($buy > 0 && $target > 0) {
            $percent = (($target - $buy) / $buy) * 100;
            $set('expected_return_percent', round($percent, 2));
        } else {
            $set('expected_return_percent', null);
        }
    }
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Stock Trade Execution Details')
                ->columnSpanFull()
                ->schema([
                    Grid::make([
                        'default' => 1,
                        'md' => 3,
                    ])->schema([
                        Select::make('trading_symbol_id')
                            ->options(
                                TradingSymbol::where('segment', 'STOCK')
                                    ->where('is_active', 1)
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn(callable $set) => $set('stock_tip_id', null))
                            ->createOptionAction(
                                fn(Action $action) =>
                                $action->modalHeading('Add Stock')->modalWidth('sm')
                            )
                            ->createOptionForm([
                                TextInput::make('symbol_code')
                                    ->required()
                                    ->unique('trading_symbols', 'symbol_code'),
                                TextInput::make('name')
                                    ->required(),
                            ])
                            ->createOptionUsing(
                                fn(array $data) =>
                                TradingSymbol::create([
                                    'symbol_code' => strtoupper($data['symbol_code']),
                                    'name'        => $data['name'],
                                    'segment'     => 'STOCK',
                                    'lot_size'    => 1,
                                    'tick_size'   => 0.05,
                                    'is_active'   => true,
                                ])->id
                            ),
                        Select::make('stock_tip_id')
                            ->label('Stock Tip')
                            ->options(function (callable $get) {
                                $symbolId = $get('trading_symbol_id');
                                if (! $symbolId) {
                                    return [];
                                }

                                return \App\Models\StockTip::query()
                                    ->where('trading_symbol_id', $symbolId)
                                    ->where('is_active', 1)
                                    ->orderByDesc('tip_date')
                                    ->get()
                                    ->mapWithKeys(function ($tip) {
                                        $symbol = $tip->symbol->name ?? '';
                                        $date = $tip->tip_date ? $tip->tip_date->format('Y-m-d') : '';
                                        $buy = $tip->buy_price ?? '';
                                        $label = "$symbol | $date | Buy: ₹$buy";
                                        return [$tip->id => $label];
                                    })
                                    ->toArray();
                            })
                            ->searchable()
                            ->live()
                            ->disabled(fn(callable $get) => ! $get('trading_symbol_id'))
                            ->createOptionAction(
                                fn(Action $action) =>
                                $action->modalHeading('Add Stock Tip')->modalWidth('4xl')
                            )
                            ->createOptionForm([
                                Grid::make([
                                    'default' => 1,
                                    'md' => 3,
                                ])->schema([
                                    Select::make('trading_symbol_id')
                                        ->label('Symbol')
                                        ->options(
                                            TradingSymbol::where('segment', 'STOCK')
                                                ->where('is_active', 1)
                                                ->pluck('name', 'id')
                                        )
                                        ->searchable()
                                        ->required(),
                                    DatePicker::make('tip_date')
                                        ->required()
                                        ->default(now())
                                        ->reactive()
                                        ->afterStateUpdated(
                                            fn($state, callable $set, callable $get) =>
                                            self::updateExpiryDate($set, $get)
                                        ),
                                    TextInput::make('buy_price')
                                        ->numeric()
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(
                                            fn($state, callable $set, callable $get) =>
                                            self::updateExpectedReturn($set, $get)
                                        ),
                                    TextInput::make('stop_loss')
                                        ->numeric()
                                        ->required(),
                                    TextInput::make('target_price')
                                        ->numeric()
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(
                                            fn($state, callable $set, callable $get) =>
                                            self::updateExpectedReturn($set, $get)
                                        ),
                                    TextInput::make('holding_days')
                                        ->numeric()
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(
                                            fn($state, callable $set, callable $get) =>
                                            self::updateExpiryDate($set, $get)
                                        ),
                                    DatePicker::make('expiry_date'),
                                    TextInput::make('expected_return_percent')
                                        ->label('Expected Return (%)')
                                        ->numeric()
                                        ->readOnly()
                                        ->suffix('%'),
                                    Select::make('status')
                                        ->options([
                                            'active' => 'Active',
                                            'completed' => 'Completed',
                                            'expired' => 'Expired',
                                        ])
                                        ->default('active')
                                        ->required(),
                                ]),
                                Textarea::make('notes')
                                    ->columnSpanFull(),
                            ])
                            ->createOptionUsing(
                                fn(array $data) => \App\Models\StockTip::create($data)->id
                            ),
                        Select::make('execution_type')
                            ->options(['BUY' => 'Buy', 'SELL' => 'Sell'])
                            ->required(),
                        TextInput::make('quantity')
                            ->required()
                            ->numeric(),
                        TextInput::make('price')
                            ->required()
                            ->numeric(),
                        DateTimePicker::make('execution_at')
                            ->label('Execution At')
                            ->seconds(false)
                            ->timezone('Asia/Kolkata')
                            ->default(function () {
                                $now = now('Asia/Kolkata');
                                $start = $now->copy()->setTime(9, 15);
                                $end   = $now->copy()->setTime(15, 30);

                                if ($now->lt($start)) {
                                    return $start;
                                }

                                if ($now->gt($end)) {
                                    return $end;
                                }

                                return $now;
                            })
                            ->afterStateUpdated(function ($state, callable $set) {
                                $clamped = self::clampToTradingWindow($state);
                                if ($clamped) {
                                    $set('execution_at', $clamped);
                                }
                            })
                            ->rule([
                                function (string $attribute, $value, callable $fail) {
                                    if (! self::isWithinTradingWindow($value)) {
                                        $fail('Execution time must be between 9:15 AM and 3:30 PM (IST).');
                                    }
                                },
                            ])
                            ->required(),
                        Textarea::make('execution_notes')
                            ->default(null)
                            ->columnSpanFull(),
                    ]),
                ]),
        ]);
    }
}
