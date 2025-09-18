{{--
    Info item component for displaying label-value pairs
    Usage:
    <x-filament.info-item label="Status" value="Enabled" type="enabled" />
    <x-filament.info-item label="Environment" value="local" type="local" />

    Types: enabled, disabled, production, local, or leave empty for default
--}}

@props(['label', 'value', 'type' => null])

<div class="fi-info-item">
    <div class="fi-info-label">{{ $label }}</div>
    <div class="fi-info-value">
        @if($type)
            <span class="fi-status-{{ $type }}">{{ $value }}</span>
        @else
            {{ $value }}
        @endif
    </div>
</div>