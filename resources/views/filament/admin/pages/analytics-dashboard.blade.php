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
            'description' => 'Rata-rata view per konten terbit.',
        ],
        [
            'title' => 'Post Terpublikasi',
            'value' => number_format((int) $summary->get('posts_published', 0)),
            'description' => 'Jumlah konten aktif yang tayang.',
        ],
        [
            'title' => 'Pengguna Aktif',
            'value' => number_format((int) $summary->get('active_users', 0)),
            'description' => 'User yang masih memiliki akses aktif.',
        ],
    ];
@endphp

<x-filament-panels::page>
    <div class="space-y-10">
        <div class="rounded-3xl border border-gray-200 bg-gray-900 p-8 text-gray-100 shadow-xl dark:border-gray-700 dark:bg-gray-950">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-2">
                    <span class="inline-flex items-center gap-2 rounded-full bg-gray-800 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide text-gray-200">
                        <span class="size-2 rounded-full bg-sky-400"></span>
                        Analytics
                    </span>
                    <div>
                        <h2 class="text-3xl font-semibold tracking-tight">
                            Insight Performa Konten & Pengguna
                        </h2>
                        <p class="mt-2 text-sm text-gray-300">
                            Pantau pertumbuhan konten, interaksi komunitas, dan konten terpopuler menggunakan ringkasan ini.
                        </p>
                    </div>
                </div>
                <div class="grid gap-3 text-xs font-medium text-gray-200 sm:grid-cols-3">
                    <div class="rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-center">
                        <div class="text-lg font-semibold text-gray-100">
                            {{ number_format((int) $summary->get('total_comments', 0)) }}
                        </div>
                        <div>Total Komentar</div>
                    </div>
                    <div class="rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-center">
                        <div class="text-lg font-semibold text-gray-100">
                            {{ number_format((int) $summary->get('new_users_30_days', 0)) }}
                        </div>
                        <div>User Baru 30 Hari</div>
                    </div>
                    <div class="rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-center">
                        <div class="text-lg font-semibold text-gray-100">
                            {{ $rates['views_per_published_post'] ?? 0 }}
                        </div>
                        <div>Views / Post Publish</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                    Tren Aktivitas 30 Hari
                </h3>
                <span class="text-xs text-gray-400">
                    Visualisasi posts, komentar, dan user baru dalam 30 hari terakhir.
                </span>
            </div>
            <div class="mt-4">
                <livewire:filament.admin.widgets.analytics-trends-chart />
            </div>
        </div>

        <div>
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Ringkasan Cepat
            </h3>
            <div class="mt-4 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($summaryCards as $card)
                    <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-gray-700 dark:bg-gray-900">
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            {{ $card['title'] }}
                        </h4>
                        <div class="mt-3 text-3xl font-semibold text-gray-900 dark:text-white">
                            {{ $card['value'] }}
                        </div>
                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                            {{ $card['description'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Rincian Aktivitas
                </h3>
                <div class="mt-5">
                    @if (empty($trends))
                        <p class="text-sm text-gray-400 dark:text-gray-500">
                            Belum ada data aktivitas dalam 30 hari terakhir.
                        </p>
                    @else
                        <div class="overflow-hidden rounded-2xl border border-gray-100 dark:border-gray-800">
                            <table class="w-full min-w-full divide-y divide-gray-100 text-xs dark:divide-gray-800">
                                <thead class="bg-gray-50 dark:bg-gray-900/60">
                                    <tr class="text-left text-gray-500 dark:text-gray-400">
                                        <th class="px-4 py-2 font-medium">Tanggal</th>
                                        <th class="px-4 py-2 font-medium">Posts</th>
                                        <th class="px-4 py-2 font-medium">Comments</th>
                                        <th class="px-4 py-2 font-medium">Users</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @foreach ($trends as $row)
                                        <tr class="text-gray-600 dark:text-gray-300">
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

            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Engagement
                </h3>
                <div class="mt-5 space-y-6 text-sm">
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            Status Post
                        </h4>
                        <div class="mt-3 space-y-2">
                            @foreach (($engagement['posts'] ?? []) as $label => $value)
                                <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white px-3 py-2 dark:border-gray-800 dark:bg-gray-900/60">
                                    <span class="capitalize text-gray-600 dark:text-gray-300">
                                        {{ str_replace('_', ' ', $label) }}
                                    </span>
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ number_format($value) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            Komentar
                        </h4>
                        <div class="mt-3 space-y-2">
                            @foreach (($engagement['comments'] ?? []) as $label => $value)
                                <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white px-3 py-2 dark:border-gray-800 dark:bg-gray-900/60">
                                    <span class="capitalize text-gray-600 dark:text-gray-300">
                                        {{ str_replace('_', ' ', $label) }}
                                    </span>
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ number_format($value) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-300">
                            Comment Conversion Rate: <span class="font-semibold">{{ $rates['comment_conversion_rate'] ?? 0 }}%</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Top Performing Posts
                </h3>
                <span class="text-xs text-gray-400">
                    Diurutkan berdasarkan jumlah view terbanyak.
                </span>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-900/60">
                        <tr class="text-left text-gray-500 dark:text-gray-400">
                            <th class="px-4 py-3 font-medium">Judul</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 text-right font-medium">Views</th>
                            <th class="px-4 py-3 text-right font-medium">Publikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($topPosts as $post)
                            <tr class="text-gray-600 dark:text-gray-300">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                    {{ $post['title'] }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-2xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-800 dark:text-gray-300">
                                        {{ strtoupper($post['status']) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">
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
                                <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-400">
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

