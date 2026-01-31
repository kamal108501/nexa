<?php

namespace App\Filament\Resources\StockTips\Schemas;

use App\Models\TradingSymbol;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;
use Carbon\Carbon;

class StockTipForm
{
    protected static function updateExpiryDate(callable $set, callable $get): void
    {
        $tipDate = $get('tip_date');
        $days = (int) $get('holding_days');

        if ($tipDate && $days > 0) {
            $expiry = Carbon::parse($tipDate)->addDays($days);
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

    protected static function updateStopLoss(callable $set, callable $get): void
    {
        $buy = (float) $get('buy_price');

        if ($buy > 0) {
            $stopLoss = $buy - ($buy * 0.20);
            $set('stop_loss', round($stopLoss, 2));
        } else {
            $set('stop_loss', null);
        }
    }

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

                        // --------------------
                        // Stock (only STOCK segment)
                        // --------------------
                        Select::make('trading_symbol_id')
                            ->relationship(
                                name: 'symbol',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) =>
                                $query->where('segment', 'STOCK')->where('is_active', 1)
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionAction(
                                fn(Action $action) =>
                                $action
                                    ->modalHeading('Add Stock')
                                    ->modalWidth('sm') // 'sm', 'md', 'lg', 'xl', 'full'
                            )
                            // Inline create STOCK
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
                                    'segment'     => 'STOCK', // ✅ forced here
                                    'lot_size'    => 1,
                                    'tick_size'   => 0.05,
                                    'is_active'   => true,
                                ])->id
                            ),

                        DatePicker::make('tip_date')
                            ->required()
                            ->default(now())->reactive()
                            ->afterStateUpdated(
                                fn($state, callable $set, callable $get) =>
                                self::updateExpiryDate($set, $get)
                            ),

                        TextInput::make('holding_days')
                            ->numeric()
                            ->required()
                            ->placeholder('e.g., 30')
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn($state, callable $set, callable $get) =>
                                self::updateExpiryDate($set, $get)
                            ),

                        // --------------------
                        // Prices
                        // --------------------
                        TextInput::make('buy_price')
                            ->numeric()
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::updateStopLoss($set, $get);
                                self::updateExpectedReturn($set, $get);
                            }),

                        TextInput::make('stop_loss')
                            ->label('Stop Loss (-20% from Buy Price)')
                            ->numeric()
                            ->required(),

                        TextInput::make('target_price')
                            ->numeric()
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn($state, callable $set, callable $get) =>
                                self::updateExpectedReturn($set, $get)
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

                        Textarea::make('notes')
                            ->columnSpanFull(),
                    ]),
                ]),
        ]);
    }
}
