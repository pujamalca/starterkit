<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PageController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $pages = Page::published()
            ->when(
                $search = $request->query('search'),
                fn ($query) => $query->where('title', 'like', "%{$search}%")
            )
            ->orderByDesc('published_at')
            ->paginate($request->integer('per_page', 10));

        return PageResource::collection($pages);
    }

    public function show(string $slug): PageResource
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();

        return PageResource::make($page);
    }
}

