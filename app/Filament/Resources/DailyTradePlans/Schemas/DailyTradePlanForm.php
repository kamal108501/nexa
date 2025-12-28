<?php

namespace App\Filament\Resources\DailyTradePlans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                        Select::make('symbol_id')
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
                                $symbolId = $get('symbol_id');
                                if (!$symbolId) {
                                    return [];
                                }
                                return \App\Models\OptionContract::where('symbol_id', $symbolId)
                                    ->pluck('contract_code', 'id');
                            })
                            ->searchable()
                            ->required()
                            ->reactive(),
                        TextInput::make('current_price')
                            ->required()
                            ->numeric()
                            ->reactive()
                            ->debounce(400)
                            ->afterStateUpdated(fn($state, $set, $get) => static::calculateExpected($set, $get)),
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
