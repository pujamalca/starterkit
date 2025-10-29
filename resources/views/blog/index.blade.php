@extends('layouts.app')

@section('title', 'Blog - ' . config('app.name'))
@section('meta_description', 'Baca artikel dan tutorial terbaru tentang Laravel, Filament, API development, dan topik pengembangan web lainnya.')

@section('content')
    {{-- Page Header --}}
    <section class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Blog & Artikel</h1>
                <p class="text-xl text-blue-100">
                    Temukan tutorial, tips, dan insight terbaru seputar development
                </p>
            </div>
        </div>
    </section>

    {{-- Search & Filter Section --}}
    <section class="bg-white border-b border-gray-200 py-6">
        <div class="container mx-auto px-4">
            <form action="{{ route('blog.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                {{-- Search Input --}}
                <div class="flex-1">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari artikel..."
                            class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Category Filter --}}
                <div class="md:w-64">
                    <select
                        name="category"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        onchange="this.form.submit()"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->posts_count }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Submit Button --}}
                <button
                    type="submit"
                    class="px-8 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors"
                >
                    Cari
                </button>

                {{-- Reset Button --}}
                @if(request()->hasAny(['search', 'category', 'tag']))
                    <a
                        href="{{ route('blog.index') }}"
                        class="px-8 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors text-center"
                    >
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </section>

    {{-- Blog Posts Grid --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($posts as $post)
                        <article class="bg-white rounded-xl overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow group flex flex-col">
                            {{-- Featured Image --}}
                            @if($post->featured_image)
                                <div class="aspect-video bg-gradient-to-br from-blue-500 to-purple-600 overflow-hidden">
                                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                            @else
                                <div class="aspect-video bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                            @endif

                            <div class="p-6 flex flex-col flex-1">
                                {{-- Category Badge --}}
                                @if($post->category)
                                    <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full mb-3 w-fit hover:bg-blue-200 transition-colors">
                                        {{ $post->category->name }}
                                    </a>
                                @endif

                                {{-- Title --}}
                                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
                                    <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                </h3>

                                {{-- Excerpt --}}
                                <p class="text-gray-600 mb-4 line-clamp-3 flex-1">
                                    {{ $post->excerpt ?? strip_tags($post->content) }}
                                </p>

                                {{-- Meta Info --}}
                                <div class="flex items-center justify-between text-sm text-gray-500 pt-4 border-t border-gray-200">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {{ substr($post->author->name, 0, 1) }}
                                        </div>
                                        <span class="font-medium">{{ $post->author->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        @if($post->reading_time)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $post->reading_time }} min
                                            </span>
                                        @endif
                                        <span>{{ $post->published_at?->diffForHumans() ?? $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-16">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Tidak Ada Artikel</h3>
                    <p class="text-gray-600 mb-8">
                        @if(request()->hasAny(['search', 'category', 'tag']))
                            Tidak ada artikel yang sesuai dengan pencarian Anda. Coba kata kunci atau filter lain.
                        @else
                            Belum ada artikel yang dipublikasikan.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'category', 'tag']))
                        <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Lihat Semua Artikel
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </section>

    {{-- Newsletter CTA (Optional) --}}
    <section class="py-16 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-4">Jangan Lewatkan Update Terbaru</h2>
                <p class="text-blue-100 mb-8">
                    Dapatkan notifikasi untuk artikel dan tutorial baru langsung di inbox Anda
                </p>
                <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
                    <input
                        type="email"
                        placeholder="Email Anda"
                        class="flex-1 px-6 py-4 rounded-lg text-gray-900 focus:ring-2 focus:ring-blue-300"
                        required
                    >
                    <button
                        type="submit"
                        class="px-8 py-4 bg-white text-blue-600 rounded-lg font-bold hover:bg-blue-50 transition-colors"
                    >
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
