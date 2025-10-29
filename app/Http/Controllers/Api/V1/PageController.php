<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Repositories\PageRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Pages",
 *     description="Manajemen halaman statis."
 * )
 */
class PageController extends Controller
{
    public function __construct(
        protected readonly PageRepository $pages,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/pages",
     *     summary="Daftar halaman",
     *     tags={"Pages"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Kata kunci pencarian di judul halaman",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Jumlah data per halaman",
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daftar halaman berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/PageResource")
     *             ),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/v1/pages/{slug}",
     *     summary="Detail halaman",
     *     tags={"Pages"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Slug halaman",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail halaman berhasil diambil",
     *         @OA\JsonContent(ref="#/components/schemas/PageResource")
     *     ),
     *     @OA\Response(response=404, description="Halaman tidak ditemukan")
     * )
     */
    public function show(string $slug): PageResource
    {
        $page = $this->pages->findPublishedBySlug($slug);

        return PageResource::make($page);
    }
}
