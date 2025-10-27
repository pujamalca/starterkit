<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;
    use InteractsWithMedia;
    use LogsActivity;

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'gallery',
        'type',
        'status',
        'published_at',
        'scheduled_at',
        'is_featured',
        'is_sticky',
        'view_count',
        'reading_time',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'og_image',
        'metadata',
    ];

    protected $casts = [
        'gallery' => 'array',
        'metadata' => 'array',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_sticky' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function publish(): void
    {
        $this->forceFill([
            'status' => 'published',
            'published_at' => now(),
            'scheduled_at' => null,
        ])->save();
    }

    public function unpublish(): void
    {
        $this->forceFill([
            'status' => 'draft',
            'published_at' => null,
        ])->save();
    }

    public function schedule(?string $datetime): void
    {
        $this->forceFill([
            'status' => 'scheduled',
            'scheduled_at' => $datetime,
        ])->save();
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function incrementViews(): void
    {
        $this->increment('view_count');
    }

    public function getExcerptAttribute(?string $value): ?string
    {
        if ($value) {
            return $value;
        }

        return Str::limit(strip_tags((string) $this->content), 160);
    }

    public function getReadingTimeAttribute(?int $value): ?int
    {
        if ($value) {
            return $value;
        }

        $wordsPerMinute = 200;
        $wordCount = str_word_count(strip_tags((string) $this->content));

        return (int) max(1, ceil($wordCount / $wordsPerMinute));
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeByAuthor(Builder $query, int $userId): Builder
    {
        return $query->where('author_id', $userId);
    }

    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($term) {
            $builder
                ->where('title', 'like', "%{$term}%")
                ->orWhere('excerpt', 'like', "%{$term}%")
                ->orWhere('content', 'like', "%{$term}%");
        });
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('featured_image')
            ->singleFile();

        $this
            ->addMediaCollection('gallery')
            ->useFallbackUrl('https://via.placeholder.com/800x600.png?text=Gallery');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('post')
            ->logOnly([
                'title',
                'status',
                'published_at',
                'scheduled_at',
                'category_id',
                'author_id',
            ])
            ->logOnlyDirty();
    }
}
