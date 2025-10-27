<?php

namespace App\Filament\Admin\Resources\Comments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('content')
                    ->label('Komentar')
                    ->limit(80)
                    ->tooltip(fn ($state) => $state),
                TextColumn::make('commentable.title')
                    ->label('Pada Konten')
                    ->formatStateUsing(fn ($state, $record) => $record->commentable?->title ?? class_basename((string) $record->commentable_type) . ' #' . $record->commentable_id)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->formatStateUsing(fn ($state, $record) => $record->user?->name ?? ($record->guest_name ?: 'Tamu')),
                IconColumn::make('is_approved')
                    ->label('Disetujui')
                    ->boolean(),
                IconColumn::make('is_featured')
                    ->label('Sorotan')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_approved')
                    ->label('Status Persetujuan')
                    ->placeholder('Semua'),
                SelectFilter::make('commentable_type')
                    ->label('Jenis Konten')
                    ->options([
                        'App\\Models\\Post' => 'Postingan',
                    ]),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Setujui')
                    ->visible(fn ($record) => ! $record->is_approved)
                    ->action(fn ($record) => $record->approve())
                    ->requiresConfirmation(),
                Action::make('reject')
                    ->label('Batalkan')
                    ->color('warning')
                    ->visible(fn ($record) => $record->is_approved)
                    ->action(fn ($record) => $record->reject())
                    ->requiresConfirmation(),
                EditAction::make()->label('Edit'),
                DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus'),
                    ForceDeleteBulkAction::make()->label('Hapus Permanen'),
                    RestoreBulkAction::make()->label('Pulihkan'),
                ]),
            ]);
    }
}
