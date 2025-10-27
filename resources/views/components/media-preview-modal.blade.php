@props(['media'])

<x-filament::modal id="media-preview-modal">
    @include('filament.admin.media.preview', ['media' => ])
</x-filament::modal>
