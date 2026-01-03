<?php

namespace App\Filament\Resources\OptionContracts;

use App\Filament\Resources\OptionContracts\Pages\CreateOptionContract;
use App\Filament\Resources\OptionContracts\Pages\EditOptionContract;
use App\Filament\Resources\OptionContracts\Pages\ListOptionContracts;
use App\Filament\Resources\OptionContracts\Pages\ViewOptionContract;
use App\Filament\Resources\OptionContracts\Schemas\OptionContractForm;
use App\Filament\Resources\OptionContracts\Schemas\OptionContractInfolist;
use App\Filament\Resources\OptionContracts\Tables\OptionContractsTable;
use App\Models\OptionContract;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OptionContractResource extends Resource
{
    protected static ?string $model = OptionContract::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string|\UnitEnum|null $navigationGroup = 'Options Trading';
    protected static ?string $navigationLabel = 'Option Contracts';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return OptionContractForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OptionContractInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OptionContractsTable::configure($table);
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
            'index' => ListOptionContracts::route('/'),
            'create' => CreateOptionContract::route('/create'),
            'view' => ViewOptionContract::route('/{record}'),
            'edit' => EditOptionContract::route('/{record}/edit'),
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
