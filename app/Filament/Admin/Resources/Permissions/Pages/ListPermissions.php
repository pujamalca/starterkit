<?php

namespace App\Filament\Admin\Resources\Permissions\Pages;

use App\Filament\Admin\Resources\Permissions\PermissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Permission Baru'),
        ];
    }
}

