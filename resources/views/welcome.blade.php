@extends('layouts.app')

@php
    $landingSettings = app(\App\Settings\LandingPageSettings::class);
@endphp

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
                    {{ $landingSettings->hero_title }}
                    @if($landingSettings->hero_subtitle)
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-yellow-300 to-orange-400">
                            {{ $landingSettings->hero_subtitle }}
                        </span>
                    @endif
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8 leading-relaxed">
                    {{ $landingSettings->hero_description }}
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ $landingSettings->hero_cta_url }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-blue-600 rounded-lg font-bold hover:bg-blue-50 transition-all transform hover:scale-105 shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        {{ $landingSettings->hero_cta_text }}
                    </a>
                    <a href="{{ $landingSettings->hero_secondary_cta_url }}" class="inline-flex items-center gap-2 px-8 py-4 bg-blue-500 bg-opacity-20 backdrop-blur-sm border-2 border-white border-opacity-30 text-white rounded-lg font-bold hover:bg-opacity-30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        {{ $landingSettings->hero_secondary_cta_text }}
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
    @if($landingSettings->show_features)
        <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $landingSettings->features_title }}</h2>
                    @if($landingSettings->features_subtitle)
                        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                            {{ $landingSettings->features_subtitle }}
                        </p>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @php
                        // Decode features from JSON string
                        $features = [];
                        if (!empty($landingSettings->features)) {
                            $decoded = json_decode($landingSettings->features, true);
                            $features = is_array($decoded) ? $decoded : [];
                        }

                        $colors = [
                            'from-blue-500 to-blue-600',
                            'from-green-500 to-green-600',
                            'from-purple-500 to-purple-600',
                            'from-red-500 to-red-600',
                            'from-yellow-500 to-yellow-600',
                            'from-indigo-500 to-indigo-600',
                            'from-pink-500 to-pink-600',
                            'from-teal-500 to-teal-600',
                        ];
                    @endphp

                    @if(count($features) > 0)
                        @foreach($features as $index => $feature)
                        <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow border border-gray-200">
                            <div class="w-14 h-14 bg-gradient-to-br {{ $colors[$index % count($colors)] }} rounded-lg flex items-center justify-center mb-6">
                                @if(isset($feature['icon']) && $feature['icon'])
                                    <x-dynamic-component :component="$feature['icon']" class="w-7 h-7 text-white" />
                                @else
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $feature['title'] }}</h3>
                            <p class="text-gray-600 leading-relaxed">
                                {{ $feature['description'] ?? '' }}
                            </p>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- Latest Blog Posts --}}
    @if($landingSettings->show_blog)
        @php
            $latestPosts = \App\Models\Post::published()
                ->with(['author', 'category'])
                ->latest('published_at')
                ->take($landingSettings->blog_posts_count)
                ->get();
        @endphp

        @if($latestPosts->count() > 0)
            <section class="py-20 bg-white">
                <div class="container mx-auto px-4">
                    <div class="flex items-center justify-between mb-12">
                        <div>
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">{{ $landingSettings->blog_title }}</h2>
                            @if($landingSettings->blog_subtitle)
                                <p class="text-xl text-gray-600">{{ $landingSettings->blog_subtitle }}</p>
                            @endif
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
    @endif

    {{-- CTA Section --}}
    @if($landingSettings->show_cta)
        <section class="py-20 text-white relative overflow-hidden" style="background: {{ $landingSettings->cta_background_color }};">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-1/4 w-64 h-64 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            </div>

            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-3xl mx-auto text-center">
                    <h2 class="text-3xl md:text-4xl font-bold mb-6">
                        {{ $landingSettings->cta_title }}
                    </h2>
                    @if($landingSettings->cta_description)
                        <p class="text-xl opacity-90 mb-8 leading-relaxed">
                            {{ $landingSettings->cta_description }}
                        </p>
                    @endif
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ $landingSettings->cta_button_url }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-gray-900 rounded-lg font-bold hover:bg-gray-100 transition-all shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            {{ $landingSettings->cta_button_text }}
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
