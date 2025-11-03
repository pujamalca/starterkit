<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    protected string $view = 'filament.admin.pages.auth.login';

    public function registerAction(): Action
    {
        return parent::registerAction()->hidden();
    }

    public function getSubheading(): string | Htmlable | null
    {
        return null;
    }
}
