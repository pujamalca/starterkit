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
                    <div class="post-content" id="blog-content">
                        {!! $post->content !!}
                    </div>
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
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Twitter --}}
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(request()->url()) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                            Twitter
                        </a>

                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            Facebook
                        </a>

                        {{-- LinkedIn --}}
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            LinkedIn
                        </a>

                        {{-- WhatsApp --}}
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' - ' . request()->url()) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp
                        </a>

                        {{-- Telegram --}}
                        <a href="https://t.me/share/url?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                            Telegram
                        </a>

                        {{-- Pinterest --}}
                        <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(request()->url()) }}&description={{ urlencode($post->title) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>
                            Pinterest
                        </a>

                        {{-- Reddit --}}
                        <a href="https://reddit.com/submit?url={{ urlencode(request()->url()) }}&title={{ urlencode($post->title) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm5.01 4.744c.688 0 1.25.561 1.25 1.249a1.25 1.25 0 0 1-2.498.056l-2.597-.547-.8 3.747c1.824.07 3.48.632 4.674 1.488.308-.309.73-.491 1.207-.491.968 0 1.754.786 1.754 1.754 0 .716-.435 1.333-1.01 1.614a3.111 3.111 0 0 1 .042.52c0 2.694-3.13 4.87-7.004 4.87-3.874 0-7.004-2.176-7.004-4.87 0-.183.015-.366.043-.534A1.748 1.748 0 0 1 4.028 12c0-.968.786-1.754 1.754-1.754.463 0 .898.196 1.207.49 1.207-.883 2.878-1.43 4.744-1.487l.885-4.182a.342.342 0 0 1 .14-.197.35.35 0 0 1 .238-.042l2.906.617a1.214 1.214 0 0 1 1.108-.701zM9.25 12C8.561 12 8 12.562 8 13.25c0 .687.561 1.248 1.25 1.248.687 0 1.248-.561 1.248-1.249 0-.688-.561-1.249-1.249-1.249zm5.5 0c-.687 0-1.248.561-1.248 1.25 0 .687.561 1.248 1.249 1.248.688 0 1.249-.561 1.249-1.249 0-.687-.562-1.249-1.25-1.249zm-5.466 3.99a.327.327 0 0 0-.231.094.33.33 0 0 0 0 .463c.842.842 2.484.913 2.961.913.477 0 2.105-.056 2.961-.913a.361.361 0 0 0 .029-.463.33.33 0 0 0-.464 0c-.547.533-1.684.73-2.512.73-.828 0-1.979-.196-2.512-.73a.326.326 0 0 0-.232-.095z"/></svg>
                            Reddit
                        </a>

                        {{-- Email --}}
                        <a href="mailto:?subject={{ urlencode($post->title) }}&body={{ urlencode('Baca artikel ini: ' . request()->url()) }}" class="flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Email
                        </a>

                        {{-- Copy Link --}}
                        <button onclick="copyToClipboard('{{ request()->url() }}')" class="flex items-center gap-2 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Copy Link
                        </button>
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
            /* Comprehensive prose styles for blog content */
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

            /* Post content wrapper - handle all child elements as block */
            .post-content {
                display: block;
                white-space: pre-line;
            }
            .post-content > * {
                display: block;
            }

            /* Fix for content that might not have proper paragraph tags */
            .post-content br {
                display: block;
                content: "";
                margin-top: 0.75rem;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Copy to clipboard function
            function copyToClipboard(text) {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(text).then(function() {
                        // Show success message
                        const originalButton = event.target.closest('button');
                        const originalText = originalButton.innerHTML;
                        originalButton.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Tersalin!';

                        setTimeout(function() {
                            originalButton.innerHTML = originalText;
                        }, 2000);
                    }, function(err) {
                        alert('Gagal menyalin link');
                    });
                } else {
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    document.body.appendChild(textArea);
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        const originalButton = event.target.closest('button');
                        const originalText = originalButton.innerHTML;
                        originalButton.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Tersalin!';

                        setTimeout(function() {
                            originalButton.innerHTML = originalText;
                        }, 2000);
                    } catch (err) {
                        alert('Gagal menyalin link');
                    }
                    document.body.removeChild(textArea);
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const contentDiv = document.getElementById('blog-content');
                if (!contentDiv) return;

                // Get the content
                let content = contentDiv.innerHTML;

                // Check if content already has HTML tags (p, h1, etc)
                const hasHtmlTags = /<(p|h[1-6]|ul|ol|blockquote|pre)[\s>]/i.test(content);

                if (!hasHtmlTags) {
                    // Content is plain text, we need to format it
                    const lines = content.split('\n');
                    let formattedContent = '';
                    let currentParagraph = '';
                    let inList = false;

                    for (let i = 0; i < lines.length; i++) {
                        let line = lines[i].trim();

                        // Skip empty lines
                        if (line === '') {
                            if (currentParagraph) {
                                formattedContent += `<p>${currentParagraph}</p>\n`;
                                currentParagraph = '';
                            }
                            if (inList) {
                                formattedContent += '</ul>\n';
                                inList = false;
                            }
                            continue;
                        }

                        // Check for headings
                        if (line.startsWith('# ')) {
                            if (currentParagraph) {
                                formattedContent += `<p>${currentParagraph}</p>\n`;
                                currentParagraph = '';
                            }
                            formattedContent += `<h1>${line.substring(2)}</h1>\n`;
                        } else if (line.startsWith('## ')) {
                            if (currentParagraph) {
                                formattedContent += `<p>${currentParagraph}</p>\n`;
                                currentParagraph = '';
                            }
                            formattedContent += `<h2>${line.substring(3)}</h2>\n`;
                        } else if (line.startsWith('### ')) {
                            if (currentParagraph) {
                                formattedContent += `<p>${currentParagraph}</p>\n`;
                                currentParagraph = '';
                            }
                            formattedContent += `<h3>${line.substring(4)}</h3>\n`;
                        } else if (line.startsWith('#### ')) {
                            if (currentParagraph) {
                                formattedContent += `<p>${currentParagraph}</p>\n`;
                                currentParagraph = '';
                            }
                            formattedContent += `<h4>${line.substring(5)}</h4>\n`;
                        }
                        // Check for list items
                        else if (line.startsWith('- ')) {
                            if (currentParagraph) {
                                formattedContent += `<p>${currentParagraph}</p>\n`;
                                currentParagraph = '';
                            }
                            if (!inList) {
                                formattedContent += '<ul>\n';
                                inList = true;
                            }
                            formattedContent += `<li>${line.substring(2)}</li>\n`;
                        }
                        // Check for blockquote
                        else if (line.startsWith('> ')) {
                            if (currentParagraph) {
                                formattedContent += `<p>${currentParagraph}</p>\n`;
                                currentParagraph = '';
                            }
                            if (inList) {
                                formattedContent += '</ul>\n';
                                inList = false;
                            }
                            formattedContent += `<blockquote><p>${line.substring(2)}</p></blockquote>\n`;
                        }
                        // Regular text
                        else {
                            if (inList) {
                                formattedContent += '</ul>\n';
                                inList = false;
                            }

                            // Process inline formatting (bold, italic, etc)
                            line = line.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
                            line = line.replace(/\*(.+?)\*/g, '<em>$1</em>');
                            line = line.replace(/`(.+?)`/g, '<code>$1</code>');

                            if (currentParagraph) {
                                currentParagraph += ' ' + line;
                            } else {
                                currentParagraph = line;
                            }
                        }
                    }

                    // Add remaining paragraph
                    if (currentParagraph) {
                        formattedContent += `<p>${currentParagraph}</p>\n`;
                    }

                    if (inList) {
                        formattedContent += '</ul>\n';
                    }

                    // Update content
                    contentDiv.innerHTML = formattedContent;
                }

                // Remove white-space pre-line after processing
                contentDiv.style.whiteSpace = 'normal';
            });
        </script>
    @endpush
@endsection
