<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->manage($user);
    }

    public function view(User $user, User $model): bool
    {
        return $this->manage($user) || $user->is($model);
    }

    public function create(User $user): bool
    {
        return $this->manage($user);
    }

    public function update(User $user, User $model): bool
    {
        if ($user->is($model)) {
            return true;
        }

        return $this->manage($user);
    }

    public function delete(User $user, User $model): bool
    {
        if ($model->hasRole('Super Admin') && ! $user->hasRole('Super Admin')) {
            return false;
        }

        return $this->manage($user);
    }

    public function restore(User $user, User $model): bool
    {
        return $this->delete($user, $model);
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole('Super Admin');
    }

    protected function manage(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('manage-users');
    }
}
