<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/** @mixin \App\Models\User */
/**
 * @OA\Schema(
 *     schema="UserResource",
 *     title="User",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Admin User"),
 *     @OA\Property(property="username", type="string", example="admin"),
 *     @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
 *     @OA\Property(property="avatar", type="string", nullable=true, example="https://..."),
 *     @OA\Property(property="bio", type="string", nullable=true),
 *     @OA\Property(property="phone", type="string", nullable=true),
 *     @OA\Property(property="roles", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="permissions", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="preferences", type="object", nullable=true),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="last_login_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
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
