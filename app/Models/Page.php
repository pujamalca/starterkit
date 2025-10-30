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

    /**
     * Get formatted content with proper HTML tags
     */
    public function getFormattedContentAttribute(): string
    {
        $content = $this->content;

        if (empty($content)) {
            return '';
        }

        // Check if content already has HTML paragraph tags
        if (preg_match('/<(p|h[1-6]|ul|ol|div)[\s>]/i', $content)) {
            return $content;
        }

        // Content is plain text or markdown-like, format it
        $lines = preg_split('/\r\n|\r|\n/', $content);
        $formatted = '';
        $inList = false;

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip empty lines
            if (empty($line)) {
                if ($inList) {
                    $formatted .= '</ul>' . "\n";
                    $inList = false;
                }
                continue;
            }

            // Check for markdown headings
            if (preg_match('/^(#{1,4})\s+(.+)$/', $line, $matches)) {
                if ($inList) {
                    $formatted .= '</ul>' . "\n";
                    $inList = false;
                }
                $level = strlen($matches[1]);
                $text = $matches[2];
                $formatted .= "<h{$level}>{$text}</h{$level}>\n";
            }
            // Check for list items
            elseif (preg_match('/^[-*]\s+(.+)$/', $line, $matches)) {
                if (!$inList) {
                    $formatted .= '<ul>' . "\n";
                    $inList = true;
                }
                $formatted .= '<li>' . $matches[1] . '</li>' . "\n";
            }
            // Regular paragraph
            else {
                if ($inList) {
                    $formatted .= '</ul>' . "\n";
                    $inList = false;
                }
                // Process inline formatting
                $line = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $line);
                $line = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $line);
                $line = preg_replace('/`(.+?)`/', '<code>$1</code>', $line);
                $formatted .= '<p>' . $line . '</p>' . "\n";
            }
        }

        // Close any open list
        if ($inList) {
            $formatted .= '</ul>' . "\n";
        }

        return $formatted;
    }
}

