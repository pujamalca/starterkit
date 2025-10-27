<?php

namespace App\Filament\Admin\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Kategori')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->maxLength(100)
                        ->reactive()
                        ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true)
                        ->helperText('Slug digunakan pada URL. Pastikan unik.'),
                    Select::make('parent_id')
                        ->label('Kategori Induk')
                        ->relationship('parent', 'name')
                        ->searchable()
                        ->preload()
                        ->placeholder('Tanpa induk')
                        ->getOptionLabelFromRecordUsing(fn (Category $record) => $record->path)
                        ->columnSpanFull(),
                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
            Section::make('Pengaturan Tampilan')
                ->columns(2)
                ->schema([
                    FileUpload::make('image')
                        ->label('Gambar')
                        ->image()
                        ->directory('categories/images')
                        ->disk('public'),
                    TextInput::make('icon')
                        ->label('Ikon (opsional)')
                        ->maxLength(50),
                    ColorPicker::make('color')
                        ->label('Warna'),
                    TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0),
                    Toggle::make('is_featured')
                        ->label('Tampilkan di Beranda')
                        ->default(false),
                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ]),
            Section::make('Metadata')
                ->schema([
                    KeyValue::make('metadata')
                        ->label('Metadata Tambahan')
                        ->keyLabel('Kunci')
                        ->valueLabel('Nilai')
                        ->addButtonLabel('Tambah')
                        ->reorderable()
                        ->nullable(),
                ]),
        ]);
    }
}
