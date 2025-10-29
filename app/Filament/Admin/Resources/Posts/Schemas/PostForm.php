<?php

namespace App\Filament\Admin\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Post Tabs')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Konten')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('title')
                                    ->label('Judul')
                                    ->required()
                                    ->maxLength(200)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(200)
                                    ->unique(ignoreRecord: true),
                            ]),
                            Grid::make(3)->schema([
                                Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name', fn ($query) => $query->orderBy('name'))
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Select::make('type')
                                    ->label('Tipe')
                                    ->options([
                                        'article' => 'Artikel',
                                        'page' => 'Halaman',
                                        'news' => 'Berita',
                                    ])
                                    ->required()
                                    ->default('article'),
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Dipublikasikan',
                                        'scheduled' => 'Terjadwal',
                                        'archived' => 'Arsip',
                                    ])
                                    ->default('draft')
                                    ->required(),
                            ]),
                            Select::make('tags')
                                ->label('Tag')
                                ->relationship('tags', 'name')
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->helperText('Pilih beberapa tag untuk memudahkan pencarian.'),
                            Textarea::make('excerpt')
                                ->label('Ringkasan')
                                ->rows(4),
                            RichEditor::make('content')
                                ->label('Konten')
                                ->columnSpanFull()
                                ->fileAttachmentsDisk('public')
                                ->fileAttachmentsDirectory('posts/editor')
                                ->required(),
                        ]),
                    Tab::make('Media')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            FileUpload::make('featured_image')
                                ->label('Gambar Utama')
                                ->image()
                                ->disk('public')
                                ->directory('posts/featured')
                                ->imageEditor(),
                            FileUpload::make('gallery')
                                ->label('Galeri')
                                ->multiple()
                                ->image()
                                ->reorderable()
                                ->disk('public')
                                ->directory('posts/gallery'),
                            Toggle::make('is_featured')
                                ->label('Sorotan Beranda')
                                ->default(false),
                            Toggle::make('is_sticky')
                                ->label('Tetap di Urutan Teratas')
                                ->default(false),
                        ]),
                    Tab::make('Publikasi')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            DateTimePicker::make('published_at')
                                ->label('Tanggal Publikasi')
                                ->seconds(false)
                                ->native(false),
                            DateTimePicker::make('scheduled_at')
                                ->label('Jadwalkan Publikasi')
                                ->seconds(false)
                                ->native(false)
                                ->helperText('Isi jika status diset menjadi Terjadwal.'),
                        ]),
                    Tab::make('SEO & Metadata')
                        ->icon('heroicon-o-chart-bar')
                        ->schema([
                            TextInput::make('seo_title')
                                ->label('Judul SEO')
                                ->maxLength(60)
                                ->helperText('Maksimal 60 karakter'),
                            Textarea::make('seo_description')
                                ->label('Deskripsi SEO')
                                ->rows(3)
                                ->maxLength(160)
                                ->helperText('Maksimal 160 karakter'),
                            TextInput::make('seo_keywords')
                                ->label('Kata Kunci (pisahkan dengan koma)')
                                ->maxLength(255),
                            TextInput::make('og_image')
                                ->label('URL Gambar Open Graph')
                                ->maxLength(255),
                            KeyValue::make('metadata')
                                ->label('Metadata Tambahan')
                                ->keyLabel('Kunci')
                                ->valueLabel('Nilai')
                                ->reorderable()
                                ->nullable(),
                        ]),
                ]),
        ]);
    }
}
