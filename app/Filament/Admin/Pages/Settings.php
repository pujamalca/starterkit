<?php

namespace App\Filament\Admin\Pages;

use Illuminate\Http\RedirectResponse;
use Filament\Pages\Page;

class Settings extends Page
{
    protected string $view = 'filament.admin.pages.settings';

    protected static ?string $title = 'Settings';

    // Hide from navigation since we'll access via user menu
    protected static bool $shouldRegisterNavigation = false;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('access-settings') ?? false;
    }

    public function mount(): ?RedirectResponse
    {
        return static::canAccess()
            ? redirect()->to(ManageSettings::getUrl())
            : null;
    }
}
