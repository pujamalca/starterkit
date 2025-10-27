<?php

namespace App\Filament\Admin\Resources\Tags\Tables;

use App\Filament\Admin\Resources\Tags\TagResource;
use App\Models\Tag;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TagsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordAction(null)
            ->recordUrl(fn (Tag $record): string => TagResource::getUrl('edit', ['record' => $record]))
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->toggleable()
                    ->searchable(),
                BadgeColumn::make('type')
                    ->label('Tipe')
                    ->colors([
                        'info' => 'post',
                        'success' => 'page',
                        'warning' => 'news',
                    ])
                    ->sortable(),
                TextColumn::make('posts_count')
                    ->label('Jumlah Konten')
                    ->badge()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'post' => 'Post',
                        'page' => 'Halaman',
                        'news' => 'Berita',
                    ]),
            ]);
    }
}
