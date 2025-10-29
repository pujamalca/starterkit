<?php

namespace App\Filament\Admin\Resources\Comments;

use App\Filament\Admin\Resources\Comments\Pages\EditComment;
use App\Filament\Admin\Resources\Comments\Pages\ListComments;
use App\Filament\Admin\Resources\Comments\Schemas\CommentForm;
use App\Filament\Admin\Resources\Comments\Tables\CommentsTable;
use App\Models\Comment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Komentar';

    public static function form(Schema $schema): Schema
    {
        return CommentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommentsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComments::route('/'),
            'edit' => EditComment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['commentable', 'user']);
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Konten';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('manage-comments') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('manage-comments') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->can('manage-comments') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('manage-comments') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->can('manage-comments') ?? false;
    }

}
