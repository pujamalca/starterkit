<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/** @mixin \App\Models\Tag */
/**
 * @OA\Schema(
 *     schema="TagResource",
 *     title="Tag",
 *     @OA\Property(property="id", type="integer", example=4),
 *     @OA\Property(property="name", type="string", example="Laravel"),
 *     @OA\Property(property="slug", type="string", example="laravel"),
 *     @OA\Property(property="type", type="string", example="post"),
 *     @OA\Property(property="color", type="string", nullable=true, example="#2563EB")
 * )
 */
class TagResource extends JsonResource
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
            'type' => $this->type,
            'color' => $this->color,
        ];
    }
}
