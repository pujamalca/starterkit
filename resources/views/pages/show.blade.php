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
<body class="bg-gray-50 min-h-screen flex flex-col">
    {{-- Header / Navbar (Placeholder) --}}
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="text-2xl font-bold text-gray-900">
                    Logo / Brand
                </div>
                <nav class="hidden md:flex items-center gap-6">
                    <a href="/" class="text-gray-600 hover:text-gray-900 transition-colors">Home</a>
                    <a href="/pages/tentang-kami" class="text-gray-600 hover:text-gray-900 transition-colors">Tentang</a>
                    <a href="/pages/kontak" class="text-gray-600 hover:text-gray-900 transition-colors">Kontak</a>
                </nav>
            </div>
        </div>
    </header>

    @if (isset($preview) && $preview)
        <div class="bg-amber-50 border-b border-amber-200">
            <div class="container mx-auto px-4 py-3">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <span class="font-semibold text-amber-900">Mode Preview</span>
                        <span class="text-sm text-amber-700 ml-2">- Perubahan belum dipublikasikan</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="flex-grow">
        {{-- Page Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-12">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $page->title }}</h1>

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
            </div>
        </div>

        {{-- Page Content --}}
        <div class="container mx-auto px-4 py-12">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 md:p-12">
                <article class="prose-custom max-w-none">
                    {!! $page->content !!}
                </article>
            </div>

            {{-- Page Meta Info --}}
            @if ($page->author)
                <div class="mt-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between flex-wrap gap-4 text-sm text-gray-600">
                        <div>
                            <p>Dibuat oleh <span class="font-semibold text-gray-900">{{ $page->author->name }}</span></p>
                            <p class="mt-1">Terakhir diperbarui: {{ $page->updated_at->translatedFormat('d F Y H:i') }}</p>
                        </div>

                        @if ($page->status === 'published' && isset($preview) && $preview)
                            <a href="{{ route('pages.show', $page->slug) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                Lihat Halaman Publik
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white mt-auto">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                {{-- Footer Column 1 --}}
                <div>
                    <h3 class="text-lg font-bold mb-4">PT Perusahaan Indonesia</h3>
                    <p class="text-gray-400 text-sm">
                        Solusi digital terpercaya untuk transformasi bisnis Anda.
                    </p>
                </div>

                {{-- Footer Column 2 --}}
                <div>
                    <h3 class="text-lg font-bold mb-4">Link Cepat</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/pages/tentang-kami" class="text-gray-400 hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="/pages/kontak" class="text-gray-400 hover:text-white transition-colors">Kontak</a></li>
                        <li><a href="/pages/faq" class="text-gray-400 hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>

                {{-- Footer Column 3 --}}
                <div>
                    <h3 class="text-lg font-bold mb-4">Legal</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/pages/kebijakan-privasi" class="text-gray-400 hover:text-white transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="/pages/syarat-ketentuan" class="text-gray-400 hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ now()->year }} PT Perusahaan Indonesia. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
