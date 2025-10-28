@php
    $checks = collect($report['checks'] ?? []);
    $analytics = $report['analytics'] ?? [];
    $versions = $report['versions'] ?? [];
    $timestamp = $report['timestamp'] ?? now();

    $statusSummary = $checks->map(fn ($check) => strtolower($check['status'] ?? 'unknown'));
    $groupedStatus = $statusSummary->countBy();
    $overallStatus = 'ok';

    if ($groupedStatus->get('error')) {
        $overallStatus = 'error';
    } elseif ($groupedStatus->get('warning')) {
        $overallStatus = 'warning';
    }

    $statusPalette = [
        'ok' => 'bg-emerald-500 text-white ring-emerald-200',
        'warning' => 'bg-amber-500 text-white ring-amber-200',
        'error' => 'bg-rose-500 text-white ring-rose-200',
        'unknown' => 'bg-slate-500 text-white ring-slate-200',
    ];

    $overallPalette = $statusPalette[$overallStatus] ?? $statusPalette['unknown'];

    $analyticsGroups = [
        'Konten' => [
            'posts_total' => 'Total Post',
            'posts_published' => 'Post Terpublikasi',
            'posts_last_7_days' => 'Post 7 Hari Terakhir',
            'pages_total' => 'Total Halaman',
            'pages_published' => 'Halaman Terpublikasi',
        ],
        'Pengguna & Interaksi' => [
            'users_total' => 'Total Pengguna',
            'users_active' => 'Pengguna Aktif',
            'comments_total' => 'Total Komentar',
            'comments_pending' => 'Komentar Pending',
        ],
    ];

    $statusLegend = [
        ['label' => 'OK', 'color' => 'bg-emerald-500', 'count' => $groupedStatus->get('ok', 0)],
        ['label' => 'Warning', 'color' => 'bg-amber-500', 'count' => $groupedStatus->get('warning', 0)],
        ['label' => 'Error', 'color' => 'bg-rose-500', 'count' => $groupedStatus->get('error', 0)],
    ];
@endphp

