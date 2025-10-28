<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('backup.default_format', 'json');
        $this->migrator->add('backup.schedule_enabled', false);
        $this->migrator->add('backup.schedule_frequency', 'daily');
        $this->migrator->add('backup.schedule_time', '02:00');
        $this->migrator->add('backup.schedule_day_of_week', 'monday');
        $this->migrator->add('backup.schedule_day_of_month', 1);
    }

    public function down(): void
    {
        $this->migrator->delete('backup.default_format');
        $this->migrator->delete('backup.schedule_enabled');
        $this->migrator->delete('backup.schedule_frequency');
        $this->migrator->delete('backup.schedule_time');
        $this->migrator->delete('backup.schedule_day_of_week');
        $this->migrator->delete('backup.schedule_day_of_month');
    }
};

