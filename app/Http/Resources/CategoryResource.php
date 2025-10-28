<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/** @mixin \App\Models\Category */
/**
 * @OA\Schema(
 *     schema="CategoryResource",
 *     title="Category",
 *     @OA\Property(property="id", type="integer", example=3),
 *     @OA\Property(property="name", type="string", example="News"),
 *     @OA\Property(property="slug", type="string", example="news"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="image", type="string", nullable=true),
 *     @OA\Property(property="icon", type="string", nullable=true),
 *     @OA\Property(property="color", type="string", nullable=true, example="#F59E0B"),
 *     @OA\Property(property="sort_order", type="integer", example=1),
 *     @OA\Property(property="is_featured", type="boolean"),
 *     @OA\Property(property="is_active", type="boolean"),
 *     @OA\Property(property="posts_count", type="integer", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class CategoryResource extends JsonResource
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
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'icon' => $this->icon,
            'color' => $this->color,
            'sort_order' => $this->sort_order,
            'is_featured' => (bool) $this->is_featured,
            'is_active' => (bool) $this->is_active,
            'posts_count' => $this->when(isset($this->posts_count), (int) $this->posts_count),
            'parent' => $this->whenLoaded('parent', fn () => CategoryResource::make($this->parent)),
            'children' => $this->whenLoaded('children', fn () => CategoryResource::collection($this->children)),
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
