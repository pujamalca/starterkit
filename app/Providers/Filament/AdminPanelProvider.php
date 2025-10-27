<?php

namespace App\Providers\Filament;
use App\Filament\Admin\Pages\ManageSettings;
use App\Settings\GeneralSettings;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName(fn () => $this->resolveBrandName())
            ->brandLogo(fn () => $this->resolveBrandLogo())
            ->favicon(fn () => $this->resolveFaviconUrl())
            ->colors([
                'primary' => Color::Amber,
            ])
            ->userMenuItems([
                Action::make('settings')
                    ->visible(fn (): bool => auth()->user()?->can('access-settings') ?? false)
                    ->url(fn (): string => ManageSettings::getUrl())
                    ->icon('heroicon-o-cog-6-tooth'),
            ])
            ->default()
            ->authGuard('web')
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\Filament\Admin\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\Filament\Admin\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\Filament\Admin\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                'permission:access-admin-panel',
            ]);
    }

    protected function resolveBrandName(): string
    {
        $settings = app(GeneralSettings::class);

        return $settings->site_name ?? config('app.name', 'Starter Kit');
    }

    protected function resolveBrandLogo(): HtmlString|string|null
    {
        $settings = app(GeneralSettings::class);

        $logoUrl = $this->toPublicUrl($settings->site_logo);
        $brandName = $this->resolveBrandName();

        if (! $logoUrl) {
            return null;
        }

        $html = sprintf(
            '<span class="inline-flex items-center gap-2 fi-brand-logo"><img src="%s" alt="%s" class="h-8 w-auto"><span class="text-base font-semibold text-gray-900 dark:text-white">%s</span></span>',
            e($logoUrl),
            e($brandName),
            e($brandName),
        );

        return new HtmlString($html);
    }

    protected function resolveFaviconUrl(): ?string
    {
        $settings = app(GeneralSettings::class);

        return $this->toPublicUrl($settings->site_favicon);
    }

    protected function toPublicUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');

        if (! Storage::disk('public')->exists($normalizedPath)) {
            return null;
        }

        return '/storage/' . $normalizedPath;
    }
}
