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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
}
