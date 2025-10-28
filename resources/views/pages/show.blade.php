<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($preview) && $preview ? '[PREVIEW] ' : '' }}{{ $page->seo_title ?? $page->title }}</title>
    @if ($page->seo_description)
        <meta name="description" content="{{ $page->seo_description }}">
    @endif
    @if ($page->canonical_url)
        <link rel="canonical" href="{{ $page->canonical_url }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styling untuk content */
        .prose-custom h1 {
            @apply text-4xl font-bold text-gray-900 mb-4 mt-8 first:mt-0;
        }
        .prose-custom h2 {
            @apply text-3xl font-bold text-gray-900 mb-3 mt-8;
        }
        .prose-custom h3 {
            @apply text-2xl font-semibold text-gray-800 mb-3 mt-6;
        }
        .prose-custom h4 {
            @apply text-xl font-semibold text-gray-800 mb-2 mt-4;
        }
        .prose-custom p {
            @apply text-gray-700 leading-relaxed mb-4;
        }
        .prose-custom ul {
            @apply list-disc list-inside space-y-2 mb-4 ml-4;
        }
        .prose-custom ol {
            @apply list-decimal list-inside space-y-2 mb-4 ml-4;
        }
        .prose-custom li {
            @apply text-gray-700 leading-relaxed;
        }
        .prose-custom strong {
            @apply font-semibold text-gray-900;
        }
        .prose-custom em {
            @apply italic;
        }
        .prose-custom u {
            @apply underline decoration-gray-400;
        }
        .prose-custom a {
            @apply text-blue-600 hover:text-blue-800 underline transition-colors;
        }
        .prose-custom blockquote {
            @apply border-l-4 border-blue-500 pl-4 italic text-gray-600 my-4;
        }
        .prose-custom code {
            @apply bg-gray-100 text-red-600 px-1.5 py-0.5 rounded text-sm font-mono;
        }
        .prose-custom pre {
            @apply bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto mb-4;
        }
        .prose-custom pre code {
            @apply bg-transparent text-gray-100 p-0;
        }
        .prose-custom table {
            @apply w-full border-collapse mb-4;
        }
        .prose-custom th {
            @apply bg-gray-100 border border-gray-300 px-4 py-2 text-left font-semibold text-gray-900;
        }
        .prose-custom td {
            @apply border border-gray-300 px-4 py-2 text-gray-700;
        }
        .prose-custom hr {
            @apply border-gray-300 my-8;
        }
        .prose-custom img {
            @apply rounded-lg shadow-md my-6;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen py-8 px-4">
    @if (isset($preview) && $preview)
        <div class="max-w-4xl mx-auto mb-4">
            <div class="bg-amber-50 border-l-4 border-amber-500 text-amber-900 px-6 py-4 rounded-r-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-semibold">Mode Preview</p>
                        <p class="text-sm text-amber-800">Ini adalah tampilan preview halaman. Perubahan belum dipublikasikan.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <main class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
        <header class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-12">
            <h1 class="text-4xl md:text-5xl font-bold mb-3">{{ $page->title }}</h1>

            <div class="flex flex-wrap items-center gap-4 text-blue-100">
                @if ($page->published_at)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm">{{ $page->published_at->translatedFormat('d F Y') }}</span>
                    </div>
                @endif

                @if ($page->status === 'draft')
                    <span class="bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        Draft
                    </span>
                @elseif ($page->status === 'scheduled')
                    <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        Terjadwal: {{ $page->scheduled_at?->translatedFormat('d F Y H:i') }}
                    </span>
                @endif
            </div>
        </header>

        <article class="prose-custom px-8 md:px-12 py-12">
            {!! $page->content !!}
        </article>

        <footer class="bg-gray-50 px-8 md:px-12 py-8 border-t border-gray-200">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="text-sm text-gray-600">
                    @if ($page->author)
                        <p>Dibuat oleh <span class="font-semibold text-gray-900">{{ $page->author->name }}</span></p>
                    @endif
                    <p class="mt-1">Terakhir diperbarui: {{ $page->updated_at->translatedFormat('d F Y H:i') }}</p>
                </div>

                @if ($page->status === 'published')
                    <a href="{{ route('pages.show', $page->slug) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Lihat Halaman Publik
                    </a>
                @endif
            </div>
        </footer>
    </main>

    <div class="text-center mt-8 text-sm text-gray-500">
        <p>&copy; {{ now()->year }} PT Perusahaan Indonesia. All rights reserved.</p>
    </div>
</body>
</html>
