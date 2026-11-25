<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages\CreateBanner;
use App\Filament\Resources\BannerResource\Pages\EditBanner;
use App\Filament\Resources\BannerResource\Pages\ListBanners;
use App\Models\Banner;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
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

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Banner content')->columns(2)->schema([
                TextInput::make('title')->label('Title (English)')->maxLength(200),
                TextInput::make('translations.ar.title')
                    ->label('العنوان (عربي)')
                    ->extraInputAttributes(['dir' => 'rtl'])
                    ->maxLength(200),
                TextInput::make('placement')
                    ->required()
                    ->maxLength(60)
                    ->default('homepage_hero')
                    ->helperText('Use homepage_hero for the top banner or homepage_promotion for the lower banner.'),
                Textarea::make('subtitle')->label('Description (English)'),
                Textarea::make('translations.ar.subtitle')
                    ->label('الوصف (عربي)')
                    ->extraInputAttributes(['dir' => 'rtl']),
                FileUpload::make('image_path')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->disk('public')
                    ->directory('banners')
                    ->required(),
                FileUpload::make('mobile_image_path')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->disk('public')
                    ->directory('banners'),
                TextInput::make('alt_text')->label('Image alt text (English)')->maxLength(255),
                TextInput::make('translations.ar.alt_text')
                    ->label('النص البديل للصورة (عربي)')
                    ->extraInputAttributes(['dir' => 'rtl'])
                    ->maxLength(255),
                TextInput::make('button_text')->label('Button text (English)')->maxLength(100),
                TextInput::make('translations.ar.button_text')
                    ->label('نص الزر (عربي)')
                    ->extraInputAttributes(['dir' => 'rtl'])
                    ->maxLength(100),
                TextInput::make('button_url')->url()->maxLength(2048),
                Toggle::make('is_active')->default(false),
                TextInput::make('sort_order')->integer()->minValue(0)->default(0),
                DateTimePicker::make('starts_at'),
                DateTimePicker::make('ends_at'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('placement')->badge(),
                TextColumn::make('sort_order')->numeric()->sortable(),
                TextColumn::make('starts_at')->dateTime(),
                TextColumn::make('ends_at')->dateTime(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->filters([TernaryFilter::make('is_active')])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBanners::route('/'),
            'create' => CreateBanner::route('/create'),
            'edit' => EditBanner::route('/{record}/edit'),
        ];
    }
}
