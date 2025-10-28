<?php

namespace Tests\Feature\Admin;

use App\Filament\Admin\Pages\BackupDatabase;
use App\Models\User;
use App\Settings\BackupSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class BackupDatabasePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_authorized_user_can_trigger_database_backup(): void
    {
        $user = $this->makeUserWithPermissions([
            'access-admin-panel',
            'access-settings',
        ]);

        $this->actingAs($user);

        Storage::fake('local');

        $component = Livewire::test(BackupDatabase::class);

        $component
            ->set('data.format', 'json')
            ->set('data.queue', false)
            ->call('triggerBackup');

        $lastOutput = $component->get('lastOutput');

        $this->assertNotNull($lastOutput);
        $this->assertStringContainsString('backups', $lastOutput);

        $this->assertNotEmpty(Storage::disk('local')->files('backups'));
    }

    public function test_backup_settings_can_be_saved(): void
    {
        $user = $this->makeUserWithPermissions([
            'access-admin-panel',
            'access-settings',
        ]);

        $this->actingAs($user);

        Livewire::test(BackupDatabase::class)
            ->set('data.format', 'sql')
            ->set('data.schedule.enabled', true)
            ->set('data.schedule.frequency', 'monthly')
            ->set('data.schedule.time', '03:30')
            ->set('data.schedule.day_of_week', 'friday')
            ->set('data.schedule.day_of_month', 12)
            ->call('saveSettings');

        /** @var BackupSettings $settings */
        $settings = app(BackupSettings::class);

        $this->assertSame('sql', $settings->default_format);
        $this->assertTrue($settings->schedule_enabled);
        $this->assertSame('monthly', $settings->schedule_frequency);
        $this->assertSame('03:30', $settings->schedule_time);
        $this->assertSame('monday', $settings->schedule_day_of_week);
        $this->assertSame(12, $settings->schedule_day_of_month);
    }

    protected function makeUserWithPermissions(array $permissions): User
    {
        $user = User::factory()->create();

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                [
                    'slug' => Str::slug($permission),
                    'module' => 'system',
                ],
            );
        }

        $user->givePermissionTo($permissions);

        return $user;
    }
}
