<x-filament-panels::page>
    <div class="space-y-6">
        <form wire:submit.prevent="saveSettings" class="space-y-6">
            {{ $this->form }}

            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <x-filament::button
                    type="submit"
                    icon="heroicon-o-check-circle"
                    wire:target="saveSettings"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="saveSettings">
                        Simpan Pengaturan
                    </span>

                    <span
                        wire:loading.flex
                        wire:target="saveSettings"
                        class="items-center gap-2"
                    >
                        <x-filament::loading-indicator class="h-4 w-4" />
                        <span>Menyimpan...</span>
                    </span>
                </x-filament::button>

                <div class="flex flex-col items-end gap-2">
                    <x-filament::button
                        type="button"
                        icon="heroicon-o-arrow-path"
                        wire:click="triggerBackup"
                        wire:target="triggerBackup"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="triggerBackup">
                            Jalankan Backup Sekarang
                        </span>

                        <span
                            wire:loading.flex
                            wire:target="triggerBackup"
                            class="items-center gap-2"
                        >
                            <x-filament::loading-indicator class="h-4 w-4" />
                            <span>Memproses backup...</span>
                        </span>
                    </x-filament::button>

                    <div
                        wire:loading.flex
                        wire:target="triggerBackup"
                        class="items-center gap-2 text-sm text-primary-600 dark:text-primary-400"
                    >
                        <x-filament::loading-indicator class="h-3 w-3" />
                        <span>Sedang menjalankan backup, mohon tunggu...</span>
                    </div>
                </div>
            </div>
        </form>

        @if ($lastOutput)
            <x-filament::section>
                <x-slot name="heading">
                    Log Terakhir
                </x-slot>

                <pre class="whitespace-pre-wrap rounded-lg bg-gray-900 p-4 text-sm text-gray-100 dark:bg-gray-950">
{{ $lastOutput }}
                </pre>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
