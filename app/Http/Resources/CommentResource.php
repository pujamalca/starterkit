<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Comment */
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
