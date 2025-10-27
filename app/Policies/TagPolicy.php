<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->manage($user);
    }

    public function view(User $user, Tag $tag): bool
    {
        return $this->manage($user);
    }

    public function create(User $user): bool
    {
        return $this->manage($user);
    }

    public function update(User $user, Tag $tag): bool
    {
        return $this->manage($user);
    }

    public function delete(User $user, Tag $tag): bool
    {
        return $this->manage($user);
    }

    public function restore(User $user, Tag $tag): bool
    {
        return $this->manage($user);
    }

    public function forceDelete(User $user, Tag $tag): bool
    {
        return $this->manage($user);
    }

    protected function manage(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('manage-tags');
    }
}

