<?php

namespace App\Filament\Admin\Resources\Posts\Pages;

use App\Filament\Admin\Resources\Posts\PostResource;
use App\Filament\Admin\Resources\Posts\Widgets\PostStatsOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Postingan Baru'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PostStatsOverview::class,
        ];
    }
}
