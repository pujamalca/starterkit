<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MailSettings extends Settings
{
    public string $mail_from_address;

    public string $mail_from_name;

    public string $mail_driver;

    public ?string $smtp_host;

    public ?int $smtp_port;

    public ?string $smtp_username;

    public ?string $smtp_password;

    public ?string $smtp_encryption;

    public static function group(): string
    {
        return 'mail';
    }
}

