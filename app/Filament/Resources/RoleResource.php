<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|\UnitEnum|null $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Role Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->label('Role Name'),
                    ])
                    ->columnSpanFull(),

                ...static::getPermissionSections(),
            ]);
    }

    protected static function getPermissionSections(): array
    {
        $permissions = Permission::all();

        // Group permissions by module
        $groupedPermissions = [];
        foreach ($permissions as $permission) {
            // Extract module name from permission (e.g., 'view_user' -> 'user')
            $parts = explode('_', $permission->name);
            if (count($parts) >= 2) {
                $module = implode('_', array_slice($parts, 1));
                $groupedPermissions[$module][] = $permission;
            }
        }

        // Create a section for each module with unique field names
        $sections = [];
        foreach ($groupedPermissions as $module => $perms) {
            $moduleLabel = Str::title(str_replace('_', ' ', $module));

            // Sort permissions to show in logical order
            $sortedPerms = collect($perms)->sortBy(function ($p) {
                $action = explode('_', $p->name)[0];
                $order = ['view' => 1, 'create' => 2, 'update' => 3, 'delete' => 4];
                return $order[$action] ?? 99;
            });

            $sections[] = Section::make($moduleLabel)
                ->schema([
                    CheckboxList::make("permissions_{$module}")
                        ->hiddenLabel()
                        ->options(
                            $sortedPerms->mapWithKeys(fn($p) => [
                                $p->id => ucfirst(explode('_', $p->name)[0])
                            ])
                        )
                        ->columns(4)
                        ->gridDirection('row')
                        ->bulkToggleable()
                        ->dehydrated(false),
                ])
                ->columnSpan(1)
                ->compact();
        }

        return $sections;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('permissions_count')
                    ->counts('permissions')
                    ->label('Permissions')
                    ->badge()
                    ->color('success'),
                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Users')
                    ->badge()
                    ->color('info'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->can('view_role') || auth()->user()->hasRole('admin');
    }
}
