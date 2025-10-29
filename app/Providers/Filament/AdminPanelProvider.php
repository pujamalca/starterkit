<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\ManageSettings;
use App\Services\Settings\SettingsCache;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use function e;

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
            ->brandLogoHeight('2.25rem')
            ->favicon(fn () => $this->resolveFaviconUrl())
            ->renderHook(PanelsRenderHook::TOPBAR_LOGO_AFTER, fn () => $this->renderBrandText())
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->userMenuItems([
                'profile' => Action::make('profile')
                    ->label('Edit Profile')
                    ->url(fn (): string => route('filament.admin.pages.edit-profile'))
                    ->icon('heroicon-o-user-circle'),
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
        $settings = $this->generalSettings();

        return $settings->site_name ?? config('app.name', 'Starter Kit');
    }

    protected function resolveBrandLogo(): ?string
    {
        $settings = $this->generalSettings();

        $logoUrl = $this->toPublicUrl($settings->site_logo);

        if (! $logoUrl) {
            return null;
        }

        return $logoUrl;
    }

    protected function resolveFaviconUrl(): ?string
    {
        $settings = $this->generalSettings();

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

    protected function generalSettings(): \App\Settings\GeneralSettings
    {
        /** @var SettingsCache $cache */
        $cache = app(SettingsCache::class);

        return $cache->general();
    }

    protected function renderBrandText(): string
    {
        if ($this->isAuthRoute()) {
            return '';
        }

        $brandName = $this->resolveBrandName();

        if (blank($brandName)) {
            return '';
        }

        return sprintf(
            '<span class="hidden sm:inline-flex items-center text-sm font-semibold text-gray-700 dark:text-gray-200 ml-2">%s</span>',
            e($brandName),
        );
    }

    protected function isAuthRoute(): bool
    {
        $request = request();

        if (! $request) {
            return false;
        }

        $routeName = $request->route()?->getName();

        if ($routeName && str_contains($routeName, '.auth.')) {
            return true;
        }

        $path = $request->path();

        return str_starts_with($path, 'admin/login');
    }
}
