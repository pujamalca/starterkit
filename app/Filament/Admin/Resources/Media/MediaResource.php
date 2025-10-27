<?php

namespace App\Filament\Admin\Resources\Media;

use App\Filament\Admin\Resources\Media\Pages\ListMedia;
use App\Models\MediaLibraryItem;
use BackedEnum;
use UnitEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $navigationLabel = 'Media';

    protected static UnitEnum|string|null $navigationGroup = 'Konten';

    public static function table(Table $table): Table
    {
        return $table
            ->recordAction(null)
            ->recordUrl(null)
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('model_type', MediaLibraryItem::class))
            ->columns([
                TextColumn::make('file_name')
                    ->label('Nama File')
                    ->searchable(isIndividual: true, isGlobal: true)
                    ->sortable()
                    ->description(fn (Media $record): string => $record->name ?? ''),
                TextColumn::make('preview')
                    ->label('Preview')
                    ->state(fn (): string => 'Lihat')
                    ->color('primary')
                    ->action(
                        Action::make('preview')
                            ->label('Pratinjau')
                            ->icon('heroicon-o-eye')
                            ->modalHeading('Pratinjau Media')
                            ->modalContent(fn (Media $record) => view('filament.admin.media.preview', ['media' => $record]))
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Tutup')
                    ),
                BadgeColumn::make('collection_name')
                    ->label('Koleksi')
                    ->colors([
                        'primary',
                    ])
                    ->icon('heroicon-o-rectangle-stack')
                    ->formatStateUsing(fn (?string $state) => $state ?? 'default'),
                TextColumn::make('mime_type')
                    ->label('Tipe')
                    ->searchable()
                    ->toggleable()
                    ->icon(fn (Media $record): string => Str::startsWith((string) $record->mime_type, 'image') ? 'heroicon-m-photo' : 'heroicon-m-document-text'),
                TextColumn::make('size')
                    ->label('Ukuran')
                    ->state(fn (Media $record) => Number::fileSize((int) $record->size))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Diunggah')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->contentGrid([
                'sm' => 2,
                'md' => 3,
                'lg' => 4,
            ])
            ->filters([
                SelectFilter::make('collection_name')
                    ->label('Koleksi')
                    ->options(fn () => Media::query()
                        ->select('collection_name')
                        ->distinct()
                        ->pluck('collection_name', 'collection_name')
                        ->filter()
                        ->all()),
                Filter::make('images_only')
                    ->label('Hanya Gambar')
                    ->toggle()
                    ->query(fn (Builder $query) => $query->where('mime_type', 'like', 'image/%')),
            ])
            ->actions([
                Action::make('download')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Media $record): string => $record->getFullUrl())
                    ->openUrlInNewTab()
                    ->color('gray'),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedia::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'file_name',
            'name',
            'collection_name',
            'mime_type',
        ];
    }

    protected static function deriveFallbackIcon(Media $record): string
    {
        return Str::startsWith((string) $record->mime_type, 'video')
            ? asset('vendor/filament/support/svg/video.svg')
            : asset('vendor/filament/support/svg/document-text.svg');
    }
}
