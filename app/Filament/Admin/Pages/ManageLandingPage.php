<?php

namespace App\Filament\Admin\Pages;

use App\Settings\LandingPageSettings;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;

class ManageLandingPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Landing Page';

    protected static UnitEnum|string|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 91;

    protected static ?string $title = 'Kelola Landing Page';

    protected string $view = 'filament.admin.pages.manage-landing-page';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getFormDefaults());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('landingPageTabs')
                    ->tabs([
                        Tab::make('Navigation')
                            ->icon('heroicon-o-bars-3')
                            ->schema([
                                Section::make('Menu Navigasi')
                                    ->description('Atur menu navigasi yang tampil di header website')
                                    ->schema([
                                        Repeater::make('navigation_menus')
                                            ->label('Daftar Menu')
                                            ->schema([
                                                Grid::make(3)->schema([
                                                    TextInput::make('label')
                                                        ->label('Label Menu')
                                                        ->required()
                                                        ->maxLength(50)
                                                        ->placeholder('Home'),
                                                    TextInput::make('url')
                                                        ->label('URL')
                                                        ->required()
                                                        ->maxLength(255)
                                                        ->placeholder('/ atau /blog')
                                                        ->helperText('Gunakan / untuk home, /blog untuk blog, dll'),
                                                    TextInput::make('order')
                                                        ->label('Urutan')
                                                        ->numeric()
                                                        ->required()
                                                        ->default(1)
                                                        ->minValue(1),
                                                ]),
                                                Grid::make(3)->schema([
                                                    Select::make('type')
                                                        ->label('Tipe Menu')
                                                        ->options([
                                                            'link' => 'Link Biasa',
                                                            'blog_dropdown' => 'Blog dengan Dropdown Kategori',
                                                        ])
                                                        ->default('link')
                                                        ->required()
                                                        ->helperText('Blog dropdown akan menampilkan kategori sebagai sub-menu'),
                                                    Select::make('position')
                                                        ->label('Posisi')
                                                        ->options([
                                                            'left' => 'Kiri',
                                                            'center' => 'Tengah',
                                                            'right' => 'Kanan',
                                                        ])
                                                        ->default('left')
                                                        ->required()
                                                        ->helperText('Pilih posisi menu di header'),
                                                    Toggle::make('show')
                                                        ->label('Tampilkan')
                                                        ->default(true),
                                                ]),
                                            ])
                                            ->defaultItems(2)
                                            ->addActionLabel('Tambah Menu')
                                            ->collapsible()
                                            ->reorderable()
                                            ->orderColumn('order')
                                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                                    ]),
                            ]),

                        Tab::make('Hero Section')
                            ->icon('heroicon-o-star')
                            ->schema([
                                Section::make('Hero Style')
                                    ->description('Pilih tampilan hero section yang sesuai dengan desain website Anda')
                                    ->schema([
                                        Select::make('hero_style')
                                            ->label('Style Hero')
                                            ->options([
                                                'image_right' => 'Image Right - Gambar di sebelah kanan (Default)',
                                                'full_background' => 'Full Background - Background image penuh dengan overlay',
                                                'centered_overlay' => 'Centered Overlay - Konten centered dengan background image',
                                            ])
                                            ->default('image_right')
                                            ->required()
                                            ->helperText('Pilih style tampilan hero section')
                                            ->live(),
                                    ]),
                                Section::make('Hero Content')
                                    ->schema([
                                        TextInput::make('hero_title')
                                            ->label('Judul Utama')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Welcome to Our Platform'),
                                        TextInput::make('hero_subtitle')
                                            ->label('Sub Judul')
                                            ->maxLength(255)
                                            ->placeholder('Build amazing things'),
                                        Textarea::make('hero_description')
                                            ->label('Deskripsi')
                                            ->rows(3)
                                            ->maxLength(500),
                                        FileUpload::make('hero_image')
                                            ->label('Gambar Hero')
                                            ->image()
                                            ->disk('public')
                                            ->directory('landing')
                                            ->imageEditor()
                                            ->imagePreviewHeight('250'),
                                    ])->columns(2),
                                Section::make('Call to Action Buttons')
                                    ->schema([
                                        Repeater::make('hero_buttons')
                                            ->label('Tombol Hero')
                                            ->schema([
                                                TextInput::make('text')
                                                    ->label('Teks Tombol')
                                                    ->required()
                                                    ->maxLength(50),
                                                TextInput::make('url')
                                                    ->label('URL')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->placeholder('/blog atau https://example.com')
                                                    ->helperText('Bisa menggunakan relative URL seperti /blog atau full URL'),
                                                Select::make('style')
                                                    ->label('Style')
                                                    ->options([
                                                        'primary' => 'Primary',
                                                        'secondary' => 'Secondary',
                                                    ])
                                                    ->default('primary')
                                                    ->required(),
                                            ])
                                            ->columns(3)
                                            ->defaultItems(2)
                                            ->addActionLabel('Tambah Tombol')
                                            ->collapsible(),
                                    ]),
                            ]),

                        Tab::make('Features')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Toggle::make('show_features')
                                    ->label('Tampilkan Section Features')
                                    ->default(true)
                                    ->live(),
                                Section::make('Features Content')
                                    ->schema([
                                        TextInput::make('features_title')
                                            ->label('Judul Section')
                                            ->required(fn ($get) => $get('show_features'))
                                            ->maxLength(255),
                                        TextInput::make('features_subtitle')
                                            ->label('Sub Judul')
                                            ->maxLength(255),
                                        Repeater::make('features')
                                            ->label('Daftar Features')
                                            ->schema([
                                                TextInput::make('icon')
                                                    ->label('Icon (Heroicon)')
                                                    ->placeholder('heroicon-o-rocket-launch')
                                                    ->helperText('Contoh: heroicon-o-rocket-launch, heroicon-o-shield-check'),
                                                TextInput::make('title')
                                                    ->label('Judul')
                                                    ->required(),
                                                Textarea::make('description')
                                                    ->label('Deskripsi')
                                                    ->rows(2),
                                            ])
                                            ->columns(3)
                                            ->defaultItems(3)
                                            ->addActionLabel('Tambah Feature')
                                            ->collapsible(),
                                    ])
                                    ->visible(fn ($get) => $get('show_features')),
                            ]),

                        Tab::make('Blog Section')
                            ->icon('heroicon-o-newspaper')
                            ->schema([
                                Toggle::make('show_blog')
                                    ->label('Tampilkan Section Blog')
                                    ->default(true)
                                    ->live(),
                                Section::make('Blog Content')
                                    ->schema([
                                        TextInput::make('blog_title')
                                            ->label('Judul Section')
                                            ->required(fn ($get) => $get('show_blog'))
                                            ->maxLength(255),
                                        TextInput::make('blog_subtitle')
                                            ->label('Sub Judul')
                                            ->maxLength(255),
                                        TextInput::make('blog_posts_count')
                                            ->label('Jumlah Post yang Ditampilkan')
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(12)
                                            ->default(6)
                                            ->required(fn ($get) => $get('show_blog')),
                                    ])->columns(2)
                                    ->visible(fn ($get) => $get('show_blog')),
                            ]),

                        Tab::make('CTA Section')
                            ->icon('heroicon-o-megaphone')
                            ->schema([
                                Toggle::make('show_cta')
                                    ->label('Tampilkan Section CTA')
                                    ->default(true)
                                    ->live(),
                                Section::make('CTA Content')
                                    ->schema([
                                        TextInput::make('cta_title')
                                            ->label('Judul')
                                            ->required(fn ($get) => $get('show_cta'))
                                            ->maxLength(255),
                                        Textarea::make('cta_description')
                                            ->label('Deskripsi')
                                            ->rows(2)
                                            ->maxLength(500),
                                        Grid::make(2)->schema([
                                            TextInput::make('cta_button_text')
                                                ->label('Teks Tombol')
                                                ->required(fn ($get) => $get('show_cta'))
                                                ->maxLength(50),
                                            TextInput::make('cta_button_url')
                                                ->label('URL Tombol')
                                                ->required(fn ($get) => $get('show_cta'))
                                                ->maxLength(255)
                                                ->placeholder('/blog atau https://example.com')
                                                ->helperText('Bisa menggunakan relative URL seperti /blog atau full URL'),
                                        ]),
                                        ColorPicker::make('cta_background_color')
                                            ->label('Warna Background')
                                            ->default('#3b82f6'),
                                    ])->columns(2)
                                    ->visible(fn ($get) => $get('show_cta')),
                            ]),

                        Tab::make('FAQ Section')
                            ->icon('heroicon-o-question-mark-circle')
                            ->schema([
                                Toggle::make('show_faq')
                                    ->label('Tampilkan Section FAQ')
                                    ->default(true)
                                    ->live(),
                                Section::make('FAQ Content')
                                    ->schema([
                                        TextInput::make('faq_title')
                                            ->label('Judul Section')
                                            ->required(fn ($get) => $get('show_faq'))
                                            ->maxLength(255),
                                        TextInput::make('faq_subtitle')
                                            ->label('Sub Judul')
                                            ->maxLength(255),
                                        Repeater::make('faqs')
                                            ->label('Daftar FAQ')
                                            ->schema([
                                                TextInput::make('question')
                                                    ->label('Pertanyaan')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->columnSpanFull(),
                                                Textarea::make('answer')
                                                    ->label('Jawaban')
                                                    ->required()
                                                    ->rows(3)
                                                    ->columnSpanFull(),
                                            ])
                                            ->defaultItems(3)
                                            ->addActionLabel('Tambah FAQ')
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['question'] ?? null),
                                    ])->columns(2)
                                    ->visible(fn ($get) => $get('show_faq')),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormDefaults(): array
    {
        $settings = app(LandingPageSettings::class);

        // Decode features JSON string to array
        $features = [];
        if (!empty($settings->features)) {
            $decoded = json_decode($settings->features, true);
            $features = is_array($decoded) ? $decoded : [];
        }

        // Decode hero_buttons JSON string to array
        $heroButtons = [];
        if (!empty($settings->hero_buttons)) {
            $decoded = json_decode($settings->hero_buttons, true);
            $heroButtons = is_array($decoded) ? $decoded : [];
        }

        // Decode faqs JSON string to array
        $faqs = [];
        if (!empty($settings->faqs)) {
            $decoded = json_decode($settings->faqs, true);
            $faqs = is_array($decoded) ? $decoded : [];
        }

        // Decode navigation_menus JSON string to array
        $navigationMenus = [];
        if (!empty($settings->navigation_menus)) {
            $decoded = json_decode($settings->navigation_menus, true);
            $navigationMenus = is_array($decoded) ? $decoded : [];
        }

        return [
            'hero_style' => $settings->hero_style,
            'hero_title' => $settings->hero_title,
            'hero_subtitle' => $settings->hero_subtitle,
            'hero_description' => $settings->hero_description,
            'hero_image' => $this->sanitizeMediaValue($settings->hero_image),
            'hero_buttons' => $heroButtons,
            'show_features' => $settings->show_features,
            'features_title' => $settings->features_title,
            'features_subtitle' => $settings->features_subtitle,
            'features' => $features,
            'show_blog' => $settings->show_blog,
            'blog_title' => $settings->blog_title,
            'blog_subtitle' => $settings->blog_subtitle,
            'blog_posts_count' => $settings->blog_posts_count,
            'show_cta' => $settings->show_cta,
            'cta_title' => $settings->cta_title,
            'cta_description' => $settings->cta_description,
            'cta_button_text' => $settings->cta_button_text,
            'cta_button_url' => $settings->cta_button_url,
            'cta_background_color' => $settings->cta_background_color,
            'show_faq' => $settings->show_faq,
            'faq_title' => $settings->faq_title,
            'faq_subtitle' => $settings->faq_subtitle,
            'faqs' => $faqs,
            'navigation_menus' => $navigationMenus,
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Sanitize media values
        $data['hero_image'] = $this->sanitizeMediaValue($data['hero_image'] ?? null);

        // Encode features array to JSON string
        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = json_encode($data['features']);
        } else {
            $data['features'] = json_encode([]);
        }

        // Encode hero_buttons array to JSON string
        if (isset($data['hero_buttons']) && is_array($data['hero_buttons'])) {
            $data['hero_buttons'] = json_encode($data['hero_buttons']);
        } else {
            $data['hero_buttons'] = json_encode([]);
        }

        // Encode faqs array to JSON string
        if (isset($data['faqs']) && is_array($data['faqs'])) {
            $data['faqs'] = json_encode($data['faqs']);
        } else {
            $data['faqs'] = json_encode([]);
        }

        // Encode navigation_menus array to JSON string
        if (isset($data['navigation_menus']) && is_array($data['navigation_menus'])) {
            $data['navigation_menus'] = json_encode($data['navigation_menus']);
        } else {
            $data['navigation_menus'] = json_encode([]);
        }

        $settings = app(LandingPageSettings::class);
        $settings->fill($data);
        $settings->save();

        Notification::make()
            ->title('Landing Page berhasil disimpan')
            ->body('Pengaturan landing page telah diperbarui.')
            ->success()
            ->send();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('access-settings') ?? false;
    }

    protected function sanitizeMediaValue(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            return ltrim($value, '/');
        }

        $parsedPath = parse_url($value, PHP_URL_PATH);

        if (!$parsedPath) {
            return null;
        }

        if (str_starts_with($parsedPath, '/storage/')) {
            $parsedPath = substr($parsedPath, strlen('/storage/'));
        }

        return ltrim($parsedPath, '/');
    }
}
