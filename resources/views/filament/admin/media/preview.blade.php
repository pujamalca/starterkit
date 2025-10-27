@php
    use Illuminate\Support\Str;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;

    /** @var Media $media */
    $isImage = Str::startsWith((string) $media->mime_type, 'image');
@endphp

<div
    x-data="{
        showPreview: false,
        async copyUrl(url) {
            try {
                await navigator.clipboard.writeText(url);
                window.dispatchEvent(new CustomEvent('filament-notify', {
                    detail: { status: 'success', message: 'URL berhasil disalin.' },
                }));
            } catch (error) {
                window.dispatchEvent(new CustomEvent('filament-notify', {
                    detail: { status: 'danger', message: 'Gagal menyalin URL.' },
                }));
            }
        },
    }"
    class="space-y-6"
>
    <div class="space-y-5 rounded-xl border border-gray-200/70 bg-white/95 p-6 shadow-sm dark:border-gray-700/60 dark:bg-gray-900/80">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">
                    Informasi Media
                </p>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ $media->name ?? $media->file_name }}
                </h2>
            </div>

            <div class="flex gap-2">
                <x-filament::button
                    color="gray"
                    size="sm"
                    icon="heroicon-o-clipboard-document"
                    x-on:click="copyUrl(@js($media->getFullUrl()))"
                >
                    Salin URL
                </x-filament::button>

                <x-filament::button
                    color="primary"
                    size="sm"
                    icon="heroicon-o-arrow-up-on-square"
                    tag="a"
                    href="{{ $media->getFullUrl() }}"
                    target="_blank"
                    rel="noopener"
                >
                    Buka di Tab Baru
                </x-filament::button>
            </div>
        </div>

        <dl class="grid gap-3 text-sm md:grid-cols-2">
            <div class="rounded-lg border border-dashed border-gray-200/70 bg-gray-50/70 px-4 py-3 dark:border-gray-700/60 dark:bg-gray-800/60">
                <dt class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Nama File</dt>
                <dd class="mt-1 font-medium text-gray-900 dark:text-gray-100">{{ $media->file_name }}</dd>
            </div>

            <div class="rounded-lg border border-dashed border-gray-200/70 bg-gray-50/70 px-4 py-3 dark:border-gray-700/60 dark:bg-gray-800/60">
                <dt class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Tipe</dt>
                <dd class="mt-1 text-gray-800 dark:text-gray-100">{{ $media->mime_type ?? 'Tidak diketahui' }}</dd>
            </div>

            <div class="rounded-lg border border-dashed border-gray-200/70 bg-gray-50/70 px-4 py-3 md:col-span-2 dark:border-gray-700/60 dark:bg-gray-800/60">
                <dt class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">URL Publik</dt>
                <dd class="mt-2 break-all text-gray-800 dark:text-gray-100">
                    <a
                        href="{{ $media->getFullUrl() }}"
                        class="inline-flex items-center gap-2 font-medium text-primary-600 transition hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300"
                        target="_blank"
                        rel="noopener"
                    >
                        <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="h-4 w-4" />
                        {{ $media->getFullUrl() }}
                    </a>
                </dd>
            </div>
        </dl>
    </div>

    <div class="rounded-xl border border-dashed border-gray-200/70 bg-gray-50/80 p-6 text-center dark:border-gray-700/60 dark:bg-gray-900/70">
        @if ($isImage)
            <div class="space-y-4">
                <x-filament::button
                    color="primary"
                    x-show="! showPreview"
                    x-on:click="showPreview = true"
                    icon="heroicon-o-eye"
                >
                    Tampilkan Gambar
                </x-filament::button>

                <div x-show="showPreview" x-transition x-cloak>
                    <img
                        src="{{ $media->getFullUrl() }}"
                        alt="{{ $media->name ?? $media->file_name }}"
                        class="max-h-[420px] w-full rounded-xl border border-gray-200/70 object-contain shadow-sm dark:border-gray-700/60"
                        loading="lazy"
                    >
                </div>
            </div>
        @else
            <div class="text-sm text-gray-500 dark:text-gray-400">
                <x-filament::icon icon="heroicon-o-document-text" class="mx-auto mb-2 h-10 w-10 text-gray-400" />
                Konten non-gambar tidak memiliki pratinjau bawaan.
            </div>
        @endif
    </div>
</div>
