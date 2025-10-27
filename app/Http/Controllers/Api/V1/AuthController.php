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

class AuthController extends Controller
{
    public function __construct(
        protected readonly UserService $userService,
    ) {
    }

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

    public function profile(Request $request): JsonResponse
    {
        $user = $request->user()?->loadMissing('roles');

        if (! $user) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        return UserResource::make($user)->response();
    }
}
