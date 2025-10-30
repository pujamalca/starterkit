@extends('layouts.app')

@section('title', (isset($preview) && $preview ? '[PREVIEW] ' : '') . ($page->seo_title ?? $page->title))

@if ($page->seo_description)
    @section('meta_description', $page->seo_description)
@endif

@if ($page->canonical_url)
    @section('canonical_url', $page->canonical_url)
@endif

@push('styles')
    <style>
        /* Comprehensive prose styles for page content */
        .prose-custom {
            max-width: none;
            color: #374151;
            font-size: 1.125rem;
            line-height: 1.75rem;
        }

        /* Headings */
        .prose-custom h1 {
            font-size: 2.25rem;
            line-height: 2.5rem;
            font-weight: 800;
            color: #111827;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .prose-custom h1:first-child {
            margin-top: 0;
        }
        .prose-custom h2 {
            font-size: 1.875rem;
            line-height: 2.25rem;
            font-weight: 700;
            color: #111827;
            margin-top: 2rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }
        .prose-custom h3 {
            font-size: 1.5rem;
            line-height: 2rem;
            font-weight: 600;
            color: #1f2937;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }
        .prose-custom h4 {
            font-size: 1.25rem;
            line-height: 1.75rem;
            font-weight: 600;
            color: #1f2937;
            margin-top: 1.25rem;
            margin-bottom: 0.5rem;
        }

        /* Paragraphs */
        .prose-custom p {
            color: #4b5563;
            line-height: 1.75;
            margin-bottom: 1.25rem;
            margin-top: 0;
        }
        .prose-custom p:last-child {
            margin-bottom: 0;
        }

        /* Lists */
        .prose-custom ul {
            list-style-type: disc;
            padding-left: 1.625rem;
            margin-top: 1.25rem;
            margin-bottom: 1.25rem;
        }
        .prose-custom ol {
            list-style-type: decimal;
            padding-left: 1.625rem;
            margin-top: 1.25rem;
            margin-bottom: 1.25rem;
        }
        .prose-custom li {
            color: #4b5563;
            line-height: 1.75;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
            padding-left: 0.375rem;
        }
        .prose-custom ul ul,
        .prose-custom ul ol,
        .prose-custom ol ul,
        .prose-custom ol ol {
            margin-top: 0.75rem;
            margin-bottom: 0.75rem;
        }
        .prose-custom li > p {
            margin-top: 0.75rem;
            margin-bottom: 0.75rem;
        }

        /* Inline elements */
        .prose-custom strong,
        .prose-custom b {
            font-weight: 600;
            color: #111827;
        }
        .prose-custom em,
        .prose-custom i {
            font-style: italic;
        }
        .prose-custom u {
            text-decoration: underline;
            text-decoration-color: #9ca3af;
        }
        .prose-custom s {
            text-decoration: line-through;
        }

        /* Links */
        .prose-custom a {
            color: #2563eb;
            text-decoration: underline;
            font-weight: 500;
            transition: color 0.2s;
        }
        .prose-custom a:hover {
            color: #1d4ed8;
        }

        /* Blockquotes */
        .prose-custom blockquote {
            border-left: 4px solid #3b82f6;
            padding-left: 1rem;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            font-style: italic;
            color: #6b7280;
            margin: 1.5rem 0;
            background-color: #f9fafb;
            border-radius: 0.25rem;
        }
        .prose-custom blockquote p {
            margin-bottom: 0.5rem;
        }
        .prose-custom blockquote p:last-child {
            margin-bottom: 0;
        }

        /* Code */
        .prose-custom code {
            background-color: #f3f4f6;
            color: #dc2626;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-weight: 400;
        }
        .prose-custom pre {
            background-color: #1f2937;
            color: #f9fafb;
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 1.5rem 0;
            line-height: 1.625;
        }
        .prose-custom pre code {
            background-color: transparent;
            color: inherit;
            padding: 0;
            font-size: 0.875rem;
        }

        /* Tables */
        .prose-custom table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            font-size: 0.875rem;
        }
        .prose-custom thead {
            border-bottom: 2px solid #d1d5db;
        }
        .prose-custom th {
            background-color: #f9fafb;
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: #111827;
            border: 1px solid #e5e7eb;
        }
        .prose-custom td {
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            color: #4b5563;
        }
        .prose-custom tbody tr:hover {
            background-color: #f9fafb;
        }

        /* Horizontal Rule */
        .prose-custom hr {
            border: 0;
            border-top: 1px solid #e5e7eb;
            margin: 2rem 0;
        }

        /* Images */
        .prose-custom img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin: 1.5rem 0;
        }

        /* Figure */
        .prose-custom figure {
            margin: 2rem 0;
        }
        .prose-custom figcaption {
            margin-top: 0.75rem;
            font-size: 0.875rem;
            color: #6b7280;
            text-align: center;
        }

        /* Ensure proper spacing between elements */
        .prose-custom > * + * {
            margin-top: 1.25rem;
        }
        .prose-custom > h2 + *,
        .prose-custom > h3 + *,
        .prose-custom > h4 + * {
            margin-top: 0.75rem;
        }

    </style>
