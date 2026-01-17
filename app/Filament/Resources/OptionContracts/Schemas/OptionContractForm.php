<?php

namespace App\Filament\Resources\OptionContracts\Schemas;

use App\Models\TradingSymbol;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Builder;

class OptionContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Option Contract Details')
                ->columnSpanFull()
                ->schema([
                    Grid::make([
                        'default' => 1,
                        'md' => 3,
                    ])->schema([
                        Select::make('symbol_id')
                            ->relationship(
                                name: 'symbol',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) =>
                                $query->whereIn('segment', ['INDEX', 'COMMODITY'])
                                    ->where('is_active', 1)
                            )
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
                            ->default(now())
                            ->minDate(now()),
                        TextInput::make('strike_price')
                            ->required()
                            ->numeric()
                            ->reactive()
                            ->debounce(500)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $symbolId = $get('symbol_id');
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
                                $symbolId = $get('symbol_id');
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
                    ]),
                ]),
        ]);
    }
}
