<?php

namespace Tests\Feature\Admin;

use App\Filament\Admin\Pages\AnalyticsDashboard;
use App\Services\Analytics\AnalyticsService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Mockery;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AnalyticsDashboardPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_page_can_be_rendered(): void
    {
        $service = Mockery::mock(AnalyticsService::class);
        $service->shouldReceive('summary')->zeroOrMoreTimes()->andReturn([]);
        $service->shouldReceive('activityTrends')->zeroOrMoreTimes()->andReturn([]);
        $service->shouldReceive('topPosts')->zeroOrMoreTimes()->andReturn(new EloquentCollection());
        $service->shouldReceive('engagementBreakdown')->zeroOrMoreTimes()->andReturn([]);
        $service->shouldReceive('engagementRates')->zeroOrMoreTimes()->andReturn([]);

        $this->app->instance(AnalyticsService::class, $service);

        $user = $this->makeUserWithPermissions(['access-admin-panel']);
        $this->actingAs($user);

        Livewire::test(AnalyticsDashboard::class)
            ->assertStatus(200)
            ->assertSee('Analytics');
    }

    public function test_refresh_action_invokes_service(): void
    {
        $service = Mockery::mock(AnalyticsService::class);
        $service->shouldReceive('summary')->zeroOrMoreTimes()->andReturn(['total_views' => 10], ['total_views' => 20]);
        $service->shouldReceive('activityTrends')->zeroOrMoreTimes()->andReturn([], []);
        $service->shouldReceive('topPosts')->zeroOrMoreTimes()->andReturn(new EloquentCollection());
        $service->shouldReceive('engagementBreakdown')->zeroOrMoreTimes()->andReturn([]);
        $service->shouldReceive('engagementRates')->zeroOrMoreTimes()->andReturn([]);

        $this->app->instance(AnalyticsService::class, $service);

        $user = $this->makeUserWithPermissions(['access-admin-panel']);
        $this->actingAs($user);

        Livewire::test(AnalyticsDashboard::class)
            ->call('refreshAnalytics')
            ->assertSet('summary.total_views', 20);
    }

    protected function makeUserWithPermissions(array $permissions): User
    {
        $user = User::factory()->create();

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['slug' => Str::slug($permission), 'module' => 'system'],
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
