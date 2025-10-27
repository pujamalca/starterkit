<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Post */
class PostResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image' => $this->featured_image,
            'gallery' => $this->gallery,
            'type' => $this->type,
            'status' => $this->status,
            'is_featured' => (bool) $this->is_featured,
            'is_sticky' => (bool) $this->is_sticky,
            'view_count' => (int) $this->view_count,
            'reading_time' => $this->reading_time,
            'published_at' => optional($this->published_at)->toIso8601String(),
            'scheduled_at' => optional($this->scheduled_at)->toIso8601String(),
            'seo' => [
                'title' => $this->seo_title,
                'description' => $this->seo_description,
                'keywords' => $this->seo_keywords,
                'og_image' => $this->og_image,
            ],
            'category' => $this->whenLoaded('category', fn () => CategoryResource::make($this->category)),
            'author' => $this->whenLoaded('author', fn () => UserResource::make($this->author)),
            'tags' => $this->whenLoaded('tags', fn () => TagResource::collection($this->tags)),
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
