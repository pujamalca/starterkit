<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Repositories\PageRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(
        protected readonly PageRepository $pages,
    ) {
    }

    public function show(Request $request, string $slug): View
    {
        $page = $this->pages->findPublishedBySlug($slug);

        return view('pages.show', [
            'page' => $page,
        ]);
    }

    public function preview(Request $request, Page $page): View
    {
        // Preview dapat diakses untuk semua status (draft, scheduled, published)
        return view('pages.show', [
            'page' => $page,
            'preview' => true,
        ]);
    }
}
