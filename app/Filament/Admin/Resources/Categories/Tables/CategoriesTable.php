<?php

namespace App\Filament\Admin\Resources\Categories\Tables;

use App\Filament\Admin\Resources\Categories\CategoryResource;
use App\Models\Category;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordAction(null)
            ->recordUrl(fn (Category $record): string => CategoryResource::getUrl('edit', ['record' => $record]))
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('parent.name')
                    ->label('Induk')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('posts_count')
                    ->label('Jumlah Konten')
                    ->sortable()
                    ->badge(),
                IconColumn::make('is_featured')
                    ->label('Sorotan')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua'),
                Filter::make('featured')
                    ->label('Sorotan')
                    ->query(fn ($query) => $query->where('is_featured', true)),
            ]);
    }
}
