<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @property \App\Models\Page $resource
 */
/**
 * @OA\Schema(
 *     schema="PageResource",
 *     title="Page",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Tentang Kami"),
 *     @OA\Property(property="slug", type="string", example="tentang-kami"),
 *     @OA\Property(property="content", type="string"),
 *     @OA\Property(property="status", type="string", example="published"),
 *     @OA\Property(property="published_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(
 *         property="seo",
 *         type="object",
 *         @OA\Property(property="title", type="string", nullable=true),
 *         @OA\Property(property="description", type="string", nullable=true),
 *         @OA\Property(property="keywords", type="string", nullable=true),
 *         @OA\Property(property="canonical_url", type="string", nullable=true),
 *         @OA\Property(property="og_image", type="string", nullable=true)
 *     ),
 *     @OA\Property(property="metadata", type="object", nullable=true),
 *     @OA\Property(
 *         property="author",
 *         type="object",
 *         @OA\Property(property="id", type="integer", nullable=true),
 *         @OA\Property(property="name", type="string", nullable=true)
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class PageResource extends JsonResource
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
            'content' => $this->content,
            'status' => $this->status,
            'published_at' => $this->published_at,
            'seo' => [
                'title' => $this->seo_title,
                'description' => $this->seo_description,
                'keywords' => $this->seo_keywords,
                'canonical_url' => $this->canonical_url,
                'og_image' => $this->og_image,
            ],
            'metadata' => $this->metadata,
            'author' => [
                'id' => $this->author?->id,
                'name' => $this->author?->name,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