@endpush


@if (isset($preview) && $preview)
    @section('banner')
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
    @endsection
@endif

@section('content')
    {{-- Page Header --}}
    <article class="bg-white">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto">
                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 text-sm text-gray-600 mb-8">
                    <a href="/" class="hover:text-blue-600">Home</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-gray-900">{{ $page->title }}</span>
                </nav>

                {{-- Status Badge --}}
                @if ($page->status === 'draft')
                    <span class="inline-block px-4 py-2 bg-amber-100 text-amber-700 text-sm font-medium rounded-full mb-6">
                        Draft
                    </span>
                @elseif ($page->status === 'scheduled')
                    <span class="inline-block px-4 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-full mb-6">
                        Terjadwal: {{ $page->scheduled_at?->translatedFormat('d F Y H:i') }}
                    </span>
                @endif

                {{-- Title --}}
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                    {{ $page->title }}
                </h1>

                {{-- Meta Info --}}
                <div class="flex flex-wrap items-center gap-6 pb-8 border-b border-gray-200 mb-8">
                    {{-- Author --}}
                    @if ($page->author)
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($page->author->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $page->author->name }}</p>
                                <p class="text-sm text-gray-600">{{ ($page->published_at ?? $page->created_at)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-6 text-sm text-gray-600">
                        {{-- Reading Time --}}
                        @if(isset($page->reading_time) && $page->reading_time)
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $page->reading_time }} min baca
                            </span>
                        @endif

                        {{-- Views --}}
                        @if(isset($page->views_count))
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ number_format($page->views_count) }} views
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Featured Image --}}
        @if(isset($page->featured_image) && $page->featured_image)
            <div class="w-full bg-gray-100">
                <div class="container mx-auto px-4">
                    <div class="max-w-5xl mx-auto">
                        <img src="{{ $page->featured_image }}" alt="{{ $page->title }}" class="w-full rounded-xl shadow-lg">
                    </div>
                </div>
            </div>
        @endif

        {{-- Page Content --}}
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto">
                {{-- Content --}}
                <div class="prose-custom max-w-none" id="page-content">
                    {!! $page->formatted_content !!}
                </div>

                {{-- Author Bio --}}
                @if ($page->author)
                    <div class="mt-12 p-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                        <div class="flex items-start gap-6">
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
                                {{ substr($page->author->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $page->author->name }}</h3>
                                <p class="text-gray-700 leading-relaxed mb-4">
                                    {{ $page->author->bio ?? 'Content creator yang passionate tentang berbagi pengetahuan.' }}
                                </p>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-600">
                                        Terakhir diperbarui: {{ ($page->updated_at ?? $page->created_at ?? now())->translatedFormat('d F Y H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if ($page->status === 'published' && isset($preview) && $preview)
                            <div class="mt-6 pt-6 border-t border-blue-200">
                                <a href="{{ route('pages.show', $page->slug) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Lihat Halaman Publik
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </article>
@endsection
