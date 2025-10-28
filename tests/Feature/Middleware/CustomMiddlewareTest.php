<?php

namespace Tests\Feature\Middleware;

use App\Services\Settings\SettingsCache;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CustomMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_force_json_response_overrides_accept_header(): void
    {
        Route::middleware('api')->get('/testing/middleware/accept', function (Request $request) {
            return response()->json([
                'accept' => $request->header('Accept'),
            ]);
        });

        $response = $this->get('/testing/middleware/accept', [
            'Accept' => 'text/html',
        ]);

        $response->assertOk()
            ->assertJson([
                'accept' => 'application/json',
            ]);
    }

    public function test_set_locale_respects_request_locale(): void
    {
        config(['app.supported_locales' => ['en', 'id']]);

        Route::middleware('api')->get('/testing/middleware/locale', function () {
            return response()->json([
                'locale' => app()->getLocale(),
            ]);
        });

        $response = $this->get('/testing/middleware/locale?locale=id', [
            'Accept-Language' => 'id-ID',
        ]);

        $response->assertOk()
            ->assertJson([
                'locale' => 'id',
            ]);
    }

    public function test_check_maintenance_mode_blocks_public_access(): void
    {
        Route::middleware('web')->get('/testing/middleware/public', fn () => 'OK');

        /** @var GeneralSettings $settings */
        $settings = app(GeneralSettings::class);
        $settings->site_name = 'Starter Kit';
        $settings->site_description = null;
        $settings->site_logo = null;
        $settings->site_favicon = null;
        $settings->site_keywords = null;
        $settings->maintenance_mode = true;
        $settings->posts_per_page = 10;
        $settings->comment_moderation = false;
        $settings->save();

        app(SettingsCache::class)->flushGeneral();

        $response = $this->get('/testing/middleware/public');

        $response->assertStatus(503);
    }
}
