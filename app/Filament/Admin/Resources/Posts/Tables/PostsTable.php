<?php

namespace App\Filament\Admin\Resources\Posts\Tables;

use App\Filament\Admin\Resources\Posts\PostResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordAction(null)
            ->recordUrl(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record]))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'info' => 'draft',
                        'success' => 'published',
                        'warning' => 'scheduled',
                        'gray' => 'archived',
                    ])
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'draft' => 'Draft',
                        'published' => 'Dipublikasikan',
                        'scheduled' => 'Terjadwal',
                        'archived' => 'Arsip',
                        default => $state,
                    })
                    ->sortable(),
                BadgeColumn::make('type')
                    ->label('Tipe')
                    ->colors([
                        'info' => 'article',
                        'success' => 'page',
                        'warning' => 'news',
                    ])
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'article' => 'Artikel',
                        'page' => 'Halaman',
                        'news' => 'Berita',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('author.name')
                    ->label('Penulis')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('comments_count')
                    ->label('Komentar')
                    ->badge()
                    ->sortable(),
                TextColumn::make('view_count')
                    ->label('Tayangan')
                    ->sortable()
                    ->badge(),
                IconColumn::make('is_featured')
                    ->label('Sorotan')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('Dipublikasikan')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Dipublikasikan',
                        'scheduled' => 'Terjadwal',
                        'archived' => 'Arsip',
                    ]),
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'article' => 'Artikel',
                        'page' => 'Halaman',
                        'news' => 'Berita',
                    ]),
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                TernaryFilter::make('is_featured')
                    ->label('Sorotan')
                    ->placeholder('Semua'),
            ])
            ->actions([
                Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (Post $record) => route('posts.preview', $record))
                    ->openUrlInNewTab(),
                EditAction::make()->label('Edit'),
                DeleteAction::make()->label('Hapus'),
            ]);
    }
}
