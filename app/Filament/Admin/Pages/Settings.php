<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class Settings extends Page
{
    protected string $view = 'filament.admin.pages.settings';

    protected static ?string $title = 'Settings';

    // Hide from navigation since we'll access via user menu
    protected static bool $shouldRegisterNavigation = false;
}
