<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mail.mail_from_address', config('mail.from.address', 'hello@example.com'));
        $this->migrator->add('mail.mail_from_name', config('mail.from.name', config('app.name', 'Starter Kit')));
        $this->migrator->add('mail.mail_driver', config('mail.default', 'smtp'));
        $this->migrator->add('mail.smtp_host', config('mail.mailers.smtp.host'));
        $this->migrator->add('mail.smtp_port', config('mail.mailers.smtp.port'));
        $this->migrator->add('mail.smtp_username', config('mail.mailers.smtp.username'));
        $this->migrator->add('mail.smtp_password', config('mail.mailers.smtp.password'));
        $this->migrator->add('mail.smtp_encryption', config('mail.mailers.smtp.encryption'));
    }
};
