<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages\CreateProduct;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Filament\Resources\ProductResource\Pages\ListProducts;
use App\Models\Product;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Product details')->columns(2)->schema([
                Select::make('category_id')->relationship('category', 'name')->searchable()->preload()->required(),
                TextInput::make('name')->label('Name (English)')->required()->maxLength(200),
                TextInput::make('translations.ar.name')
                    ->label('اسم المنتج (عربي)')
                    ->extraInputAttributes(['dir' => 'rtl'])
                    ->maxLength(200),
                TextInput::make('slug')->maxLength(220)->unique(ignoreRecord: true),
                TextInput::make('sku')->required()->maxLength(100)->unique(ignoreRecord: true),
                Textarea::make('description')->label('Description (English)'),
                Textarea::make('translations.ar.description')
                    ->label('وصف المنتج (عربي)')
                    ->extraInputAttributes(['dir' => 'rtl']),
                TextInput::make('price')->required()->numeric()->minValue(0.01)->prefix('EGP'),
                TextInput::make('compare_at_price')->numeric()->minValue(0)->gt('price')->prefix('EGP'),
                TextInput::make('stock_quantity')
                    ->required()
                    ->integer()
                    ->minValue(0)
                    ->default(0)
                    ->helperText('Inventory is managed per variant when variants are added.'),
                Toggle::make('is_active')->default(false),
                Toggle::make('is_featured')->default(false),
                DateTimePicker::make('published_at'),
            ]),
            Section::make('Product variants')->schema([
                Repeater::make('variants')->relationship()->defaultItems(0)->collapsed()->schema([
                    TextInput::make('sku')->required()->maxLength(100)->unique(table: 'product_variants', column: 'sku', ignoreRecord: true),
                    TextInput::make('size')->label('Size (English)')->maxLength(50),
                    TextInput::make('translations.ar.size')
                        ->label('المقاس (عربي)')
                        ->extraInputAttributes(['dir' => 'rtl'])
                        ->maxLength(50),
                    TextInput::make('color')->label('Color (English)')->maxLength(100),
                    TextInput::make('translations.ar.color')
                        ->label('اللون (عربي)')
                        ->extraInputAttributes(['dir' => 'rtl'])
                        ->maxLength(100),
                    TextInput::make('color_code')->maxLength(20),
                    TextInput::make('price')->numeric()->minValue(0)->prefix('EGP'),
                    TextInput::make('stock_quantity')->required()->integer()->minValue(0)->default(0),
                    Toggle::make('is_active')->default(true),
                ])->columns(3),
            ]),
            Section::make('Product images')->schema([
                Repeater::make('images')->relationship()->defaultItems(0)->collapsed()->schema([
                    FileUpload::make('image_path')
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(5120)
                        ->disk('public')
                        ->directory('products')
                        ->required(),
                    TextInput::make('alt_text')->label('Alt text (English)')->maxLength(255),
                    TextInput::make('translations.ar.alt_text')
                        ->label('النص البديل للصورة (عربي)')
                        ->extraInputAttributes(['dir' => 'rtl'])
                        ->maxLength(255),
                    Toggle::make('is_primary')->default(false),
                    TextInput::make('sort_order')->integer()->minValue(0)->default(0),
                ])->columns(2),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('sku')->searchable(),
                TextColumn::make('category.name')->sortable(),
                TextColumn::make('price')->money('EGP')->sortable(),
                TextColumn::make('stock_quantity')->numeric(),
                TextColumn::make('variants_count')->counts('variants')->label('Variants'),
                IconColumn::make('is_active')->boolean(),
                IconColumn::make('is_featured')->boolean(),
            ])
            ->filters([
                SelectFilter::make('category')->relationship('category', 'name'),
                TernaryFilter::make('is_active'),
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
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
