<?php

namespace App\Filament\Admin\Resources\Comments\Schemas;

use App\Models\User;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Komentar')
            ->columnSpanFull()
                ->schema([
                    Textarea::make('content')
                        ->label('Isi Komentar')
                        ->rows(5)
                        ->required(),
                    Select::make('user_id')
                        ->label('Pengguna')
                        ->options(fn () => User::query()->orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->placeholder('Komentar tamu'),
                    TextInput::make('guest_name')
                        ->label('Nama Tamu')
                        ->maxLength(50),
                    TextInput::make('guest_email')
                        ->label('Email Tamu')
                        ->maxLength(100),
                    Toggle::make('is_approved')
                        ->label('Disetujui')
                        ->helperText('Centang untuk menampilkan komentar di publik.'),
                    Toggle::make('is_featured')
                        ->label('Sorotan'),
                    TextInput::make('likes_count')
                        ->label('Jumlah Suka')
                        ->numeric()
                        ->default(0),
                    KeyValue::make('metadata')
                        ->label('Metadata Tambahan')
                        ->nullable(),
                ]),
        ]);
    }
}

