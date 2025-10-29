<?php

namespace App\Filament\Admin\Resources\Tags;

use App\Filament\Admin\Resources\Tags\Pages\CreateTag;
use App\Filament\Admin\Resources\Tags\Pages\EditTag;
use App\Filament\Admin\Resources\Tags\Pages\ListTags;
use App\Filament\Admin\Resources\Tags\Schemas\TagForm;
use App\Filament\Admin\Resources\Tags\Tables\TagsTable;
use App\Models\Tag;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Tag';

    public static function form(Schema $schema): Schema
    {
        return TagForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TagsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTags::route('/'),
            'create' => CreateTag::route('/create'),
            'edit' => EditTag::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('posts');
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Konten';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('manage-tags') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('manage-tags') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->can('manage-tags') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('manage-tags') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->can('manage-tags') ?? false;
    }

}
