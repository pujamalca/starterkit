<?php

namespace App\Filament\Admin\Resources\Posts\RelationManagers;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Actions;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $title = 'Komentar';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Textarea::make('content')
                ->label('Isi Komentar')
                ->rows(4)
                ->required(),
            Toggle::make('is_approved')
                ->label('Disetujui')
                ->inline(false),
            Toggle::make('is_featured')
                ->label('Sorotan')
                ->inline(false),
            KeyValue::make('metadata')
                ->label('Metadata Tambahan')
                ->nullable(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('content')
                    ->label('Komentar')
                    ->limit(80)
                    ->tooltip(fn ($state) => $state),
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
                    ->since(),
            ])
            ->headerActions([])
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
                Actions\EditAction::make()->label('Edit'),
                DeleteAction::make()->label('Hapus'),
            ])
            ->emptyStateHeading('Belum ada komentar');
    }
}
