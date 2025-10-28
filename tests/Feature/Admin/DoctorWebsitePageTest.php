<?php

namespace Tests\Feature\Admin;

use App\Filament\Admin\Pages\DoctorWebsite;
use App\Services\Doctor\DoctorService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Mockery;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class DoctorWebsitePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_page_can_be_rendered(): void
    {
        Storage::fake(config('filesystems.default'));

        $user = $this->makeUserWithPermissions(['access-admin-panel']);

        $this->actingAs($user);

        Livewire::test(DoctorWebsite::class)
            ->assertStatus(200)
            ->assertSee('Doctor Website');
    }

    public function test_refresh_action_invokes_service(): void
    {
        Storage::fake(config('filesystems.default'));

        $initialReport = [
            'timestamp' => now(),
            'checks' => [
                'database' => ['status' => 'ok'],
            ],
            'analytics' => [],
            'versions' => [],
        ];

        $refreshedReport = [
            'timestamp' => now()->addMinute(),
            'checks' => [
                'database' => ['status' => 'warning'],
            ],
            'analytics' => [],
            'versions' => [],
        ];

        $mock = Mockery::mock(DoctorService::class);
        $mock->shouldReceive('run')
            ->zeroOrMoreTimes()
            ->andReturn($initialReport, $refreshedReport);

        $this->app->instance(DoctorService::class, $mock);

        $user = $this->makeUserWithPermissions(['access-admin-panel']);
        $this->actingAs($user);

        Livewire::test(DoctorWebsite::class)
            ->call('refreshReport')
            ->assertSet('report.checks.database.status', 'warning');
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

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
