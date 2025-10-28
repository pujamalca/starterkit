<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/** @mixin \App\Models\Post */
/**
 * @OA\Schema(
 *     schema="PostResource",
 *     title="Post",
 *     @OA\Property(property="id", type="integer", example=12),
 *     @OA\Property(property="title", type="string", example="How to use the starter kit"),
 *     @OA\Property(property="slug", type="string", example="how-to-use-the-starter-kit"),
 *     @OA\Property(property="excerpt", type="string", nullable=true),
 *     @OA\Property(property="content", type="string"),
 *     @OA\Property(property="featured_image", type="string", nullable=true),
 *     @OA\Property(property="gallery", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="type", type="string", example="article"),
 *     @OA\Property(property="status", type="string", example="published"),
 *     @OA\Property(property="is_featured", type="boolean"),
 *     @OA\Property(property="is_sticky", type="boolean"),
 *     @OA\Property(property="view_count", type="integer", example=150),
 *     @OA\Property(property="reading_time", type="integer", example=4),
 *     @OA\Property(property="published_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="scheduled_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(
 *         property="seo",
 *         type="object",
 *         @OA\Property(property="title", type="string", nullable=true),
 *         @OA\Property(property="description", type="string", nullable=true),
 *         @OA\Property(property="keywords", type="string", nullable=true),
 *         @OA\Property(property="og_image", type="string", nullable=true)
 *     ),
 *     @OA\Property(property="category", ref="#/components/schemas/CategoryResource"),
 *     @OA\Property(property="author", ref="#/components/schemas/UserResource"),
 *     @OA\Property(property="tags", type="array", @OA\Items(ref="#/components/schemas/TagResource")),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
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
