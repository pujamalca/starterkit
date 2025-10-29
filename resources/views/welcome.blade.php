@extends('layouts.app')

@section('title', 'Home - ' . config('app.name'))
@section('meta_description', 'Laravel Starter Kit dengan Filament Admin Panel, RESTful API, Content Management, dan fitur modern untuk mempercepat development aplikasi web Anda.')

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute transform rotate-45 -translate-x-1/2 -translate-y-1/2 top-0 left-0 w-96 h-96 bg-white rounded-full"></div>
            <div class="absolute transform rotate-12 translate-x-1/2 translate-y-1/2 bottom-0 right-0 w-64 h-64 bg-white rounded-full"></div>
        </div>

        <div class="container mx-auto px-4 py-20 md:py-32 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    Laravel Starter Kit yang
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-yellow-300 to-orange-400">
                        Lengkap & Modern
                    </span>
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8 leading-relaxed">
                    Percepat development aplikasi web Anda dengan Filament Admin Panel, RESTful API, Content Management System, dan fitur-fitur profesional yang siap pakai.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="/admin" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-blue-600 rounded-lg font-bold hover:bg-blue-50 transition-all transform hover:scale-105 shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Admin Panel
                    </a>
                    <a href="/blog" class="inline-flex items-center gap-2 px-8 py-4 bg-blue-500 bg-opacity-20 backdrop-blur-sm border-2 border-white border-opacity-30 text-white rounded-lg font-bold hover:bg-opacity-30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Lihat Blog
                    </a>
                    <a href="/api/documentation" class="inline-flex items-center gap-2 px-8 py-4 bg-transparent border-2 border-white text-white rounded-lg font-bold hover:bg-white hover:text-blue-600 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                        API Docs
                    </a>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-16">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-yellow-300">{{ \App\Models\Post::published()->count() }}+</div>
                        <div class="text-blue-200 mt-2">Blog Posts</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-yellow-300">{{ \App\Models\Category::count() }}+</div>
                        <div class="text-blue-200 mt-2">Categories</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-yellow-300">10+</div>
                        <div class="text-blue-200 mt-2">API Endpoints</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-yellow-300">100%</div>
                        <div class="text-blue-200 mt-2">Open Source</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Wave Divider --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#F9FAFB"/>
            </svg>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Fitur Unggulan</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Dibangun dengan teknologi modern dan best practices untuk memastikan aplikasi Anda scalable dan maintainable.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow border border-gray-200">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Filament Admin Panel</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Interface admin yang powerful dan modern dengan form builder, table builder, dan resource management yang lengkap.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow border border-gray-200">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">RESTful API</h3>
                    <p class="text-gray-600 leading-relaxed">
                        API yang terstruktur dengan versioning, authentication (Sanctum), throttling, dan dokumentasi Swagger/OpenAPI.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow border border-gray-200">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Content Management</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sistem manajemen konten lengkap dengan posts, pages, categories, tags, comments, dan media library.
                    </p>
                </div>

                {{-- Feature 4 --}}
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow border border-gray-200">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Authentication & Authorization</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sistem keamanan yang robust dengan Laravel Sanctum dan Spatie Permission untuk role-based access control.
                    </p>
                </div>

                {{-- Feature 5 --}}
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow border border-gray-200">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Activity Logging</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Track semua aktivitas user dengan Spatie Activity Log untuk audit trail dan monitoring yang comprehensive.
                    </p>
                </div>

                {{-- Feature 6 --}}
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow border border-gray-200">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Media Library</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Manajemen file yang mudah dengan Spatie Media Library untuk upload, organize, dan serve media files.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Latest Blog Posts --}}
    @php
        $latestPosts = \App\Models\Post::published()
            ->with(['author', 'category'])
            ->latest('published_at')
            ->take(3)
            ->get();
    @endphp

    @if($latestPosts->count() > 0)
        <section class="py-20 bg-white">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between mb-12">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Blog Terbaru</h2>
                        <p class="text-xl text-gray-600">Artikel dan tutorial terkini</p>
                    </div>
                    <a href="/blog" class="hidden md:inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        Lihat Semua
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($latestPosts as $post)
                        <article class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow group">
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

                            <div class="p-6">
                                @if($post->category)
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full mb-3">
                                        {{ $post->category->name }}
                                    </span>
                                @endif

                                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
                                    <a href="/blog/{{ $post->slug }}">{{ $post->title }}</a>
                                </h3>

                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {{ $post->excerpt ?? strip_tags($post->content) }}
                                </p>

                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr($post->author->name, 0, 1) }}
                                        </div>
                                        <span>{{ $post->author->name }}</span>
                                    </div>
                                    <span>{{ $post->published_at?->diffForHumans() ?? $post->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="text-center mt-12 md:hidden">
                    <a href="/blog" class="inline-flex items-center gap-2 px-8 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        Lihat Semua Blog
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- CTA Section --}}
    <section class="py-20 bg-gradient-to-br from-blue-600 to-indigo-700 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-1/4 w-64 h-64 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">
                    Siap Memulai Project Anda?
                </h2>
                <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                    Clone repository ini dan mulai building aplikasi web yang amazing dengan foundation yang solid dan best practices yang terbukti.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="https://github.com" target="_blank" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-blue-600 rounded-lg font-bold hover:bg-blue-50 transition-all shadow-xl">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                        </svg>
                        Clone di GitHub
                    </a>
                    <a href="/admin" class="inline-flex items-center gap-2 px-8 py-4 bg-transparent border-2 border-white text-white rounded-lg font-bold hover:bg-white hover:text-blue-600 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Coba Admin Panel
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
