<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema as DatabaseSchema;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('User details')->columns(2)->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('email')->email()->required()->maxLength(255)->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->minLength(12),
                Select::make('roles')
                    ->multiple()
                    ->relationship(
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query): Builder => $query
                            ->where('guard_name', 'web')
                            ->where('name', '!=', 'super_admin'),
                    )
                    ->searchable()
                    ->preload()
                    ->helperText('Assign panel_user for panel access. Permission changes to a role apply to every user assigned to it; use a dedicated employee role for individual access levels.')
                    ->visible(fn (): bool => (auth()->user()?->isProjectOwner() ?? false) && static::permissionTablesReady())
                    ->disabled(fn (?User $record): bool => $record?->isProjectOwner() ?? false),
            ]),
            Section::make('Role assignment unavailable')
                ->description('Role assignment is unavailable until all Spatie Permission tables are installed.')
                ->schema([])
                ->visible(fn (): bool => (auth()->user()?->isProjectOwner() ?? false) && ! static::permissionTablesReady()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('role')->badge()->visible(fn (): bool => ! DatabaseSchema::hasTable('roles')),
                TextColumn::make('roles.name')
                    ->badge()
                    ->visible(fn (): bool => DatabaseSchema::hasTable('roles') && DatabaseSchema::hasTable('model_has_roles')),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    private static function permissionTablesReady(): bool
    {
        foreach ([
            config('permission.table_names.permissions', 'permissions'),
            config('permission.table_names.roles', 'roles'),
            config('permission.table_names.model_has_permissions', 'model_has_permissions'),
            config('permission.table_names.model_has_roles', 'model_has_roles'),
            config('permission.table_names.role_has_permissions', 'role_has_permissions'),
        ] as $table) {
            if (! is_string($table) || ! DatabaseSchema::hasTable($table)) {
                return false;
            }
        }

        return true;
    }
}
