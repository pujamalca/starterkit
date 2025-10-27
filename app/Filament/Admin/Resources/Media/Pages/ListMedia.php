<?php

namespace App\Filament\Admin\Resources\Media\Pages;

use App\Filament\Admin\Resources\Media\MediaResource;
use App\Models\MediaLibraryItem;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\FileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ListMedia extends ListRecords
{
    protected static string $resource = MediaResource::class;

    protected static ?string $title = 'Media Library';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('upload')
                ->label('Upload Media')
                ->icon('heroicon-o-cloud-arrow-up')
                ->color('primary')
                ->form([
                    FileUpload::make('files')
                        ->label('File')
                        ->multiple()
                        ->required()
                        ->preserveFilenames()
                        ->helperText('Pilih satu atau beberapa file untuk menambahkannya ke library.')
                        ->acceptedFileTypes(['image/*', 'video/*', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                        ->storeFiles(false),
                ])
                ->action(function (array $data): void {
                    $files = $data['files'] ?? [];

                    if (blank($files)) {
                        return;
                    }

                    $library = MediaLibraryItem::firstOrCreate(['name' => 'Library']);

                    foreach ($files as $file) {
                        if (! $file instanceof TemporaryUploadedFile) {
                            continue;
                        }

                        $library
                            ->addMedia($file)
                            ->usingName($file->getClientOriginalName())
                            ->usingFileName($file->getClientOriginalName())
                            ->toMediaCollection('library');
                    }

                    $this->dispatch('$refresh');

                    Notification::make()
                        ->title('Upload media berhasil')
                        ->success()
                        ->send();
                }),
            Action::make('refresh')
                ->label('Muat Ulang')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(fn () => $this->dispatch('$refresh')),
        ];
    }
}
