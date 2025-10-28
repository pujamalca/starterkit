<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/** @mixin \App\Models\Comment */
/**
 * @OA\Schema(
 *     schema="CommentResource",
 *     title="Comment",
 *     @OA\Property(property="id", type="integer", example=45),
 *     @OA\Property(property="content", type="string", example="Komentar yang sangat membantu"),
 *     @OA\Property(property="is_approved", type="boolean", example=true),
 *     @OA\Property(property="guest_name", type="string", nullable=true),
 *     @OA\Property(property="guest_email", type="string", nullable=true),
 *     @OA\Property(property="parent_id", type="integer", nullable=true),
 *     @OA\Property(property="metadata", type="object", nullable=true),
 *     @OA\Property(property="commentable_type", type="string", example="App\\Models\\Post"),
 *     @OA\Property(property="commentable_id", type="integer", example=12),
 *     @OA\Property(property="replies_count", type="integer", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="user", ref="#/components/schemas/UserResource")
 * )
 */
class CommentResource extends JsonResource
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
            'content' => $this->content,
            'is_approved' => (bool) $this->is_approved,
            'guest_name' => $this->guest_name,
            'guest_email' => $this->guest_email,
            'parent_id' => $this->parent_id,
            'metadata' => $this->metadata,
            'user' => $this->whenLoaded('user', fn () => UserResource::make($this->user)),
            'replies_count' => $this->when(isset($this->replies_count), (int) $this->replies_count),
            'commentable_type' => $this->commentable_type,
            'commentable_id' => $this->commentable_id,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
