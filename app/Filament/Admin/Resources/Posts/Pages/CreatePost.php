<?php

namespace App\Filament\Admin\Resources\Posts\Pages;

use App\Filament\Admin\Resources\Posts\PostResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['title'] ?? Str::random());

        if (($data['status'] ?? 'draft') === 'published' && blank($data['published_at'])) {
            $data['published_at'] = now();
        }

        if (($data['status'] ?? 'draft') !== 'scheduled') {
            $data['scheduled_at'] = null;
        }

        return $data;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Postingan berhasil dibuat';
    }
}

