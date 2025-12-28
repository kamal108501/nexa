<?php

namespace App\Filament\Resources\StockTradeExecutions;

use App\Filament\Resources\StockTradeExecutions\Pages\CreateStockTradeExecution;
use App\Filament\Resources\StockTradeExecutions\Pages\EditStockTradeExecution;
use App\Filament\Resources\StockTradeExecutions\Pages\ListStockTradeExecutions;
use App\Filament\Resources\StockTradeExecutions\Pages\ViewStockTradeExecution;
use App\Filament\Resources\StockTradeExecutions\Schemas\StockTradeExecutionForm;
use App\Filament\Resources\StockTradeExecutions\Schemas\StockTradeExecutionInfolist;
use App\Filament\Resources\StockTradeExecutions\Tables\StockTradeExecutionsTable;
use App\Models\StockTradeExecution;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockTradeExecutionResource extends Resource
{
    protected static ?string $model = StockTradeExecution::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';
    protected static string|\UnitEnum|null $navigationGroup = 'Trading';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return StockTradeExecutionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StockTradeExecutionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockTradeExecutionsTable::configure($table);
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
            'index' => ListStockTradeExecutions::route('/'),
            'create' => CreateStockTradeExecution::route('/create'),
            'view' => ViewStockTradeExecution::route('/{record}'),
            'edit' => EditStockTradeExecution::route('/{record}/edit'),
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
