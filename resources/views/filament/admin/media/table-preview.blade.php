@php
    use Spatie\MediaLibrary\MediaCollections\Models\Media;

    /** @var Media  */
@endphp

<x-filament::button
    color="gray"
    icon="heroicon-o-eye"
    wire:click="dispatch('openModal', {\n        component: 'filament.admin.media.preview',\n        arguments: { media: {{ ->getKey() }} }\n    })"
>
    Pratinjau
</x-filament::button>
