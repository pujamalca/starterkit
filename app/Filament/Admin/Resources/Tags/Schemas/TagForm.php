<?php

namespace App\Filament\Admin\Resources\Tags\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Tag')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->maxLength(50)
                        ->reactive()
                        ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(50)
                        ->unique(ignoreRecord: true),
                    Select::make('type')
                        ->label('Tipe')
                        ->options([
                            'post' => 'Post',
                            'page' => 'Halaman',
                            'news' => 'Berita',
                        ])
                        ->default('post'),
                    TextInput::make('color')
                        ->label('Warna (opsional)')
                        ->maxLength(7)
                        ->placeholder('#FFFFFF'),
                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
            KeyValue::make('metadata')
                ->label('Metadata tambahan')
                ->keyLabel('Kunci')
                ->valueLabel('Nilai')
                ->addButtonLabel('Tambah')
                ->nullable(),
        ]);
    }
}

