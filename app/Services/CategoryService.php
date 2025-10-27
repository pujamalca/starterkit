<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class CategoryService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        $perPage = (int) ($filters['per_page'] ?? 15);
        $perPage = max(1, min($perPage, 50));

        $query = Category::query()
            ->withCount('posts')
            ->with(['parent'])
            ->orderBy('sort_order')
            ->orderBy('name');

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage)->appends($filters);
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        if (($active = Arr::get($filters, 'is_active')) !== null) {
            $query->where('is_active', filter_var($active, FILTER_VALIDATE_BOOLEAN));
        }

        if (($featured = Arr::get($filters, 'is_featured')) !== null) {
            $query->where('is_featured', filter_var($featured, FILTER_VALIDATE_BOOLEAN));
        }

        if (Arr::get($filters, 'only_root')) {
            $query->root();
        }

        if ($search = Arr::get($filters, 'search')) {
            $query->where(function (Builder $builder) use ($search): void {
                $builder
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }
    }
}
