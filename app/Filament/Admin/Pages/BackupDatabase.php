<?php

namespace App\Filament\Admin\Pages;

use App\Settings\BackupSettings;
use BackedEnum;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use UnitEnum;
use Throwable;

class BackupDatabase extends Page implements HasForms
{
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationLabel = 'Backup Basis Data';

    protected static UnitEnum|string|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 95;

    protected static ?string $title = 'Backup Basis Data';

    protected string $view = 'filament.admin.pages.backup-database';

    public ?array $data = [];

    public ?string $lastOutput = null;

    public function mount(BackupSettings $settings): void
    {
        $this->form->fill([
            'format' => $settings->default_format ?? 'json',
            'queue' => false,
            'schedule' => [
                'enabled' => $settings->schedule_enabled,
                'frequency' => $settings->schedule_frequency ?? 'daily',
                'time' => $settings->schedule_time ?? '02:00',
                'day_of_week' => $settings->schedule_day_of_week ?? 'monday',
                'day_of_month' => $settings->schedule_day_of_month ?? 1,
            ],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Backup Manual')
                    ->schema([
                        Placeholder::make('info')
                            ->content(fn () => 'Jalankan backup database secara manual. Disarankan menjalankan via antrean agar proses berat berlangsung di background.')
                            ->extraAttributes([
                                'class' => 'text-sm text-gray-600 dark:text-gray-300',
                            ]),
                        Select::make('format')
                            ->label('Format Backup')
                            ->options([
                                'json' => 'JSON',
                                'csv' => 'CSV (ZIP)',
                                'sql' => 'SQL',
                            ])
                            ->default('json')
                            ->required(),
                        Toggle::make('queue')
                            ->label('Jalankan di antrean')
                            ->default(false)
                            ->helperText('Jika aktif, proses backup berjalan di queue. Pastikan worker queue aktif.'),
                    ])
                    ->columns(2),
                Section::make('Jadwal Otomatis')
                    ->schema([
                        Toggle::make('schedule.enabled')
                            ->label('Aktifkan jadwal otomatis')
                            ->live()
                            ->helperText('Backup akan dijalankan oleh scheduler (php artisan schedule:run).'),
                        Select::make('schedule.frequency')
                            ->label('Frekuensi')
                            ->options([
                                'daily' => 'Harian',
                                'weekly' => 'Mingguan',
                                'monthly' => 'Bulanan',
                                'none' => 'Tidak dijadwalkan',
                            ])
                            ->default('daily')
                            ->live()
                            ->disabled(fn (Get $get): bool => ! $get('schedule.enabled')),
                        TimePicker::make('schedule.time')
                            ->label('Jam (server)')
                            ->seconds(false)
                            ->default('02:00')
                            ->disabled(fn (Get $get): bool => ! $get('schedule.enabled')),
                        Select::make('schedule.day_of_week')
                            ->label('Hari (jadwal mingguan)')
                            ->options([
                                'monday' => 'Senin',
                                'tuesday' => 'Selasa',
                                'wednesday' => 'Rabu',
                                'thursday' => 'Kamis',
                                'friday' => 'Jumat',
                                'saturday' => 'Sabtu',
                                'sunday' => 'Minggu',
                            ])
                            ->default('monday')
                            ->visible(fn (Get $get): bool => $get('schedule.enabled') && $get('schedule.frequency') === 'weekly'),
                        TextInput::make('schedule.day_of_month')
                            ->label('Tanggal (jadwal bulanan)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(28)
                            ->default(1)
                            ->visible(fn (Get $get): bool => $get('schedule.enabled') && $get('schedule.frequency') === 'monthly'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function triggerBackup(): void
    {
        $state = $this->form->getState();
        $this->lastOutput = null;

        $format = strtolower((string) Arr::get($state, 'format', 'json'));
        $parameters = [
            'format' => $format,
        ];

        if (! empty($state['queue'])) {
            $parameters['--queue'] = true;
        }

        try {
            $status = Artisan::call('system:backup', $parameters);
            $output = trim((string) Artisan::output());
            $this->lastOutput = $output !== '' ? $output : null;

            if ($status === 0) {
                Notification::make()
                    ->title($state['queue'] ? 'Backup dijadwalkan.' : 'Backup selesai.')
                    ->body($output ?: 'Perintah backup berhasil dieksekusi.')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Backup gagal dijalankan.')
                    ->body($output ?: 'Periksa konfigurasi backup Anda.')
                    ->danger()
                    ->send();
            }
        } catch (Throwable $throwable) {
            report($throwable);

            $message = $throwable->getMessage();
            $this->lastOutput = $message;

            Notification::make()
                ->title('Terjadi kesalahan saat menjalankan backup.')
                ->body($message)
                ->danger()
                ->send();
        }
    }

    public function saveSettings(): void
    {
        $state = $this->form->getState();

        /** @var BackupSettings $settings */
        $settings = app(BackupSettings::class);

        $settings->default_format = Arr::get($state, 'format', 'json');
        $settings->schedule_enabled = (bool) Arr::get($state, 'schedule.enabled', false);
        $settings->schedule_frequency = Arr::get($state, 'schedule.frequency', 'daily');
        $settings->schedule_time = Arr::get($state, 'schedule.time') ?: '02:00';
        $settings->schedule_day_of_week = Arr::get($state, 'schedule.day_of_week', 'monday');
        $settings->schedule_day_of_month = max(1, min(28, (int) Arr::get($state, 'schedule.day_of_month', 1)));

        if (! $settings->schedule_enabled) {
            $settings->schedule_frequency = 'none';
        }

        $settings->save();

        Notification::make()
            ->title('Pengaturan backup berhasil disimpan.')
            ->success()
            ->send();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('access-settings') ?? false;
    }
}
