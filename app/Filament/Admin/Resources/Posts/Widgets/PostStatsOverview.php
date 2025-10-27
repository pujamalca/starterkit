<?php

namespace App\Filament\Admin\Resources\Posts\Widgets;

use App\Models\Comment;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class PostStatsOverview extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Postingan', Post::count())
                ->icon('heroicon-o-document-text'),
            Card::make('Dipublikasikan', Post::published()->count())
                ->description('Konten yang live')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            Card::make('Komentar Pending', Comment::pending()->count())
                ->description('Menunggu persetujuan')
                ->descriptionIcon('heroicon-o-chat-bubble-left-right')
                ->color('warning'),
        ];
    }
}
