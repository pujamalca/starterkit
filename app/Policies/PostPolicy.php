<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->manage($user);
    }

    public function view(User $user, Post $post): bool
    {
        return $this->manage($user);
    }

    public function create(User $user): bool
    {
        return $this->manage($user);
    }

    public function update(User $user, Post $post): bool
    {
        return $this->manage($user);
    }

    public function delete(User $user, Post $post): bool
    {
        return $this->manage($user);
    }

    public function restore(User $user, Post $post): bool
    {
        return $this->manage($user);
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return $this->manage($user);
    }

    protected function manage(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('manage-posts');
    }
}

