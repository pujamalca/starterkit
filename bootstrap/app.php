<?php

use App\Http\Middleware\CheckMaintenanceMode;
use App\Http\Middleware\CheckUserActive;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\LogUserActivity;
use App\Http\Middleware\SetLocale;
use App\Services\DatabaseBackupService;
use App\Settings\BackupSettings;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'active' => CheckUserActive::class,
            'log.user.activity' => LogUserActivity::class,
            'set.locale' => SetLocale::class,
        ]);

        $middleware->prepend(CheckMaintenanceMode::class);

        // API middleware stack adjustments
        $middleware->api(
            append: [
                LogUserActivity::class,
            ],
            prepend: [
                SetLocale::class,
                ForceJsonResponse::class,
            ],
        );
    })
    ->withSchedule(function (Schedule $schedule): void {
        /** @var BackupSettings $settings */
        $settings = app(BackupSettings::class);

        if (! $settings->schedule_enabled || $settings->schedule_frequency === 'none') {
            return;
        }

        $format = strtolower($settings->default_format ?? 'json');
        if (! in_array($format, DatabaseBackupService::FORMATS, true)) {
            $format = 'json';
        }

        $event = $schedule->command('system:backup', [
            'format' => $format,
            '--queue' => true,
        ])->withoutOverlapping()->onOneServer()->description('Scheduled database backup');

        $time = $settings->schedule_time ?: '02:00';

        switch ($settings->schedule_frequency) {
            case 'weekly':
                $dayMap = [
                    'monday' => Carbon::MONDAY,
                    'tuesday' => Carbon::TUESDAY,
                    'wednesday' => Carbon::WEDNESDAY,
                    'thursday' => Carbon::THURSDAY,
                    'friday' => Carbon::FRIDAY,
                    'saturday' => Carbon::SATURDAY,
                    'sunday' => Carbon::SUNDAY,
                ];
                $weekday = $dayMap[strtolower((string) $settings->schedule_day_of_week)] ?? Carbon::MONDAY;
                $event->weeklyOn($weekday, $time);
                break;
            case 'monthly':
                $dayOfMonth = max(1, min(28, (int) $settings->schedule_day_of_month));
                $event->monthlyOn($dayOfMonth, $time);
                break;
            case 'daily':
            default:
                $event->dailyAt($time);
                break;
        }
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle exception untuk API routes dengan JSON response
        $exceptions->render(function (\Throwable $e, Request $request) {
            // Jika request ke API routes, selalu return JSON
            if ($request->is('api/*')) {
                // Handle ValidationException
                if ($e instanceof ValidationException) {
                    return response()->json([
                        'message' => $e->getMessage() ?: 'Data yang diberikan tidak valid.',
                        'errors' => $e->errors(),
                    ], 422);
                }

                // Handle NotFoundHttpException (404)
                if ($e instanceof NotFoundHttpException) {
                    return response()->json([
                        'message' => 'Resource tidak ditemukan.',
                    ], 404);
                }

                // Handle HttpException (semua HTTP exceptions seperti 401, 403, dll)
                if ($e instanceof HttpException) {
                    $statusCode = $e->getStatusCode();
                    $defaultMessages = [
                        401 => 'Tidak terautentikasi.',
                        403 => 'Akses ditolak.',
                        404 => 'Resource tidak ditemukan.',
                        429 => 'Terlalu banyak permintaan.',
                        500 => 'Terjadi kesalahan pada server.',
                        503 => 'Layanan tidak tersedia.',
                    ];

                    $message = $e->getMessage() ?: ($defaultMessages[$statusCode] ?? 'Terjadi kesalahan.');

                    return response()->json([
                        'message' => $message,
                    ], $statusCode);
                }

                // Handle exception lainnya
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                $message = config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat memproses permintaan Anda.';

                return response()->json([
                    'message' => $message,
                ], $statusCode >= 100 && $statusCode < 600 ? $statusCode : 500);
            }
        });
    })->create();
