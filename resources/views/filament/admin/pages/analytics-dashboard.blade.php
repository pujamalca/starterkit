@php
    $summary = collect($summary ?? []);
    $engagement = $engagement ?? [];
    $rates = $rates ?? [];
    $trends = $trends ?? [];
    $topPosts = $topPosts ?? [];

    $summaryCards = [
        [
            'title' => 'Total Views',
            'value' => number_format((int) $summary->get('total_views', 0)),
            'description' => 'Akumulasi seluruh view konten.',
        ],
        [
            'title' => 'Rata-rata View / Post',
            'value' => number_format((float) $summary->get('avg_views_per_post', 0), 1),
            'description' => 'Rata-rata view per konten.',
        ],
        [
            'title' => 'Publikasi 30 Hari',
            'value' => number_format((int) $summary->get('posts_published', 0)),
            'description' => 'Jumlah konten publish aktif.',
        ],
        [
            'title' => 'Pengguna Aktif',
            'value' => number_format((int) $summary->get('active_users', 0)),
            'description' => 'User aktif yang dapat berinteraksi.',
        ],
    ];

    $trendChartData = [
        'labels' => collect($trends)->pluck('date')->all(),
        'posts' => collect($trends)->pluck('posts')->all(),
        'comments' => collect($trends)->pluck('comments')->all(),
        'users' => collect($trends)->pluck('users')->all(),
    ];
@endphp

