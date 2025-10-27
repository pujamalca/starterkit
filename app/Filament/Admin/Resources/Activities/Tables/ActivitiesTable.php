<?php

namespace App\Filament\Admin\Resources\Activities\Tables;

use App\Models\Activity;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),
                BadgeColumn::make('log_name')
                    ->label('Log')
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(60)
                    ->tooltip(fn ($state) => $state)
                    ->searchable(),
                TextColumn::make('event')
                    ->label('Event')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('causer.name')
                    ->label('Pengguna')
                    ->placeholder('Sistem')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('subject_type')
                    ->label('Objek')
                    ->formatStateUsing(fn ($state, $record) => class_basename((string) $state) . ($record->subject_id ? ' #' . $record->subject_id : ''))
                    ->toggleable(),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('url')
                    ->label('URL')
                    ->limit(40)
                    ->tooltip(fn ($state) => $state)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->label('Log')
                    ->options(fn () => Activity::query()->whereNotNull('log_name')->distinct()->pluck('log_name', 'log_name')->toArray()),
                SelectFilter::make('event')
                    ->label('Event')
                    ->options(fn () => Activity::query()->whereNotNull('event')->distinct()->pluck('event', 'event')->toArray()),
                Filter::make('causer')
                    ->label('Memiliki Pengguna')
                    ->query(fn ($query) => $query->whereNotNull('causer_id')),
            ])
            ->actions([
                ViewAction::make()->label('Detail'),
            ])
            ->bulkActions([]);
    }
}
