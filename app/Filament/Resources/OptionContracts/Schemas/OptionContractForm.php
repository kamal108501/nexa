<?php

namespace App\Filament\Resources\OptionContracts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Carbon\Carbon;

class OptionContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('symbol')
                    ->options([
                        'NIFTY' => 'NIFTY',
                        'SENSEX' => 'SENSEX',
                        'BANKNIFTY' => 'BANKNIFTY',
                        'RELIANCE' => 'RELIANCE',
                        'TCS' => 'TCS',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $today = Carbon::today();

                        match ($state) {
                            'NIFTY' => $set('expiry_date', $today->next(Carbon::TUESDAY)),
                            'SENSEX' => $set('expiry_date', $today->next(Carbon::THURSDAY)),
                            'BANKNIFTY' => $set(
                                'expiry_date',
                                $today->copy()->endOfMonth()->previous(Carbon::THURSDAY)
                            ),
                            default => $set('expiry_date', $today),
                        };
                    }),
                DatePicker::make('expiry_date')
                    ->required()
                    ->default(now()),
                TextInput::make('strike_price')
                    ->required()
                    ->numeric(),
                // ->prefix('Rs'),
                Select::make('option_type')
                    ->options(['CE' => 'CE', 'PE' => 'PE'])
                    ->required(),
                TextInput::make('lot_size')
                    ->required()
                    ->numeric(),
                // TextInput::make('tick_size')
                //     ->numeric()
                //     ->default(null),
                TextInput::make('contract_code')
                    ->default(null),
                Toggle::make('is_weekly')
                    // ->required()
                    ->default(true),
                // Toggle::make('is_active')
                //     ->required(),
                // TextInput::make('created_by')
                //     ->numeric()
                //     ->default(null),
                // TextInput::make('updated_by')
                //     ->numeric()
                //     ->default(null),
                // TextInput::make('deleted_by')
                //     ->numeric()
                //     ->default(null),
            ]);
    }
}