<x-filament-panels::page>
    <div class="space-y-10">
        <div class="rounded-3xl border border-slate-200 bg-gradient-to-br from-sky-600 via-sky-700 to-sky-900 p-8 shadow-xl ring-1 ring-sky-500/40 dark:border-slate-800 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-2">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide text-sky-100 ring-1 ring-white/20">
                        <span class="size-2 rounded-full bg-sky-300 shadow"></span>
                        Analytics
                    </span>
                    <div>
                        <h2 class="text-3xl font-semibold tracking-tight text-white">
                            Insight Kinerja Konten & Pengguna
                        </h2>
                        <p class="mt-1 text-sm text-sky-100/80">
                            Pantau pertumbuhan konten, keterlibatan pengguna, dan performa post populer.
                        </p>
                    </div>
                </div>
                <div class="grid gap-3 text-xs font-medium text-sky-100 sm:grid-cols-3">
                    <div class="rounded-xl border border-white/10 bg-white/10 px-3 py-2 text-center">
                        <div class="text-lg font-semibold text-white">
                            {{ number_format((int) $summary->get('total_comments', 0)) }}
                        </div>
                        <div>Total Komentar</div>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-white/10 px-3 py-2 text-center">
                        <div class="text-lg font-semibold text-white">
                            {{ number_format((int) $summary->get('new_users_30_days', 0)) }}
                        </div>
                        <div>User Baru 30 Hari</div>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-white/10 px-3 py-2 text-center">
                        <div class="text-lg font-semibold text-white">
                            {{ $rates['views_per_published_post'] ?? 0 }}
                        </div>
                        <div>Views / Post Publish</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-sm ring-1 ring-slate-100 dark:border-slate-700 dark:bg-slate-900/80 dark:ring-slate-800/80">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">
                    Tren Aktivitas
                </h3>
                <span class="text-xs text-slate-400">
                    Visualisasi pertumbuhan konten dan interaksi 30 hari terakhir.
                </span>
            </div>
            <div class="mt-4">
                <livewire:filament.admin.widgets.analytics-trends-chart />
            </div>
        </div>

        <div>
            <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                Ringkasan
            </h3>
            <div class="mt-4 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($summaryCards as $card)
                    <div class="rounded-3xl border border-slate-200 bg-white/80 p-5 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900/80 dark:ring-slate-800/80">
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            {{ $card['title'] }}
                        </h4>
                        <div class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">
                            {{ $card['value'] }}
                        </div>
                        <p class="mt-2 text-xs text-slate-400 dark:text-slate-500">
                            {{ $card['description'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-sm ring-1 ring-slate-100 dark:border-slate-700 dark:bg-slate-900/80 dark:ring-slate-800/80">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        Aktivitas 30 Hari Terakhir
                    </h3>
                    <span class="text-2xs uppercase tracking-widest text-slate-400">
                        Posts • Comments • Users
                    </span>
                </div>
                <div class="mt-5">
                    @if (empty($trendChartData['labels']))
                        <p class="text-sm text-slate-400">Belum ada data aktivitas dalam 30 hari terakhir.</p>
                    @else
                        <div class="overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800">
                            <table class="w-full min-w-full divide-y divide-slate-100 text-xs dark:divide-slate-800">
                                <thead class="bg-slate-50 dark:bg-slate-900/60">
                                    <tr class="text-left">
                                        <th class="px-4 py-2 font-medium text-slate-500">Tanggal</th>
                                        <th class="px-4 py-2 font-medium text-slate-500">Posts</th>
                                        <th class="px-4 py-2 font-medium text-slate-500">Comments</th>
                                        <th class="px-4 py-2 font-medium text-slate-500">Users</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @foreach ($trends as $row)
                                        <tr class="text-slate-600 dark:text-slate-300">
                                            <td class="px-4 py-2 font-medium">{{ $row['date'] }}</td>
                                            <td class="px-4 py-2">{{ $row['posts'] }}</td>
                                            <td class="px-4 py-2">{{ $row['comments'] }}</td>
                                            <td class="px-4 py-2">{{ $row['users'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-sm ring-1 ring-slate-100 dark:border-slate-700 dark:bg-slate-900/80 dark:ring-slate-800/80">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                    Engagement
                </h3>
                <div class="mt-5 space-y-5 text-sm">
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            Post Status
                        </h4>
                        <div class="mt-3 space-y-2">
                            @foreach (($engagement['posts'] ?? []) as $label => $value)
                                <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-white/60 px-3 py-2 dark:border-slate-800 dark:bg-slate-900/60">
                                    <span class="capitalize text-slate-600 dark:text-slate-300">
                                        {{ str_replace('_', ' ', $label) }}
                                    </span>
                                    <span class="font-semibold text-slate-900 dark:text-white">
                                        {{ number_format($value) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            Komentar
                        </h4>
                        <div class="mt-3 space-y-2">
                            @foreach (($engagement['comments'] ?? []) as $label => $value)
                                <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-white/60 px-3 py-2 dark:border-slate-800 dark:bg-slate-900/60">
                                    <span class="capitalize text-slate-600 dark:text-slate-300">
                                        {{ str_replace('_', ' ', $label) }}
                                    </span>
                                    <span class="font-semibold text-slate-900 dark:text-white">
                                        {{ number_format($value) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-300">
                            Comment Conversion Rate: <span class="font-semibold">{{ $rates['comment_conversion_rate'] ?? 0 }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                    Top Performing Posts
                </h3>
                <span class="text-xs text-slate-400">
                    Berdasarkan jumlah view terbanyak.
                </span>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white/80 shadow-sm ring-1 ring-slate-100 dark:border-slate-700 dark:bg-slate-900/80 dark:ring-slate-800/80">
                <table class="min-w-full divide-y divide-slate-100 text-sm dark:divide-slate-800">
                    <thead class="bg-slate-50 dark:bg-slate-900/60">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-slate-500">Judul</th>
                            <th class="px-4 py-3 text-left font-medium text-slate-500">Status</th>
                            <th class="px-4 py-3 text-right font-medium text-slate-500">Views</th>
                            <th class="px-4 py-3 text-right font-medium text-slate-500">Publikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($topPosts as $post)
                            <tr class="text-slate-600 dark:text-slate-300">
                                <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">
                                    {{ $post['title'] }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-2xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                                        {{ strtoupper($post['status']) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-slate-900 dark:text-white">
                                    {{ number_format($post['view_count'] ?? 0) }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @if (!empty($post['published_at']))
                                        {{ \Illuminate\Support\Carbon::parse($post['published_at'])->translatedFormat('d M Y') }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-sm text-slate-400">
                                    Belum ada data views post.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
