<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StorefrontPageResource\Pages\CreateStorefrontPage;
use App\Filament\Resources\StorefrontPageResource\Pages\EditStorefrontPage;
use App\Filament\Resources\StorefrontPageResource\Pages\ListStorefrontPages;
use App\Models\StorefrontPage;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
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
use Filament\Tables\Table;

class StorefrontPageResource extends Resource
{
    protected static ?string $model = StorefrontPage::class;

    protected static string|\UnitEnum|null $navigationGroup = 'AK2 Content';

    protected static ?string $navigationLabel = 'Store Pages';

    protected static ?int $navigationSort = 60;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Page identity and search preview')->columns(2)->schema([
                TextInput::make('slug')
                    ->rules(['regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'])
                    ->maxLength(64)
                    ->required()
                    ->helperText('Public URL: /pages/{slug}. Use lowercase letters, numbers, and hyphens.')
                    ->unique(ignoreRecord: true)
                    ->disabledOn('edit'),
                TextInput::make('title')->label('Title (English)')->required()->maxLength(200),
                TextInput::make('translations.ar.title')
                    ->label('العنوان (عربي)')
                    ->extraInputAttributes(['dir' => 'rtl'])
                    ->maxLength(200),
                TextInput::make('meta_title')->label('SEO title (English)')->maxLength(200),
                TextInput::make('translations.ar.meta_title')
                    ->label('عنوان SEO (عربي)')
                    ->extraInputAttributes(['dir' => 'rtl'])
                    ->maxLength(200),
                Textarea::make('meta_description')->label('SEO description (English)')->rows(2)->maxLength(320),
                Textarea::make('translations.ar.meta_description')
                    ->label('وصف SEO (عربي)')
                    ->extraInputAttributes(['dir' => 'rtl'])
                    ->rows(2)
                    ->maxLength(320),
                Toggle::make('is_published')->default(false),
            ]),
            Section::make('Main page content')->columns(2)->schema([
                TextInput::make('eyebrow')->label('Eyebrow (English)')->maxLength(120),
                TextInput::make('translations.ar.eyebrow')
                    ->label('النص التمهيدي (عربي)')
                    ->extraInputAttributes(['dir' => 'rtl'])
                    ->maxLength(120),
                TextInput::make('heading')->label('Heading (English)')->maxLength(200),
                TextInput::make('translations.ar.heading')
                    ->label('العنوان الرئيسي (عربي)')
                    ->extraInputAttributes(['dir' => 'rtl'])
                    ->maxLength(200),
                Textarea::make('subheading')->label('Subheading (English)')->rows(3),
                Textarea::make('translations.ar.subheading')
                    ->label('العنوان الفرعي (عربي)')
                    ->extraInputAttributes(['dir' => 'rtl'])
                    ->rows(3),
                Textarea::make('body')->label('Body (English)')->rows(6),
                Textarea::make('translations.ar.body')
                    ->label('المحتوى (عربي)')
                    ->extraInputAttributes(['dir' => 'rtl'])
                    ->rows(6),
                FileUpload::make('hero_image_path')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->disk('public')
                    ->directory('storefront-pages'),
                TextInput::make('primary_cta_text')->label('Button text (English)')->maxLength(100),
                TextInput::make('translations.ar.primary_cta_text')
                    ->label('نص الزر (عربي)')
                    ->extraInputAttributes(['dir' => 'rtl'])
                    ->maxLength(100),
                TextInput::make('primary_cta_url')->rules(self::safeUrlRules())->maxLength(2048),
            ]),
            Section::make('Page sections')->schema([
                Repeater::make('sections')
                    ->schema([
                        Select::make('key')
                            ->options([
                                'hero' => 'Hero copy',
                                'categories' => 'Categories heading',
                                'featured_products' => 'Featured products heading',
                                'new_arrivals' => 'New arrivals heading',
                                'promotion' => 'Lower homepage banner - بانر أسفل المنتجات: ارتقِ بأناقتك اليومية',
                                'newsletter' => 'Newsletter copy',
                                'content' => 'Content section',
                                'navigation' => 'Header navigation',
                                'announcement' => 'Announcement bar',
                                'footer' => 'Footer content',
                            ])
                            ->required()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                        TextInput::make('eyebrow')->label('Eyebrow (English)')->maxLength(120),
                        TextInput::make('translations.ar.eyebrow')
                            ->label('النص التمهيدي (عربي)')
                            ->extraInputAttributes(['dir' => 'rtl'])
                            ->maxLength(120),
                        TextInput::make('heading')->label('Heading (English)')->maxLength(200),
                        TextInput::make('translations.ar.heading')
                            ->label('العنوان الرئيسي (عربي)')
                            ->extraInputAttributes(['dir' => 'rtl'])
                            ->maxLength(200),
                        Textarea::make('body')->label('Body (English)')->rows(4),
                        Textarea::make('translations.ar.body')
                            ->label('المحتوى (عربي)')
                            ->extraInputAttributes(['dir' => 'rtl'])
                            ->rows(4),
                        FileUpload::make('image_path')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('storefront-pages'),
                        TextInput::make('cta_text')->label('Button text (English)')->maxLength(100),
                        TextInput::make('translations.ar.cta_text')
                            ->label('نص الزر (عربي)')
                            ->extraInputAttributes(['dir' => 'rtl'])
                            ->maxLength(100),
                        TextInput::make('cta_url')->rules(self::safeUrlRules())->maxLength(2048),
                        TextInput::make('input_placeholder')->label('Placeholder (English)')->maxLength(160),
                        TextInput::make('translations.ar.input_placeholder')
                            ->label('النص الإرشادي (عربي)')
                            ->extraInputAttributes(['dir' => 'rtl'])
                            ->maxLength(160),
                        Textarea::make('fine_print')->label('Fine print (English)')->rows(2)->maxLength(500),
                        Textarea::make('translations.ar.fine_print')
                            ->label('النص التوضيحي (عربي)')
                            ->extraInputAttributes(['dir' => 'rtl'])
                            ->rows(2)
                            ->maxLength(500),
                        TextInput::make('secondary_text')->label('Secondary text (English)')->maxLength(200),
                        TextInput::make('translations.ar.secondary_text')
                            ->label('النص الثانوي (عربي)')
                            ->extraInputAttributes(['dir' => 'rtl'])
                            ->maxLength(200),
                        Repeater::make('links')
                            ->schema([
                                Select::make('group')->options([
                                    'navigation' => 'Header navigation',
                                    'footer_explore' => 'Footer: Explore',
                                    'footer_service' => 'Footer: Customer service',
                                    'footer_social' => 'Footer: Social links',
                                    'footer_contact' => 'Footer: Contact links',
                                ])->required(),
                                TextInput::make('label')->label('Label (English)')->required()->maxLength(100),
                                TextInput::make('translations.ar.label')
                                    ->label('تسمية الرابط (عربي)')
                                    ->extraInputAttributes(['dir' => 'rtl'])
                                    ->maxLength(100),
                                TextInput::make('url')->rules(self::safeUrlRules())->required()->maxLength(2048),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->collapsible(),
                        Toggle::make('is_active')->default(true),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->defaultItems(0),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('slug')->badge(),
                IconColumn::make('is_published')->boolean(),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
            ])
            ->defaultSort('slug');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStorefrontPages::route('/'),
            'create' => CreateStorefrontPage::route('/create'),
            'edit' => EditStorefrontPage::route('/{record}/edit'),
        ];
    }

    /** @return array<int, string> */
    private static function safeUrlRules(): array
    {
        return ['nullable', 'string', 'max:2048', 'regex:/^(https?:\/\/|mailto:|tel:|\/(?!\/))/i'];
    }
}
