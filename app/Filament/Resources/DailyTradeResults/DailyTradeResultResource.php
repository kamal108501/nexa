<?php

namespace App\Filament\Resources\DailyTradeResults;

use App\Filament\Resources\DailyTradeResults\Pages\CreateDailyTradeResult;
use App\Filament\Resources\DailyTradeResults\Pages\EditDailyTradeResult;
use App\Filament\Resources\DailyTradeResults\Pages\ListDailyTradeResults;
use App\Filament\Resources\DailyTradeResults\Pages\ViewDailyTradeResult;
use App\Filament\Resources\DailyTradeResults\Schemas\DailyTradeResultForm;
use App\Filament\Resources\DailyTradeResults\Schemas\DailyTradeResultInfolist;
use App\Filament\Resources\DailyTradeResults\Tables\DailyTradeResultsTable;
use App\Models\DailyTradeResult;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DailyTradeResultResource extends Resource
{
    protected static ?string $model = DailyTradeResult::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static string|\UnitEnum|null $navigationGroup = 'Options Trading';
    protected static ?string $navigationLabel = 'Daily Trade Results';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return DailyTradeResultForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DailyTradeResultInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DailyTradeResultsTable::configure($table);
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
            'index' => ListDailyTradeResults::route('/'),
            'create' => CreateDailyTradeResult::route('/create'),
            'view' => ViewDailyTradeResult::route('/{record}'),
            'edit' => EditDailyTradeResult::route('/{record}/edit'),
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
