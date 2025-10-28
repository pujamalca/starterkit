<?php

namespace App\Filament\Admin\Pages;

use App\Settings\GeneralSettings;
use App\Settings\MailSettings;
use App\Settings\SocialSettings;
use App\Services\Settings\SettingsCache;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use BackedEnum;
use UnitEnum;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Pengaturan Aplikasi';

    protected static UnitEnum|string|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 90;

    protected static ?string $title = 'Kelola Pengaturan';

    protected string $view = 'filament.admin.pages.manage-settings';

    /**
     * Form state container.
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getFormDefaults());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('settingsTabs')
                    ->tabs([
                        Tab::make('Umum')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->statePath('general')
                            ->schema([
                                Section::make('Identitas Brand')
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('Nama Situs')
                                            ->required()
                                            ->maxLength(120),
                                        FileUpload::make('site_logo')
                                            ->label('Logo')
                                            ->image()
                                            ->disk('public')
                                            ->directory('branding')
                                            ->imageEditor()
                                            ->imagePreviewHeight('160')
                                            ->preserveFilenames()
                                            ->helperText('Upload logo dengan rasio horizontal (PNG/SVG, maks 2MB).'),
                                        FileUpload::make('site_favicon')
                                            ->label('Favicon')
                                            ->image()
                                            ->disk('public')
                                            ->directory('branding')
                                            ->preserveFilenames()
                                            ->imagePreviewHeight('80')
                                            ->helperText('Gunakan ikon square (PNG/ICO, maks 1MB).')
                                            ->maxSize(1024),
                                    ])->columns(3),
                                Section::make('Informasi Umum')
                                    ->schema([
                                        Textarea::make('site_description')
                                            ->label('Deskripsi Situs')
                                            ->rows(4)
                                            ->maxLength(500)
                                            ->columnSpanFull(),
                                        TagsInput::make('site_keywords')
                                            ->label('Kata Kunci')
                                            ->placeholder('Tambah kata kunci...')
                                            ->separator(',')
                                            ->suggestions([
                                                'laravel',
                                                'filament',
                                                'starter-kit',
                                            ])
                                            ->helperText('Pisahkan dengan enter atau koma.')
                                            ->columnSpanFull(),
                                        Grid::make()
                                            ->schema([
                                                TextInput::make('posts_per_page')
                                                    ->label('Jumlah Post per Halaman')
                                                    ->numeric()
                                                    ->minValue(1)
                                                    ->maxValue(100)
                                                    ->required(),
                                                Toggle::make('maintenance_mode')
                                                    ->label('Mode Pemeliharaan')
                                                    ->helperText('Aktifkan untuk menampilkan halaman maintenance.'),
                                                Toggle::make('comment_moderation')
                                                    ->label('Moderasi Komentar')
                                                    ->helperText('Komentar harus disetujui sebelum tampil.'),
                                            ])
                                            ->columns(3),
                                    ]),
                            ]),
                        Tab::make('Email')
                            ->icon('heroicon-o-envelope')
                            ->statePath('mail')
                            ->schema([
                                Section::make('Alamat Pengirim')
                                    ->schema([
                                        TextInput::make('mail_from_name')
                                            ->label('Nama Pengirim')
                                            ->required()
                                            ->maxLength(120),
                                        TextInput::make('mail_from_address')
                                            ->label('Email Pengirim')
                                            ->email()
                                            ->required()
                                            ->maxLength(190),
                                    ])->columns(2),
                                Section::make('Konfigurasi SMTP')
                                    ->schema([
                                        TextInput::make('mail_driver')
                                            ->label('Driver')
                                            ->required()
                                            ->maxLength(50)
                                            ->datalist([
                                                'smtp',
                                                'log',
                                                'sendmail',
                                                'mailgun',
                                                'ses',
                                                'postmark',
                                            ]),
                                        TextInput::make('smtp_host')
                                            ->label('Host')
                                            ->maxLength(190),
                                        TextInput::make('smtp_port')
                                            ->label('Port')
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(65535),
                                        TextInput::make('smtp_username')
                                            ->label('Username')
                                            ->maxLength(120),
                                        TextInput::make('smtp_password')
                                            ->label('Password')
                                            ->password()
                                            ->dehydrateStateUsing(fn (?string $state) => $state),
                                        TextInput::make('smtp_encryption')
                                            ->label('Enkripsi')
                                            ->maxLength(20)
                                            ->datalist([
                                                'tls',
                                                'ssl',
                                            ]),
                                    ])->columns(2),
                            ]),
                        Tab::make('Sosial')
                            ->icon('heroicon-o-share')
                            ->statePath('social')
                            ->schema([
                                Section::make('Tautan Media Sosial')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('facebook_url')
                                                    ->label('Facebook')
                                                    ->url()
                                                    ->maxLength(255),
                                                TextInput::make('twitter_url')
                                                    ->label('X / Twitter')
                                                    ->url()
                                                    ->maxLength(255),
                                                TextInput::make('instagram_url')
                                                    ->label('Instagram')
                                                    ->url()
                                                    ->maxLength(255),
                                                TextInput::make('linkedin_url')
                                                    ->label('LinkedIn')
                                                    ->url()
                                                    ->maxLength(255),
                                                TextInput::make('youtube_url')
                                                    ->label('YouTube')
                                                    ->url()
                                                    ->maxLength(255),
                                                TextInput::make('github_url')
                                                    ->label('GitHub')
                                                    ->url()
                                                    ->maxLength(255),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormDefaults(): array
    {
        /** @var SettingsCache $settingsCache */
        $settingsCache = app(SettingsCache::class);
        $general = $settingsCache->general();
        /** @var MailSettings $mail */
        $mail = app(MailSettings::class);
        /** @var SocialSettings $social */
        $social = app(SocialSettings::class);

        return [
            'general' => [
                'site_name' => $general->site_name ?? config('app.name', 'Starter Kit'),
                'site_description' => $general->site_description,
                'site_logo' => $this->sanitizeMediaValue($general->site_logo),
                'site_favicon' => $this->sanitizeMediaValue($general->site_favicon),
                'site_keywords' => $general->site_keywords
                    ? array_filter(array_map('trim', explode(',', $general->site_keywords)))
                    : [],
                'maintenance_mode' => $general->maintenance_mode,
                'posts_per_page' => $general->posts_per_page,
                'comment_moderation' => $general->comment_moderation,
            ],
            'mail' => [
                'mail_from_address' => $mail->mail_from_address,
                'mail_from_name' => $mail->mail_from_name,
                'mail_driver' => $mail->mail_driver,
                'smtp_host' => $mail->smtp_host,
                'smtp_port' => $mail->smtp_port,
                'smtp_username' => $mail->smtp_username,
                'smtp_password' => $mail->smtp_password,
                'smtp_encryption' => $mail->smtp_encryption,
            ],
            'social' => [
                'facebook_url' => $social->facebook_url,
                'twitter_url' => $social->twitter_url,
                'instagram_url' => $social->instagram_url,
                'linkedin_url' => $social->linkedin_url,
                'youtube_url' => $social->youtube_url,
                'github_url' => $social->github_url,
            ],
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (isset($data['general']['site_keywords']) && is_array($data['general']['site_keywords'])) {
            $keywords = array_filter(array_map('trim', $data['general']['site_keywords']));
            $data['general']['site_keywords'] = $keywords ? implode(',', $keywords) : null;
        }

        $data['general']['site_logo'] = $this->sanitizeMediaValue($data['general']['site_logo'] ?? null);
        $data['general']['site_favicon'] = $this->sanitizeMediaValue($data['general']['site_favicon'] ?? null);

        $data['general']['posts_per_page'] = (int) ($data['general']['posts_per_page'] ?? 10);
        $data['mail']['smtp_port'] = $data['mail']['smtp_port'] !== null
            ? (int) $data['mail']['smtp_port']
            : null;

        /** @var GeneralSettings $general */
        $general = app(GeneralSettings::class);
        $general->fill($data['general']);
        $general->save();
        app(SettingsCache::class)->flushGeneral();

        /** @var MailSettings $mail */
        $mail = app(MailSettings::class);
        $mail->fill($data['mail']);
        $mail->save();

        /** @var SocialSettings $social */
        $social = app(SocialSettings::class);
        $social->fill($data['social']);
        $social->save();

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->body('Konfigurasi aplikasi telah diperbarui.')
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

        if (! filter_var($value, FILTER_VALIDATE_URL)) {
            return ltrim($value, '/');
        }

        $parsedPath = parse_url($value, PHP_URL_PATH);

        if (! $parsedPath) {
            return null;
        }

        if (str_starts_with($parsedPath, '/storage/')) {
            $parsedPath = substr($parsedPath, strlen('/storage/'));
        }

        return ltrim($parsedPath, '/');
    }
}
