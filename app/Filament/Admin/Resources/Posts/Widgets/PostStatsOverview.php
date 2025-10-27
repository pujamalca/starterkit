<?php

namespace App\Filament\Admin\Resources\Posts\Widgets;

use App\Models\Comment;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PostStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Postingan', Post::count())
                ->icon('heroicon-o-document-text'),
            Stat::make('Dipublikasikan', Post::published()->count())
                ->description('Konten yang live')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Komentar Pending', Comment::pending()->count())
                ->description('Menunggu persetujuan')
                ->descriptionIcon('heroicon-o-chat-bubble-left-right')
                ->color('warning'),
        ];
    }
}
