<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use App\Filament\Resources\CategoryResource\Pages\ListCategories;
use App\Models\Category;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Category details')->columns(2)->schema([
                TextInput::make('name')->label('Name (English)')->required()->maxLength(150),
                TextInput::make('translations.ar.name')
                    ->label('اسم التصنيف (عربي)')
                    ->extraInputAttributes(['dir' => 'rtl'])
                    ->maxLength(150),
                TextInput::make('slug')->maxLength(180)->unique(ignoreRecord: true),
                Select::make('parent_id')
                    ->options(fn (?Category $record): array => static::parentOptions($record))
                    ->searchable()
                    ->nullable(),
                TextInput::make('sort_order')->integer()->minValue(0)->default(0),
                Textarea::make('description')->label('Description (English)'),
                Textarea::make('translations.ar.description')
                    ->label('وصف التصنيف (عربي)')
                    ->extraInputAttributes(['dir' => 'rtl']),
                FileUpload::make('image_path')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->disk('public')
                    ->directory('categories'),
                Toggle::make('is_active')->default(true),
            ]),
        ]);
    }

    /** @return array<int|string, string> */
    private static function parentOptions(?Category $record): array
    {
        $excludedIds = [];

        if ($record) {
            $visitedIds = [$record->getKey()];
            $pendingIds = [$record->getKey()];

            while ($pendingIds !== []) {
                $childIds = Category::query()
                    ->whereIn('parent_id', $pendingIds)
                    ->pluck('id')
                    ->all();

                $pendingIds = array_values(array_diff($childIds, $visitedIds));
                $visitedIds = [...$visitedIds, ...$pendingIds];
            }

            $excludedIds = $visitedIds;
        }

        return Category::query()
            ->when($excludedIds !== [], fn ($query) => $query->whereNotIn('id', $excludedIds))
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('slug')->searchable(),
                TextColumn::make('parent.name')->label('Parent'),
                TextColumn::make('sort_order')->numeric()->sortable(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->filters([TernaryFilter::make('is_active')])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->disabled(fn (Category $record): bool => $record->products()->exists() || $record->children()->exists()),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
