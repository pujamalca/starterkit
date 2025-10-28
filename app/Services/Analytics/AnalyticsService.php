<?php

namespace App\Services\Analytics;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function summary(): array
    {
        $totalViews = (int) Post::sum('view_count');
        $totalPosts = (int) Post::count();
        $publishedPosts = (int) Post::published()->count();
        $draftPosts = (int) Post::draft()->count();
        $avgViews = $totalPosts > 0 ? round($totalViews / $totalPosts, 1) : 0;
        $totalComments = (int) Comment::count();
        $commentsPerPost = $totalPosts > 0 ? round($totalComments / $totalPosts, 2) : 0;

        return [
            'total_views' => $totalViews,
            'avg_views_per_post' => $avgViews,
            'posts_published' => $publishedPosts,
            'posts_draft' => $draftPosts,
            'total_comments' => $totalComments,
            'comments_per_post' => $commentsPerPost,
            'active_users' => (int) User::where('is_active', true)->count(),
            'new_users_30_days' => (int) User::where('created_at', '>=', now()->subDays(30))->count(),
        ];
    }

    public function engagementBreakdown(): array
    {
        return [
            'posts' => [
                'published' => (int) Post::published()->count(),
                'draft' => (int) Post::draft()->count(),
                'scheduled' => (int) Post::where('status', 'scheduled')->count(),
            ],
            'comments' => [
                'approved' => (int) Comment::where('is_approved', true)->count(),
                'pending' => (int) Comment::where('is_approved', false)->count(),
            ],
        ];
    }

    public function topPosts(int $limit = 5): Collection
    {
        return Post::query()
            ->select(['id', 'title', 'slug', 'view_count', 'status', 'published_at'])
            ->orderByDesc('view_count')
            ->limit($limit)
            ->get();
    }

    public function activityTrends(int $days = 30): array
    {
        $end = Carbon::now()->endOfDay();
        $start = $end->copy()->subDays($days - 1)->startOfDay();
        $period = CarbonPeriod::create($start, $end);

        $postCounts = Post::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->pluck('total', 'date');

        $commentCounts = Comment::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->pluck('total', 'date');

        $userCounts = User::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->pluck('total', 'date');

        $trend = [];

        foreach ($period as $date) {
            $formatted = $date->format('Y-m-d');

            $trend[] = [
                'date' => $date->translatedFormat('d M'),
                'posts' => (int) ($postCounts[$formatted] ?? 0),
                'comments' => (int) ($commentCounts[$formatted] ?? 0),
                'users' => (int) ($userCounts[$formatted] ?? 0),
            ];
        }

        return $trend;
    }

    public function engagementRates(): array
    {
        $publishedPosts = (int) Post::published()->count();
        $totalViews = (int) Post::sum('view_count');
        $totalComments = (int) Comment::count();

        $commentConversion = $totalViews > 0
            ? round(($totalComments / $totalViews) * 100, 2)
            : 0;

        $viewsPerPublished = $publishedPosts > 0
            ? round($totalViews / $publishedPosts, 1)
            : 0;

        return [
            'comment_conversion_rate' => $commentConversion,
            'views_per_published_post' => $viewsPerPublished,
        ];
    }
}

