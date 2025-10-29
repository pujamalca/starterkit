<?php

namespace App\Filament\Admin\Resources\Activities;

use App\Filament\Admin\Resources\Activities\Infolists\ActivityInfolist;
use App\Filament\Admin\Resources\Activities\Pages\ListActivities;
use App\Filament\Admin\Resources\Activities\Pages\ViewActivity;
use App\Filament\Admin\Resources\Activities\Tables\ActivitiesTable;
use App\Models\Activity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $navigationLabel = 'Log Aktivitas';

    protected static ?string $recordTitleAttribute = 'summary';

    public static function table(Table $table): Table
    {
        return ActivitiesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ActivityInfolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivities::route('/'),
            'view' => ViewActivity::route('/{record}'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Sistem';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->latest('created_at');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view-activity-log') ?? false;
    }

    public static function canCreate(): bool
    {
        return false; // Activity logs should not be manually created
    }

    public static function canEdit($record): bool
    {
        return false; // Activity logs should not be edited
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('view-activity-log') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->can('view-activity-log') ?? false;
    }
}

