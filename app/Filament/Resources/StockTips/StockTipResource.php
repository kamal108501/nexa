<?php

namespace App\Filament\Resources\StockTips;

use App\Filament\Resources\StockTips\Pages\CreateStockTip;
use App\Filament\Resources\StockTips\Pages\EditStockTip;
use App\Filament\Resources\StockTips\Pages\ListStockTips;
use App\Filament\Resources\StockTips\Pages\ViewStockTip;
use App\Filament\Resources\StockTips\Schemas\StockTipForm;
use App\Filament\Resources\StockTips\Schemas\StockTipInfolist;
use App\Filament\Resources\StockTips\Tables\StockTipsTable;
use App\Models\StockTip;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockTipResource extends Resource
{
    protected static ?string $model = StockTip::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Trading';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return StockTipForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StockTipInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockTipsTable::configure($table);
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
            'index' => ListStockTips::route('/'),
            'create' => CreateStockTip::route('/create'),
            'view' => ViewStockTip::route('/{record}'),
            'edit' => EditStockTip::route('/{record}/edit'),
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
