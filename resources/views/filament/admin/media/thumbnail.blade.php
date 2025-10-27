@php
    use Illuminate\Support\Str;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;

    /** @var Media  */
     = Str::startsWith((string) ->mime_type, 'image');
     = Str::startsWith((string) ->mime_type, 'video')
        ? 'heroicon-o-video-camera'
        : 'heroicon-o-document-text';
@endphp

<div class="flex items-center justify-center">
    @if ()
        <img
            src="{{ ->getFullUrl() }}"
            alt="{{ ->name ?? ->file_name }}"
            class="h-16 w-16 rounded-lg object-cover"
            loading="lazy"
        >
    @else
        <x-filament::icon
            :icon=""
            class="h-12 w-12 text-gray-400"
        />
    @endif
</div>
