<?php

namespace App\Filament\Admin\Pages;

use App\Services\Analytics\AnalyticsService;
use BackedEnum;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class AnalyticsDashboard extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Analytics';

    protected static UnitEnum|string|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 70;

    protected static ?string $title = 'Analytics Overview';

    protected string $view = 'filament.admin.pages.analytics-dashboard';

    public array $summary = [];

    public array $trends = [];

    public array $topPosts = [];

    public array $engagement = [];

    public array $rates = [];

    public function mount(): void
    {
        $this->loadAnalytics();
    }

    public function refreshAnalytics(): void
    {
        $this->loadAnalytics();

        Notification::make()
            ->title('Analytics diperbarui')
            ->body('Data analitik terbaru telah dimuat.')
            ->success()
            ->send();
    }

    protected function loadAnalytics(): void
    {
        /** @var AnalyticsService $service */
        $service = app(AnalyticsService::class);

        $this->summary = $service->summary();
        $this->trends = $service->activityTrends();
        $this->topPosts = $service->topPosts()->toArray();
        $this->engagement = $service->engagementBreakdown();
        $this->rates = $service->engagementRates();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action('refreshAnalytics')
                ->requiresConfirmation(false),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('access-admin-panel') ?? false;
    }
}

