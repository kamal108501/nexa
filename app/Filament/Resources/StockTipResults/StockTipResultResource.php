<?php

namespace App\Filament\Resources\StockTipResults;

use App\Filament\Resources\StockTipResults\Pages\CreateStockTipResult;
use App\Filament\Resources\StockTipResults\Pages\EditStockTipResult;
use App\Filament\Resources\StockTipResults\Pages\ListStockTipResults;
use App\Filament\Resources\StockTipResults\Pages\ViewStockTipResult;
use App\Filament\Resources\StockTipResults\Schemas\StockTipResultForm;
use App\Filament\Resources\StockTipResults\Schemas\StockTipResultInfolist;
use App\Filament\Resources\StockTipResults\Tables\StockTipResultsTable;
use App\Models\StockTipResult;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockTipResultResource extends Resource
{
    protected static ?string $model = StockTipResult::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\UnitEnum|null $navigationGroup = 'Trading';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return StockTipResultForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StockTipResultInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockTipResultsTable::configure($table);
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
            'index' => ListStockTipResults::route('/'),
            'create' => CreateStockTipResult::route('/create'),
            'view' => ViewStockTipResult::route('/{record}'),
            'edit' => EditStockTipResult::route('/{record}/edit'),
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
