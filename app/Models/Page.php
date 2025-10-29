<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Page extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'content',
        'status',
        'published_at',
        'scheduled_at',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'canonical_url',
        'og_image',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        // Use full-text search for better performance
        return $query->whereRaw(
            'MATCH(title, content) AGAINST(? IN NATURAL LANGUAGE MODE)',
            [$term]
        );
    }

    public function scopeSearchRelevance(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        // Full-text search with relevance score
        return $query
            ->selectRaw('*, MATCH(title, content) AGAINST(? IN NATURAL LANGUAGE MODE) as relevance', [$term])
            ->whereRaw('MATCH(title, content) AGAINST(? IN NATURAL LANGUAGE MODE)', [$term])
            ->orderByDesc('relevance');
    }
}

