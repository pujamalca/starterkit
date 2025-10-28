<?php

namespace App\Services;

use App\Jobs\GenerateSitemap;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PostPublishedNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class PostService
{
    public function __construct(
        protected readonly MediaService $mediaService,
    ) {
    }

    public function listPublished(array $filters = []): LengthAwarePaginator
    {
        $filters = array_merge($filters, ['status' => 'published']);

        return $this->paginateWithFilters($filters);
    }

    public function listByCategory(int $categoryId, array $filters = []): LengthAwarePaginator
    {
        $filters = array_merge($filters, [
            'category_id' => $categoryId,
            'status' => 'published',
        ]);

        return $this->paginateWithFilters($filters);
    }

    public function create(User $author, array $data): Post
    {
        $payload = $this->preparePayload($data);
        $payload['author_id'] = $author->id;

        /** @var Post $post */
        $post = Post::query()->create($payload);

        $this->syncRelations($post, $data);

        $post->load(['category', 'author', 'tags']);

        $this->afterPersist($post, null);

        return $post;
    }

    public function update(Post $post, array $data): Post
    {
        $payload = $this->preparePayload($data, $post);

        $previousStatus = $post->status;

        $post->fill($payload);
        $post->save();

        $this->syncRelations($post, $data);

        $post->load(['category', 'author', 'tags']);

        $this->afterPersist($post, $previousStatus);

        return $post;
    }

    public function delete(Post $post): void
    {
        $wasPublished = $post->status === 'published';

        $post->delete();

        if ($wasPublished) {
            GenerateSitemap::dispatch();
        }
    }

    protected function paginateWithFilters(array $filters): LengthAwarePaginator
    {
        $perPage = (int) ($filters['per_page'] ?? 15);
        $perPage = max(1, min($perPage, 50));

        $query = $this->applyFilters(
            Post::query()->with(['category', 'author', 'tags']),
            $filters,
        );

        $sortField = $filters['sort'] ?? 'published_at';
        $direction = strtolower((string) ($filters['direction'] ?? 'desc')) === 'asc' ? 'asc' : 'desc';

        if (! in_array($sortField, ['published_at', 'created_at', 'view_count'], true)) {
            $sortField = 'published_at';
        }

        if ($sortField === 'view_count') {
            $query->orderBy('view_count', $direction);
        } else {
            $query->orderBy($sortField, $direction)->orderBy('id', 'desc');
        }

        return $query->paginate($perPage)->appends($filters);
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (($status = Arr::get($filters, 'status')) !== null) {
            $statuses = is_array($status) ? $status : [$status];
            $query->whereIn('status', $statuses);
        }

        if ($search = Arr::get($filters, 'search')) {
            $query->search($search);
        }

        if ($categoryId = Arr::get($filters, 'category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($categorySlug = Arr::get($filters, 'category_slug')) {
            $query->whereHas('category', fn (Builder $builder) => $builder->where('slug', $categorySlug));
        }

        if ($authorId = Arr::get($filters, 'author_id')) {
            $query->where('author_id', $authorId);
        }

        if ($author = Arr::get($filters, 'author')) {
            $query->whereHas('author', function (Builder $builder) use ($author): void {
                $builder
                    ->where('username', $author)
                    ->orWhere('email', $author);
            });
        }

        if ($tag = Arr::get($filters, 'tag')) {
            $query->whereHas('tags', function (Builder $builder) use ($tag): void {
                $builder->when(
                    is_numeric($tag),
                    fn (Builder $inner) => $inner->where('tags.id', $tag),
                    fn (Builder $inner) => $inner->where('tags.slug', $tag),
                );
            });
        }

        if (($featured = Arr::get($filters, 'is_featured')) !== null) {
            $query->where('is_featured', filter_var($featured, FILTER_VALIDATE_BOOLEAN));
        }

        return $query;
    }

    protected function preparePayload(array $data, ?Post $post = null): array
    {
        $payload = Arr::only($data, [
            'category_id',
            'title',
            'slug',
            'excerpt',
            'content',
            'type',
            'status',
            'published_at',
            'scheduled_at',
            'is_featured',
            'is_sticky',
            'seo_title',
            'seo_description',
            'seo_keywords',
            'og_image',
            'metadata',
        ]);

        $payload['type'] = $payload['type'] ?? ($post?->type ?? 'article');
        $payload['status'] = $payload['status'] ?? ($post?->status ?? 'draft');

        if (isset($payload['metadata']) && ! is_array($payload['metadata'])) {
            $payload['metadata'] = (array) $payload['metadata'];
        }

        if (($payload['status'] ?? null) === 'published' && empty($payload['published_at'])) {
            $payload['published_at'] = now();
        }

        if (($payload['status'] ?? null) !== 'scheduled') {
            $payload['scheduled_at'] = null;
        }

        if (array_key_exists('featured_image', $data)) {
            $payload['featured_image'] = $this->mediaService->normalizePath($data['featured_image']);
        }

        if (array_key_exists('gallery', $data)) {
            $payload['gallery'] = is_array($data['gallery'])
                ? $this->mediaService->normalizeMany($data['gallery'])
                : null;
        }

        return $payload;
    }

    protected function syncRelations(Post $post, array $data): void
    {
        if (array_key_exists('tags', $data)) {
            $post->tags()->sync(Arr::wrap($data['tags']));
        }
    }

    protected function afterPersist(Post $post, ?string $previousStatus): void
    {
        if ($post->status === 'published' && $previousStatus !== 'published') {
            $this->notifyPublished($post);
            GenerateSitemap::dispatch();
        }
    }

    protected function notifyPublished(Post $post): void
    {
        $author = $post->author;

        if ($author) {
            $author->notify(new PostPublishedNotification($post));
        }
    }
}
