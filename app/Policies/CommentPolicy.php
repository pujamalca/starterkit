<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->manage($user);
    }

    public function view(User $user, Comment $comment): bool
    {
        return $this->manage($user);
    }

    public function create(User $user): bool
    {
        return $this->manage($user);
    }

    public function update(User $user, Comment $comment): bool
    {
        return $this->manage($user);
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $this->manage($user);
    }

    public function restore(User $user, Comment $comment): bool
    {
        return $this->manage($user);
    }

    public function forceDelete(User $user, Comment $comment): bool
    {
        return $this->manage($user);
    }

    protected function manage(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('manage-comments');
    }
}

