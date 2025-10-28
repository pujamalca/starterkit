<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckMaintenanceMode;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Http\Request;
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
        ]);

        $middleware->prepend(CheckMaintenanceMode::class);

        // Force JSON response untuk semua API routes
        $middleware->api(prepend: [
            ForceJsonResponse::class,
        ]);
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
