@extends('layouts.app')

@section('title', $post->seo_title ?? $post->title)
@section('meta_description', $post->seo_description ?? $post->excerpt ?? strip_tags(substr($post->content, 0, 160)))

@push('meta')
    @if($post->og_image)
        <meta property="og:image" content="{{ $post->og_image }}">
    @endif
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ $post->excerpt ?? strip_tags(substr($post->content, 0, 160)) }}">
    <meta property="og:type" content="article">
    <meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
    @if($post->author)
        <meta property="article:author" content="{{ $post->author->name }}">
    @endif
@endpush

@section('content')
    {{-- Post Header --}}
    <article class="bg-white">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto">
                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 text-sm text-gray-600 mb-8">
                    <a href="/" class="hover:text-blue-600">Home</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="{{ route('blog.index') }}" class="hover:text-blue-600">Blog</a>
                    @if($post->category)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="hover:text-blue-600">
                            {{ $post->category->name }}
                        </a>
                    @endif
                </nav>

                {{-- Category Badge --}}
                @if($post->category)
                    <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="inline-block px-4 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-full mb-6 hover:bg-blue-200 transition-colors">
                        {{ $post->category->name }}
                    </a>
                @endif

                {{-- Title --}}
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                    {{ $post->title }}
                </h1>

                {{-- Meta Info --}}
                <div class="flex flex-wrap items-center gap-6 pb-8 border-b border-gray-200 mb-8">
                    {{-- Author --}}
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($post->author->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $post->author->name }}</p>
                            <p class="text-sm text-gray-600">{{ ($post->published_at ?? $post->created_at)->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-6 text-sm text-gray-600">
                        {{-- Reading Time --}}
                        @if($post->reading_time)
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $post->reading_time }} min baca
                            </span>
                        @endif

                        {{-- Views --}}
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ number_format($post->views_count) }} views
                        </span>

                        {{-- Comments Count --}}
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            {{ $post->comments->count() }} komentar
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Featured Image --}}
        @if($post->featured_image)
            <div class="w-full bg-gray-100">
                <div class="container mx-auto px-4">
                    <div class="max-w-5xl mx-auto">
                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full rounded-xl shadow-lg">
                    </div>
                </div>
            </div>
        @endif

        {{-- Post Content --}}
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto">
                {{-- Excerpt --}}
                @if($post->excerpt)
                    <div class="text-xl text-gray-700 leading-relaxed mb-8 p-6 bg-blue-50 border-l-4 border-blue-500 rounded-r-lg">
                        {{ $post->excerpt }}
                    </div>
                @endif

                {{-- Content --}}
                <div class="prose-custom max-w-none">
                    {!! $post->content !!}
                </div>

                {{-- Tags --}}
                @if($post->tags->count() > 0)
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-500 mb-4">TAGS:</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($post->tags as $tag)
                                <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition-colors">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Share Buttons --}}
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-500 mb-4">BAGIKAN:</h3>
                    <div class="flex items-center gap-3">
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(request()->url()) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                            Twitter
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            Facebook
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            LinkedIn
                        </a>
                    </div>
                </div>

                {{-- Author Bio --}}
                <div class="mt-12 p-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                    <div class="flex items-start gap-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
                            {{ substr($post->author->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $post->author->name }}</h3>
                            <p class="text-gray-700 leading-relaxed mb-4">
                                {{ $post->author->bio ?? 'Content creator dan pengembang web yang passionate tentang teknologi dan berbagi pengetahuan.' }}
                            </p>
                            <div class="flex items-center gap-4">
                                <span class="text-sm text-gray-600">
                                    <strong>{{ \App\Models\Post::where('author_id', $post->author_id)->published()->count() }}</strong> artikel ditulis
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>

    {{-- Related Posts --}}
    @if($relatedPosts->count() > 0)
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">Artikel Terkait</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @foreach($relatedPosts as $relatedPost)
                            <article class="bg-white rounded-xl overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow group">
                                @if($relatedPost->featured_image)
                                    <div class="aspect-video bg-gradient-to-br from-blue-500 to-purple-600 overflow-hidden">
                                        <img src="{{ $relatedPost->featured_image }}" alt="{{ $relatedPost->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    </div>
                                @else
                                    <div class="aspect-video bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                @endif

                                <div class="p-6">
                                    @if($relatedPost->category)
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full mb-3">
                                            {{ $relatedPost->category->name }}
                                        </span>
                                    @endif

                                    <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                                        <a href="{{ route('blog.show', $relatedPost->slug) }}">{{ $relatedPost->title }}</a>
                                    </h3>

                                    <p class="text-gray-600 text-sm line-clamp-2 mb-4">
                                        {{ $relatedPost->excerpt ?? strip_tags($relatedPost->content) }}
                                    </p>

                                    <div class="flex items-center text-sm text-gray-500">
                                        <span>{{ $relatedPost->published_at?->diffForHumans() ?? $relatedPost->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Comments Section --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">
                    Komentar ({{ $post->comments->count() }})
                </h2>

                {{-- Comments List --}}
                @if($post->comments->count() > 0)
                    <div class="space-y-6 mb-12">
                        @foreach($post->comments as $comment)
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                        {{ substr($comment->user ? $comment->user->name : $comment->guest_name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h4 class="font-bold text-gray-900">
                                                {{ $comment->user ? $comment->user->name : $comment->guest_name }}
                                            </h4>
                                            <span class="text-sm text-gray-500">
                                                {{ $comment->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-gray-700 leading-relaxed">
                                            {{ $comment->content }}
                                        </p>

                                        {{-- Replies --}}
                                        @if($comment->replies && $comment->replies->count() > 0)
                                            <div class="mt-4 space-y-4">
                                                @foreach($comment->replies as $reply)
                                                    <div class="flex items-start gap-4 pl-8 border-l-2 border-blue-200">
                                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                                            {{ substr($reply->user ? $reply->user->name : $reply->guest_name, 0, 1) }}
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="flex items-center gap-3 mb-2">
                                                                <h5 class="font-bold text-gray-900 text-sm">
                                                                    {{ $reply->user ? $reply->user->name : $reply->guest_name }}
                                                                </h5>
                                                                <span class="text-xs text-gray-500">
                                                                    {{ $reply->created_at->diffForHumans() }}
                                                                </span>
                                                            </div>
                                                            <p class="text-gray-700 text-sm leading-relaxed">
                                                                {{ $reply->content }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg mb-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        <p class="text-gray-600">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                    </div>
                @endif

                {{-- Comment Form --}}
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-8 border border-blue-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Tulis Komentar</h3>
                    <p class="text-sm text-gray-600 mb-6">
                        Komentar Anda akan ditinjau sebelum dipublikasikan. Mohon gunakan bahasa yang sopan dan relevan.
                    </p>
                    <form class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                                <input type="text" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Komentar</label>
                            <textarea rows="4" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                        <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            Kirim Komentar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            /* Prose custom styles for post content */
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
                @apply text-gray-700 leading-relaxed mb-4 text-lg;
            }
            .prose-custom ul {
                @apply list-disc list-inside space-y-2 mb-4 ml-4;
            }
            .prose-custom ol {
                @apply list-decimal list-inside space-y-2 mb-4 ml-4;
            }
            .prose-custom li {
                @apply text-gray-700 leading-relaxed text-lg;
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
    @endpush
@endsection
