<?php

namespace App\Filament\Resources\TradingMonthlyRiskPlans;

use App\Filament\Resources\TradingMonthlyRiskPlans\Pages\CreateTradingMonthlyRiskPlan;
use App\Filament\Resources\TradingMonthlyRiskPlans\Pages\EditTradingMonthlyRiskPlan;
use App\Filament\Resources\TradingMonthlyRiskPlans\Pages\ListTradingMonthlyRiskPlans;
use App\Filament\Resources\TradingMonthlyRiskPlans\Pages\ViewTradingMonthlyRiskPlan;
use App\Filament\Resources\TradingMonthlyRiskPlans\Schemas\TradingMonthlyRiskPlanForm;
use App\Filament\Resources\TradingMonthlyRiskPlans\Schemas\TradingMonthlyRiskPlanInfolist;
use App\Filament\Resources\TradingMonthlyRiskPlans\Tables\TradingMonthlyRiskPlansTable;
use App\Models\TradingMonthlyRiskPlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TradingMonthlyRiskPlanResource extends Resource
{
    protected static ?string $model = TradingMonthlyRiskPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'admin';

    public static function form(Schema $schema): Schema
    {
        return TradingMonthlyRiskPlanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TradingMonthlyRiskPlanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TradingMonthlyRiskPlansTable::configure($table);
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
            'index' => ListTradingMonthlyRiskPlans::route('/'),
            'create' => CreateTradingMonthlyRiskPlan::route('/create'),
            'view' => ViewTradingMonthlyRiskPlan::route('/{record}'),
            'edit' => EditTradingMonthlyRiskPlan::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id());
    }
}
