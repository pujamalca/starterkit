<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Support\Enums\Width;

class Login extends BaseLogin
{
    protected Width|string|null $maxWidth = 'full';

    protected string $view = 'filament.admin.pages.auth.login';

    public function mount(): void
    {
        parent::mount();

        // You can customize the form data here if needed
        // $this->form->fill([
        //     'email' => 'admin@example.com',
        //     'password' => 'password',
        //     'remember' => true,
        // ]);
    }
}
