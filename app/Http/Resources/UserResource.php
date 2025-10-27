<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'avatar' => method_exists($this->resource, 'getAvatarUrl')
                ? $this->resource->getAvatarUrl()
                : $this->avatar,
            'bio' => $this->bio,
            'phone' => $this->phone,
            'roles' => method_exists($this->resource, 'getRoleNames')
                ? $this->resource->getRoleNames()->values()->all()
                : [],
            'permissions' => method_exists($this->resource, 'getAllPermissions')
                ? $this->resource->getAllPermissions()->pluck('name')->values()->all()
                : [],
            'preferences' => $this->preferences,
            'is_active' => (bool) $this->is_active,
            'last_login_at' => optional($this->last_login_at)->toIso8601String(),
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
