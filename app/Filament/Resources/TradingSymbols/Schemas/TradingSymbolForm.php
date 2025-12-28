<?php

namespace App\Filament\Resources\TradingSymbols\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TradingSymbolForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Trading Symbol Details')
                ->columnSpanFull()
                ->schema([
                    Grid::make([
                        'default' => 1,
                        'md' => 3,
                    ])->schema([
                        TextInput::make('symbol_code')->label('Symbol Code')->required(),
                        TextInput::make('name')->label('Name')->required(),
                        Select::make('exchange')
                            ->label('Exchange')
                            ->options([
                                'NSE' => 'NSE',
                                'BSE' => 'BSE',
                                'MCX' => 'MCX',
                                'NCDEX' => 'NCDEX',
                            ])
                            ->required(),
                        Select::make('segment')
                            ->label('Segment')
                            ->options([
                                'INDEX' => 'INDEX',
                                'EQUITY' => 'STOCK',
                                'COMMODITY' => 'COMMODITY',
                                'CURRENCY' => 'CURRENCY',
                            ])
                            ->required(),
                        TextInput::make('lot_size')->label('Lot Size')->required()->numeric()->default(null),
                        TextInput::make('tick_size')->label('Tick Size')->numeric()->default(null),
                        Toggle::make('is_active')->label('Active')->default(true),
                    ]),
                ]),
        ]);
    }
}
