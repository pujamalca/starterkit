<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function register(array $data): User
    {
        /** @var User $user */
        $user = User::query()->create([
            'name' => $data['name'] ?? null,
            'username' => $data['username'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'is_active' => $data['is_active'] ?? true,
        ]);

        event(new Registered($user));

        return $user;
    }

    public function attemptLogin(string $login, string $password): ?User
    {
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);

        $user = User::query()
            ->when(
                $isEmail,
                fn ($query) => $query->where('email', $login),
                fn ($query) => $query->where(function ($builder) use ($login) {
                    $builder
                        ->where('email', $login)
                        ->orWhere('username', $login);
                })
            )
            ->first();

        if (! $user instanceof User) {
            return null;
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'email' => __('Akun Anda belum aktif.'),
            ]);
        }

        if (! Hash::check($password, $user->password)) {
            return null;
        }

        return $user;
    }

    public function createToken(User $user, ?string $tokenName = null): string
    {
        $name = $tokenName ?: sprintf('api-token-%s', now()->timestamp);

        return $user->createToken($name)->plainTextToken;
    }

    public function revokeTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    public function update(User $user, array $data): User
    {
        if (isset($data['password']) && blank($data['password'])) {
            unset($data['password']);
        }

        $user->fill($data);
        $user->save();

        return $user;
    }
}
