<?php

namespace App\Filament\Admin\Pages;

use App\Services\Doctor\DoctorService;
use BackedEnum;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;
use UnitEnum;

class DoctorWebsite extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationLabel = 'Doctor Website';

    protected static UnitEnum|string|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 80;

    protected static ?string $title = 'Doctor Website';

    protected string $view = 'filament.admin.pages.doctor-website';

    public array $report = [];

    public function mount(): void
    {
        $this->report = app(DoctorService::class)->run();
    }

    public function refreshReport(): void
    {
        $this->report = app(DoctorService::class)->run();

        Notification::make()
            ->title('Health check diperbarui')
            ->body('Laporan Doctor Website berhasil diperbarui.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action('refreshReport')
                ->requiresConfirmation(false)
                ->extraAttributes([
                    'class' => 'rounded-full',
                ]),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('access-admin-panel') ?? false;
    }

    public function statusColor(string $status): string
    {
        return match (Str::lower($status)) {
            'ok' => 'text-emerald-600 bg-emerald-100',
            'warning' => 'text-amber-600 bg-amber-100',
            'error' => 'text-rose-600 bg-rose-100',
            default => 'text-slate-600 bg-slate-100',
        };
    }
}
