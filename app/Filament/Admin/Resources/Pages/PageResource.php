<?php

namespace App\Filament\Admin\Resources\Pages;

use App\Filament\Admin\Resources\Pages\PageResource\Pages\CreatePage;
use App\Filament\Admin\Resources\Pages\PageResource\Pages\EditPage;
use App\Filament\Admin\Resources\Pages\PageResource\Pages\ListPages;
use App\Models\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use UnitEnum;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationLabel = 'Halaman Statis';

    protected static UnitEnum|string|null $navigationGroup = 'Konten';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-duplicate';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Page Tabs')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Konten')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Grid::make(2)->schema([
                                \Filament\Forms\Components\TextInput::make('title')
                                    ->label('Judul')
                                    ->required()
                                    ->maxLength(200)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                                \Filament\Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(200)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Slug digunakan pada URL. Pastikan unik.'),
                            ]),
                            \Filament\Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'draft' => 'Draft',
                                    'published' => 'Dipublikasikan',
                                    'scheduled' => 'Terjadwal',
                                ])
                                ->default('draft')
                                ->required(),

                            // Tabs untuk Content: Visual Editor dan HTML Source
                            Tabs::make('content_tabs')
                                ->columnSpanFull()
                                ->contained(false)
                                ->tabs([
                                    Tab::make('Editor Visual')
                                        ->icon('heroicon-o-document-text')
                                        ->schema([
                                            \Filament\Forms\Components\RichEditor::make('content')
                                                ->label('Konten')
                                                ->columnSpanFull()
                                                ->fileAttachmentsDisk('public')
                                                ->fileAttachmentsDirectory('pages/editor')
                                                ->required()
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn (?string $state, callable $set) => $set('content_html', $state))
                                                ->afterStateHydrated(fn ($state, callable $set) => $set('content_html', $state))
                                                ->extraInputAttributes([
                                                    'style' => 'min-height: 30rem; max-height: 60vh; overflow-y: auto;'
                                                ])
                                                ->toolbarButtons([
                                                    'attachFiles',
                                                    'blockquote',
                                                    'bold',
                                                    'bulletList',
                                                    'codeBlock',
                                                    'h2',
                                                    'h3',
                                                    'italic',
                                                    'link',
                                                    'orderedList',
                                                    'redo',
                                                    'strike',
                                                    'table',
                                                    'underline',
                                                    'undo',
                                                ]),
                                        ]),
                                    Tab::make('HTML Source')
                                        ->icon('heroicon-o-code-bracket')
                                        ->schema([
                                            \Filament\Forms\Components\Textarea::make('content_html')
                                                ->label('HTML Code')
                                                ->rows(25)
                                                ->columnSpanFull()
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn (?string $state, callable $set) => $set('content', $state))
                                                ->dehydrated(false)
                                                ->helperText('Edit HTML secara langsung. Perubahan akan tersinkronisasi dengan Editor Visual.')
                                                ->extraAttributes([
                                                    'style' => 'font-family: monospace; font-size: 0.9rem;'
                                                ]),
                                        ]),
                                ]),
                        ]),
                    Tab::make('Publikasi')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            Grid::make(2)->schema([
                                \Filament\Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Tanggal Publikasi')
                                    ->seconds(false)
                                    ->native(false)
                                    ->helperText('Tanggal halaman dipublikasikan.'),
                                \Filament\Forms\Components\DateTimePicker::make('scheduled_at')
                                    ->label('Jadwalkan Publikasi')
                                    ->seconds(false)
                                    ->native(false)
                                    ->helperText('Isi jika status diset menjadi Terjadwal.'),
                            ]),
                        ]),
                    Tab::make('SEO & Metadata')
                        ->icon('heroicon-o-magnifying-glass')
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('seo_title')
                                ->label('Judul SEO')
                                ->maxLength(60)
                                ->helperText('Maksimal 60 karakter untuk SEO optimal.'),
                            \Filament\Forms\Components\Textarea::make('seo_description')
                                ->label('Deskripsi SEO')
                                ->rows(3)
                                ->maxLength(160)
                                ->helperText('Maksimal 160 karakter untuk SEO optimal.'),
                            \Filament\Forms\Components\TagsInput::make('seo_keywords')
                                ->label('Kata Kunci SEO')
                                ->separator(',')
                                ->helperText('Tekan Enter atau koma untuk menambahkan tag. Contoh: halaman utama, tentang kami, kontak'),
                            Grid::make(2)->schema([
                                \Filament\Forms\Components\TextInput::make('canonical_url')
                                    ->label('Canonical URL')
                                    ->url()
                                    ->maxLength(2048)
                                    ->helperText('URL kanonik untuk halaman ini.'),
                                \Filament\Forms\Components\TextInput::make('og_image')
                                    ->label('URL Gambar Open Graph')
                                    ->url()
                                    ->maxLength(255)
                                    ->helperText('Gambar untuk social media sharing.'),
                            ]),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->copyable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'draft',
                        'primary' => 'scheduled',
                        'success' => 'published',
                    ])
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'draft' => 'Draft',
                        'scheduled' => 'Terjadwal',
                        'published' => 'Dipublikasikan',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Dipublikasikan')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Penulis')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Terjadwal',
                        'published' => 'Dipublikasikan',
                    ]),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (Page $record) => route('pages.preview', $record))
                    ->openUrlInNewTab(),
                \Filament\Actions\Action::make('publish')
                    ->label('Publikasikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn (Page $record) => $record->status === 'published')
                    ->requiresConfirmation()
                    ->action(function (Page $record): void {
                        $record->update([
                            'status' => 'published',
                            'published_at' => now(),
                            'scheduled_at' => null,
                        ]);
                    }),
                \Filament\Actions\Action::make('draft')
                    ->label('Jadikan Draft')
                    ->icon('heroicon-o-document')
                    ->color('warning')
                    ->hidden(fn (Page $record) => $record->status === 'draft')
                    ->requiresConfirmation()
                    ->action(function (Page $record): void {
                        $record->update([
                            'status' => 'draft',
                        ]);
                    }),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('author');
    }
}
