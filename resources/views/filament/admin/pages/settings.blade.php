<x-filament-panels::page>
    {{-- Load Filament page assets --}}
    <x-filament.page-assets />

    <div class="space-y-6">
        {{-- Application Settings --}}
        <x-filament.info-card title="Application Settings">
            <x-filament.info-item
                label="Application Name"
                value="{{ config('app.name') }}"
            />
            <x-filament.info-item
                label="Environment"
                value="{{ config('app.env') }}"
                type="{{ config('app.env') === 'production' ? 'production' : 'local' }}"
            />
            <x-filament.info-item
                label="Debug Mode"
                value="{{ config('app.debug') ? 'Enabled' : 'Disabled' }}"
                type="{{ config('app.debug') ? 'enabled' : 'disabled' }}"
            />
            <x-filament.info-item
                label="Laravel Version"
                value="{{ app()->version() }}"
            />
        </x-filament.info-card>

        {{-- Database Information --}}
        <x-filament.info-card title="Database Information">
            <x-filament.info-item
                label="Connection"
                value="{{ config('database.default') }}"
            />
            <x-filament.info-item
                label="Database"
                value="{{ config('database.connections.' . config('database.default') . '.database') }}"
            />
        </x-filament.info-card>

        {{-- System Information --}}
        <x-filament.info-card title="System Information">
            <x-filament.info-item
                label="PHP Version"
                value="{{ PHP_VERSION }}"
            />
            <x-filament.info-item
                label="Timezone"
                value="{{ config('app.timezone') }}"
            />
        </x-filament.info-card>
    </div>
</x-filament-panels::page>
