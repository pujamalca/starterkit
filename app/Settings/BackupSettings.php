<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class BackupSettings extends Settings
{
    public string $default_format = 'json';

    public bool $schedule_enabled = false;

    public string $schedule_frequency = 'daily';

    public ?string $schedule_time = '02:00';

    public ?string $schedule_day_of_week = 'monday';

    public int $schedule_day_of_month = 1;

    public static function group(): string
    {
        return 'backup';
    }
}