<x-filament-panels::page>
    <div class="space-y-12">
        <div class="rounded-3xl border border-slate-200 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-8 shadow-xl ring-1 ring-white/10 dark:border-slate-800 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-3">
                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-800/70 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide text-slate-200 ring-1 ring-white/20">
                        <span class="size-2 rounded-full bg-rose-400/90 shadow"></span>
                        Doctor Website
                    </span>
                    <div>
                        <h2 class="text-3xl font-semibold tracking-tight text-white md:text-4xl">
                            Ringkasan Kesehatan Aplikasi
                        </h2>
                        <div class="mt-2 flex flex-col gap-2 text-sm text-slate-300 md:flex-row md:items-center md:gap-4">
                            <span class="inline-flex items-center gap-2">
                                <span class="size-1.5 rounded-full bg-emerald-400/80"></span>
                                Pemeriksaan terakhir:
                                <span class="font-medium text-white">
                                    @if ($timestamp instanceof \Illuminate\Support\Carbon)
                                        {{ $timestamp->translatedFormat('d F Y H:i:s') }}
                                    @else
                                        {{ $timestamp }}
                                    @endif
                                </span>
                            </span>
                            <span class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-slate-400">
                                <span class="h-1.5 w-16 rounded-full bg-gradient-to-r from-slate-500 via-slate-200 to-transparent"></span>
                                Snapshot Real-time
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-start gap-4 md:flex-row md:items-end md:gap-6 lg:flex-col lg:items-end">
                    <span class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold uppercase tracking-wide shadow {{ $overallPalette }}">
                        <span class="size-2 rounded-full bg-white/70"></span>
                        {{ strtoupper($overallStatus) }}
                    </span>
                    <div class="grid grid-cols-3 gap-3 text-xs font-medium text-slate-200">
                        @foreach ($statusLegend as $item)
                            <div class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-center backdrop-blur">
                                <div class="text-lg font-semibold text-white">
                                    {{ $item['count'] }}
                                </div>
                                <div class="flex items-center justify-center gap-1 text-[10px] uppercase tracking-wider text-slate-300">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $item['color'] }}"></span>
                                    {{ $item['label'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                    Health Check
                </h3>
                <p class="text-xs text-slate-400">
                    Memantau status koneksi utama dan performa dasar sistem.
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white/80 p-4 shadow-sm ring-1 ring-slate-100 dark:border-slate-700 dark:bg-slate-900/80 dark:ring-slate-800/80">
                <livewire:filament.admin.widgets.doctor-latency-chart />
            </div>

            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($checks as $key => $check)
                    @php
                        $status = strtoupper((string) ($check['status'] ?? 'UNKNOWN'));
                        $badgeClasses = $this->statusColor($check['status'] ?? 'unknown');
                    @endphp

                    <div class="group relative overflow-hidden rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900/80">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/40 via-white/5 to-transparent opacity-0 transition-opacity group-hover:opacity-100 dark:from-slate-500/10 dark:via-transparent"></div>
                        <div class="relative flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    Komponen
                                </span>
                                <span class="text-base font-semibold text-slate-800 dark:text-slate-100">
                                    {{ \Illuminate\Support\Str::headline((string) $key) }}
                                </span>
                            </div>
                            <span class="rounded-full px-3 py-1 text-2xs font-semibold {{ $badgeClasses }}">
                                {{ $status }}
                            </span>
                        </div>

                        <dl class="relative mt-5 space-y-3 text-sm">
                            @foreach ($check as $attribute => $value)
                                @continue($attribute === 'status')

                                <div class="flex items-start justify-between gap-x-3 border-b border-slate-100 pb-2 last:border-none last:pb-0 dark:border-slate-700/60">
                                    <dt class="text-slate-500 dark:text-slate-400">
                                        {{ \Illuminate\Support\Str::headline((string) $attribute) }}
                                    </dt>
                                    <dd class="text-right font-medium text-slate-900 dark:text-slate-100">
                                        @php
                                            $display = match (true) {
                                                is_bool($value) => $value ? 'Ya' : 'Tidak',
                                                is_numeric($value) => (string) $value,
                                                blank($value) => 'N/A',
                                                default => (string) $value,
                                            };
                                        @endphp
                                        {{ $display }}
                                    </dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                    Analitik Cepat
                </h3>
                <p class="text-xs text-slate-400">
                    Ikhtisar pertumbuhan konten dan keterlibatan pengguna, diperbarui setiap refresh.
                </p>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                @foreach ($analyticsGroups as $section => $metrics)
                    <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900/80 dark:ring-slate-800/80">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                                {{ $section }}
                            </h4>
                            <span class="text-2xs uppercase tracking-widest text-slate-400 dark:text-slate-500">
                                Snapshot
                            </span>
                        </div>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            @foreach ($metrics as $key => $label)
                                <div class="rounded-2xl border border-slate-100 bg-white/70 px-4 py-3 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                                    <dt class="text-2xs font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        {{ $label }}
                                    </dt>
                                    <dd class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">
                                        {{ number_format((int) ($analytics[$key] ?? 0)) }}
                                    </dd>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                    Versi Dependensi
                </h3>
                <p class="text-xs text-slate-400">
                    Pastikan aplikasi berjalan di atas versi framework dan paket terbaru.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($versions as $package => $version)
                    <div class="rounded-3xl border border-slate-200 bg-white/80 px-5 py-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900/80">
                        <div class="flex items-center justify-between">
                            <div class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                                {{ \Illuminate\Support\Str::headline((string) $package) }}
                            </div>
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500/80"></span>
                        </div>
                        <div class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">
                            {{ $version }}
                        </div>
                        <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                            {{ \Illuminate\Support\Str::headline((string) $package) }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>
