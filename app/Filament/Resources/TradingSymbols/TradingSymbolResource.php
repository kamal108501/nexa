<?php

namespace App\Filament\Resources\TradingSymbols;

use App\Filament\Resources\TradingSymbols\Pages\CreateTradingSymbol;
use App\Filament\Resources\TradingSymbols\Pages\EditTradingSymbol;
use App\Filament\Resources\TradingSymbols\Pages\ListTradingSymbols;
use App\Filament\Resources\TradingSymbols\Pages\ViewTradingSymbol;
use App\Filament\Resources\TradingSymbols\Schemas\TradingSymbolForm;
use App\Filament\Resources\TradingSymbols\Schemas\TradingSymbolInfolist;
use App\Filament\Resources\TradingSymbols\Tables\TradingSymbolsTable;
use App\Models\TradingSymbol;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TradingSymbolResource extends Resource
{
    protected static ?string $model = TradingSymbol::class;

    // Use a relevant Filament Heroicon for trading symbols, e.g. 'heroicon-o-chart-bar'
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static string|\UnitEnum|null $navigationGroup = 'Trading Masters';
    protected static ?string $navigationLabel = 'Trading Symbols';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TradingSymbolForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TradingSymbolInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TradingSymbolsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTradingSymbols::route('/'),
            'create' => CreateTradingSymbol::route('/create'),
            'view' => ViewTradingSymbol::route('/{record}'),
            'edit' => EditTradingSymbol::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canAccess(): bool
    {
        return auth()->user()->can('view_trading_symbol') || auth()->user()->hasRole('admin');
    }
}
