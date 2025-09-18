{{--
    Reusable info card component for Filament pages
    Usage:
    <x-filament.info-card title="Card Title">
        <x-filament.info-item label="Label" value="Value" />
        <x-filament.info-item label="Status" value="Enabled" type="enabled" />
    </x-filament.info-card>
--}}

@props(['title'])

<div class="fi-page-card">
    <div class="fi-page-card-header">
        <h2 class="fi-page-card-title">{{ $title }}</h2>
    </div>
    <div class="fi-page-card-body">
        <div class="fi-info-grid">
            {{ $slot }}
        </div>
    </div>
</div>