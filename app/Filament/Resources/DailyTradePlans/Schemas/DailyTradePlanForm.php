<?php

namespace App\Filament\Resources\DailyTradePlans\Schemas;

use App\Models\OptionContract;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class DailyTradePlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Stock Tip Details')
                ->columnSpanFull()
                ->schema([

                    Grid::make([
                        'default' => 1,
                        'md' => 3,
                    ])->schema([

                        DatePicker::make('trade_date')
                            ->required()
                            ->default(now()),
                        Select::make('trading_symbol_id')
                            ->relationship('symbol', 'name', function ($query) {
                                $query->whereIn('segment', ['INDEX', 'COMMODITY']);
                            })
                            // no searchable()
                            ->required()
                            ->reactive()
                            ->placeholder('-- Select Symbol --'),
                        Select::make('option_contract_id')
                            ->label('Option Contract')
                            ->options(function ($get) {
                                $symbolId = $get('trading_symbol_id');
                                if (!$symbolId) {
                                    return [];
                                }
                                return \App\Models\OptionContract::where('trading_symbol_id', $symbolId)
                                    ->pluck('contract_code', 'id');
                            })
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if (!$state) {
                                    return;
                                }

                                $contract = \App\Models\OptionContract::find($state);
                                if ($contract) {
                                    $set('trading_symbol_id', $contract->trading_symbol_id);
                                }
                                if ($contract && $contract->symbol) {
                                    $set('lot_size_display', $contract->symbol->lot_size);
                                }
                            })
                            ->createOptionAction(
                                fn(Action $action) =>
                                $action
                                    ->modalHeading('Add Option Contract')
                                    ->modalWidth('lg')
                            )
                            ->createOptionForm([
                                Select::make('trading_symbol_id')
                                    ->label('Symbol')
                                    ->options(fn() => \App\Models\TradingSymbol::whereIn('segment', ['INDEX', 'COMMODITY'])
                                        ->where('is_active', 1)
                                        ->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $symbol = \App\Models\TradingSymbol::find($state);
                                        if ($symbol) {
                                            $name = strtolower($symbol->name);
                                            $baseCode = $symbol->symbol_code;
                                            $strike = $get('strike_price');
                                            $optionType = $get('option_type');
                                            $expiry = null;
                                            $isWeekly = 0;
                                            if ($name === 'nifty 50') {
                                                $expiryDate = \Carbon\Carbon::now()->next(2); // Tuesday
                                                $expiry = $expiryDate->format('d-m-Y');
                                                $isWeekly = 1;
                                            } elseif ($name === 'bank nifty') {
                                                $now = \Carbon\Carbon::now();
                                                $lastThursday = $now->copy()->endOfMonth()->previous(4);
                                                if ($now->greaterThan($lastThursday)) {
                                                    $lastThursday = $now->copy()->addMonth()->endOfMonth()->previous(4);
                                                }
                                                $expiry = $lastThursday->format('d-m-Y');
                                            } elseif ($name === 'sensex') {
                                                $expiryDate = \Carbon\Carbon::now()->next(4); // Tuesday
                                                $expiry = $expiryDate->format('d-m-Y');
                                                $isWeekly = 1;
                                            } elseif (str_contains($name, 'natural gas')) {
                                                $now = \Carbon\Carbon::now();
                                                $expiryDate = $now->copy()->day(26);
                                                if ($now->greaterThan($expiryDate)) {
                                                    $expiryDate = $now->copy()->addMonth()->day(26);
                                                }
                                                $expiry = $expiryDate->format('d-m-Y');
                                            } elseif (str_contains($name, 'crude oil')) {
                                                $now = \Carbon\Carbon::now();
                                                $expiryDate = $now->copy()->day(19);
                                                if ($now->greaterThan($expiryDate)) {
                                                    $expiryDate = $now->copy()->addMonth()->day(19);
                                                }
                                                $expiry = $expiryDate->format('d-m-Y');
                                            }
                                            if ($expiry) {
                                                $set('expiry_date', $expiry);
                                            }
                                            if (!is_null($isWeekly)) {
                                                $set('is_weekly', $isWeekly);
                                            }
                                            $set('lot_size', $symbol->lot_size);
                                            // Format expiry for contract code: DDMMMYY (e.g., 26Jan01)
                                            $expiryForCode = $expiry ? Carbon::parse($expiry)->format('dMy') : '{EXPIRY}';
                                            $contractCode = $baseCode . $expiryForCode . ($strike ?: '{STRIKE}') . ($optionType ?: '{OPTION_TYPE}');
                                            $set('contract_code', $contractCode);
                                        }
                                    }),
                                DatePicker::make('expiry_date')
                                    ->required()
                                    ->default(now()),
                                TextInput::make('strike_price')
                                    ->required()
                                    ->numeric()
                                    ->reactive()
                                    ->debounce(500)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $symbolId = $get('trading_symbol_id');
                                        $symbol = $symbolId ? \App\Models\TradingSymbol::find($symbolId) : null;
                                        if ($symbol) {
                                            $name = strtolower($symbol->name);
                                            $baseCode = $symbol->symbol_code;
                                            $strike = $state;
                                            $optionType = $get('option_type');
                                            $expiry = $get('expiry_date');
                                            $expiryForCode = $expiry ? Carbon::parse($expiry)->format('dMy') : '{EXPIRY}';
                                            $contractCode = $baseCode . $expiryForCode . ($strike ?: '{STRIKE}') . ($optionType ?: '{OPTION_TYPE}');
                                            $set('contract_code', $contractCode);
                                        }
                                    }),
                                Select::make('option_type')
                                    ->options(['CE' => 'CE', 'PE' => 'PE'])
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $symbolId = $get('trading_symbol_id');
                                        $symbol = $symbolId ? \App\Models\TradingSymbol::find($symbolId) : null;
                                        if ($symbol) {
                                            $name = strtolower($symbol->name);
                                            $baseCode = $symbol->symbol_code;
                                            $strike = $get('strike_price');
                                            $optionType = $state;
                                            $expiry = $get('expiry_date');
                                            $expiryForCode = $expiry ? Carbon::parse($expiry)->format('dMy') : '{EXPIRY}';
                                            $contractCode = $baseCode . $expiryForCode . ($strike ?: '{STRIKE}') . ($optionType ?: '{OPTION_TYPE}');
                                            $set('contract_code', $contractCode);
                                        }
                                    }),
                                TextInput::make('lot_size')
                                    ->required()
                                    ->numeric(),
                                TextInput::make('contract_code')
                                    ->required()
                                    ->default(null),
                                Toggle::make('is_weekly')
                                    ->default(true),
                            ])
                            ->createOptionUsing(function (array $data) {
                                return OptionContract::create([
                                    'trading_symbol_id' => $data['trading_symbol_id'],
                                    'expiry_date' => $data['expiry_date'],
                                    'strike_price' => $data['strike_price'],
                                    'option_type' => $data['option_type'],
                                    'lot_size' => $data['lot_size'] ?? 1,
                                    'is_weekly' => $data['is_weekly'] ?? false,
                                    'contract_code' => strtoupper($data['contract_code']),
                                    'is_active' => true,
                                ])->id;
                            }),
                        TextInput::make('current_price')
                            ->required()
                            ->numeric()
                            ->reactive()
                            ->debounce(400)
                            ->afterStateUpdated(fn($state, $set, $get) => static::calculateExpected($set, $get)),
                        TextInput::make('lot_size_display')
                            ->label('Lot Size')
                            ->numeric()
                            ->readOnly()
                            ->dehydrated(false)
                            ->placeholder('Auto-filled from contract'),
                        TextInput::make('planned_entry_price')
                            ->required()
                            ->numeric()
                            ->reactive()
                            ->debounce(400)
                            ->afterStateUpdated(fn($state, $set, $get) => static::calculateExpected($set, $get)),
                        TextInput::make('stop_loss')
                            ->required()
                            ->numeric()
                            ->reactive()
                            ->debounce(400)
                            ->afterStateUpdated(fn($state, $set, $get) => static::calculateExpected($set, $get)),
                        TextInput::make('target_price')
                            ->required()
                            ->numeric()
                            ->reactive()
                            ->debounce(400)
                            ->afterStateUpdated(fn($state, $set, $get) => static::calculateExpected($set, $get)),
                        TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->reactive()
                            ->debounce(400)
                            ->afterStateUpdated(fn($state, $set, $get) => static::calculateExpected($set, $get)),
                        TextInput::make('expected_profit')
                            ->numeric()
                            ->default(null)
                            ->readOnly(),
                        TextInput::make('expected_loss')
                            ->numeric()
                            ->default(null)
                            ->readOnly(),
                        TextInput::make('expected_return_percent')
                            ->label('Expected Return (%)')
                            ->numeric()
                            ->default(null)
                            ->readOnly(),
                        Select::make('status')
                            ->options(['planned' => 'Planned', 'executed' => 'Executed', 'skipped' => 'Skipped'])
                            ->default('planned')
                            ->required(),
                        Textarea::make('notes')
                            ->default(null)
                            ->columnSpanFull(),
                        // TextInput::make('created_by')
                        //     ->numeric()
                        //     ->default(null),
                        // TextInput::make('updated_by')
                        //     ->numeric()
                        //     ->default(null),
                        // TextInput::make('deleted_by')
                        //     ->numeric()
                        //     ->default(null),
                    ]),
                ]),
        ]);
    }
    /**
     * Auto-calculate expected profit, loss, and return percent.
     */
    protected static function calculateExpected($set, $get)
    {
        $entry = floatval($get('planned_entry_price'));
        $target = floatval($get('target_price'));
        $stop = floatval($get('stop_loss'));
        $qty = floatval($get('quantity'));
        if ($entry && $target && $qty) {
            $profit = ($target - $entry) * $qty;
            $set('expected_profit', round($profit, 2));
        } else {
            $set('expected_profit', null);
        }
        if ($entry && $stop && $qty) {
            $loss = ($entry - $stop) * $qty;
            $set('expected_loss', round($loss, 2));
        } else {
            $set('expected_loss', null);
        }
        if ($entry && $target) {
            $ret = (($target - $entry) / $entry) * 100;
            $set('expected_return_percent', round($ret, 2));
        } else {
            $set('expected_return_percent', null);
        }
    }
}
