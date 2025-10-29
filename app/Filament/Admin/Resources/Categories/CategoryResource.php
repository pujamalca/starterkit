<?php

namespace App\Filament\Admin\Resources\Categories;

use App\Filament\Admin\Resources\Categories\Pages\CreateCategory;
use App\Filament\Admin\Resources\Categories\Pages\EditCategory;
use App\Filament\Admin\Resources\Categories\Pages\ListCategories;
use App\Filament\Admin\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Admin\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $navigationLabel = 'Kategori';

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
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
        return auth()->user()?->can('manage-categories') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('manage-categories') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->can('manage-categories') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('manage-categories') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->can('manage-categories') ?? false;
    }

}
