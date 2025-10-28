<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Endpoints untuk autentikasi pengguna."
 * )
 */
class AuthController extends Controller
{
    public function __construct(
        protected readonly UserService $userService,
    ) {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/register",
     *     summary="Registrasi pengguna baru",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="Admin User"),
     *             @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="secret123"),
     *             @OA\Property(property="username", type="string", example="admin"),
     *             @OA\Property(property="phone", type="string", example="+62-812-3456-7890")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registrasi berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/UserResource"),
     *             @OA\Property(
     *                 property="meta",
     *                 @OA\Property(property="token", type="string", example="1|xxxxxxxx"),
     *                 @OA\Property(property="token_type", type="string", example="Bearer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function register(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->register($request->validated());
        $token = $this->userService->createToken($user, 'api-registration');

        return UserResource::make($user->loadMissing('roles'))
            ->additional([
                'meta' => [
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="Login pengguna",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"login","password"},
     *             @OA\Property(property="login", type="string", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *             @OA\Property(property="device_name", type="string", example="postman")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/UserResource"),
     *             @OA\Property(
     *                 property="meta",
     *                 @OA\Property(property="token", type="string", example="1|xxxxxxxx"),
     *                 @OA\Property(property="token_type", type="string", example="Bearer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:120'],
        ]);

        $user = $this->userService->attemptLogin($validated['login'], $validated['password']);

        if (! $user) {
            throw ValidationException::withMessages([
                'login' => [__('auth.failed')],
            ]);
        }

        $token = $this->userService->createToken($user, $validated['device_name'] ?? 'api-login');

        return UserResource::make($user->loadMissing('roles'))
            ->additional([
                'meta' => [
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
            ])
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     summary="Logout pengguna",
     *     tags={"Auth"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Anda telah keluar.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Tidak terautentikasi")
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([], Response::HTTP_NO_CONTENT);
        }

        $token = $user->currentAccessToken();

        if ($token) {
            $token->delete();
        } else {
            $this->userService->revokeTokens($user);
        }

        return response()->json([
            'message' => __('Anda telah keluar.'),
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/auth/profile",
     *     summary="Profil pengguna",
     *     tags={"Auth"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Profil berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Tidak terautentikasi")
     * )
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user()?->loadMissing('roles');

        if (! $user) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        return UserResource::make($user)->response();
    }
}
