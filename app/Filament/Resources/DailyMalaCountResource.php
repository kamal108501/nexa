<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyMalaCountResource\Pages;
use App\Models\DailyMalaCount;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class DailyMalaCountResource extends Resource
{
    protected static ?string $model = DailyMalaCount::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar';

    protected static string|\UnitEnum|null $navigationGroup = 'Sadhana Tracker';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('mala_count')
                    ->label('Mala Count')
                    ->required()
                    ->numeric()
                    ->default(7),
                DateTimePicker::make('start')
                    ->hidden(),
                DateTimePicker::make('end')
                    ->hidden(),
                Toggle::make('allDay')
                    ->hidden(),
                TextInput::make('name')
                    ->hidden(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                //
            ]);
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
            'index' => Pages\ListDailyMalaCounts::route('/'),
        ];
    }
}
