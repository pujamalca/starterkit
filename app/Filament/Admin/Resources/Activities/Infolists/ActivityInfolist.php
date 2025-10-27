<?php

namespace App\Filament\Admin\Resources\Activities\Infolists;

use App\Models\Activity;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ActivityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('description')
                ->label('Deskripsi'),
            TextEntry::make('event')
                ->label('Event')
                ->badge(),
            TextEntry::make('log_name')
                ->label('Log'),
            TextEntry::make('created_at')
                ->label('Waktu')
                ->dateTime('d M Y H:i:s'),
            TextEntry::make('causer.name')
                ->label('Pengguna')
                ->formatStateUsing(fn ($state, Activity $record) => $record->causer?->name ?? 'Sistem'),
            TextEntry::make('subject_type')
                ->label('Objek')
                ->formatStateUsing(fn ($state, Activity $record) => class_basename((string) $state) . ($record->subject_id ? ' #' . $record->subject_id : '')),
            TextEntry::make('ip_address')
                ->label('Alamat IP')
                ->placeholder('-'),
            TextEntry::make('method')
                ->label('Metode HTTP')
                ->placeholder('-'),
            TextEntry::make('url')
                ->label('URL')
                ->url(fn ($state) => $state)
                ->placeholder('-'),
            KeyValueEntry::make('properties.attributes')
                ->label('Atribut Baru')
                ->keyLabel('Kolom')
                ->valueLabel('Nilai')
                ->hidden(fn ($record) => empty($record->properties['attributes'] ?? [])),
            KeyValueEntry::make('properties.old')
                ->label('Atribut Lama')
                ->keyLabel('Kolom')
                ->valueLabel('Nilai')
                ->hidden(fn ($record) => empty($record->properties['old'] ?? [])),
        ]);
    }
}

