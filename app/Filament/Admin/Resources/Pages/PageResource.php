<?php

namespace App\Filament\Admin\Resources\Pages;

use App\Filament\Admin\Resources\Pages\PageResource\Pages\CreatePage;
use App\Filament\Admin\Resources\Pages\PageResource\Pages\EditPage;
use App\Filament\Admin\Resources\Pages\PageResource\Pages\ListPages;
use App\Models\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Actions;
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
                    ->unique(ignoreRecord: true),
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
            \Filament\Forms\Components\RichEditor::make('content')
                ->label('Konten')
                ->columnSpanFull()
                ->fileAttachmentsDisk('public')
                ->fileAttachmentsDirectory('pages/editor')
                ->nullable(),
            Grid::make(2)->schema([
                \Filament\Forms\Components\DateTimePicker::make('published_at')
                    ->label('Tanggal Publikasi')
                    ->seconds(false)
                    ->native(false),
                \Filament\Forms\Components\DateTimePicker::make('scheduled_at')
                    ->label('Jadwalkan')
                    ->seconds(false)
                    ->native(false),
            ]),
            Grid::make(2)->schema([
                \Filament\Forms\Components\TextInput::make('seo_title')
                    ->label('Judul SEO')
                    ->maxLength(60),
                \Filament\Forms\Components\TextInput::make('seo_keywords')
                    ->label('Kata Kunci (pisahkan dengan koma)')
                    ->maxLength(255),
            ]),
            \Filament\Forms\Components\Textarea::make('seo_description')
                ->label('Deskripsi SEO')
                ->rows(3)
                ->maxLength(160),
            \Filament\Forms\Components\TextInput::make('canonical_url')
                ->label('Canonical URL')
                ->url()
                ->maxLength(2048),
            \Filament\Forms\Components\TextInput::make('og_image')
                ->label('URL Open Graph')
                ->url()
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->label('Slug')->copyable()->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'draft',
                        'primary' => 'scheduled',
                        'success' => 'published',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')->label('Dipublikasikan')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('author.name')->label('Penulis')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Diperbarui')->dateTime()->since(),
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
            ->actions([
                Actions\EditAction::make(),
                Actions\Action::make('publish')
                    ->label('Publikasikan')
                    ->icon('heroicon-o-check')
                    ->hidden(fn (Page $record) => $record->status === 'published')
                    ->requiresConfirmation()
                    ->action(function (Page $record): void {
                        $record->update([
                            'status' => 'published',
                            'published_at' => now(),
                            'scheduled_at' => null,
                        ]);
                    }),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
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
