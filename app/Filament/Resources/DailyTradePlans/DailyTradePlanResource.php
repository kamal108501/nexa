<?php

namespace App\Filament\Resources\DailyTradePlans;

use App\Filament\Resources\DailyTradePlans\Pages\CreateDailyTradePlan;
use App\Filament\Resources\DailyTradePlans\Pages\EditDailyTradePlan;
use App\Filament\Resources\DailyTradePlans\Pages\ListDailyTradePlans;
use App\Filament\Resources\DailyTradePlans\Pages\ViewDailyTradePlan;
use App\Filament\Resources\DailyTradePlans\Schemas\DailyTradePlanForm;
use App\Filament\Resources\DailyTradePlans\Schemas\DailyTradePlanInfolist;
use App\Filament\Resources\DailyTradePlans\Tables\DailyTradePlansTable;
use App\Models\DailyTradePlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DailyTradePlanResource extends Resource
{
    protected static ?string $model = DailyTradePlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\UnitEnum|null $navigationGroup = 'Trading';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return DailyTradePlanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DailyTradePlanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DailyTradePlansTable::configure($table);
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
            'index' => ListDailyTradePlans::route('/'),
            'create' => CreateDailyTradePlan::route('/create'),
            'view' => ViewDailyTradePlan::route('/{record}'),
            'edit' => EditDailyTradePlan::route('/{record}/edit'),
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
