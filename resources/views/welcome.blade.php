@extends('layouts.app')

@php
    $landingSettings = app(\App\Settings\LandingPageSettings::class);
@endphp

@section('title', 'Home - ' . config('app.name'))
@section('meta_description', 'Laravel Starter Kit dengan Filament Admin Panel, RESTful API, Content Management, dan fitur modern untuk mempercepat development aplikasi web Anda.')

@section('content')
    {{-- Prepare Hero Buttons --}}
    @php
        $heroButtons = [];
        if (!empty($landingSettings->hero_buttons)) {
            $decoded = json_decode($landingSettings->hero_buttons, true);
            $heroButtons = is_array($decoded) ? $decoded : [];
        }
    @endphp

    {{-- Hero Section - Style image_right --}}
    @if($landingSettings->hero_style === 'image_right')
    <section class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute transform rotate-45 -translate-x-1/2 -translate-y-1/2 top-0 left-0 w-96 h-96 bg-white rounded-full"></div>
            <div class="absolute transform rotate-12 translate-x-1/2 translate-y-1/2 bottom-0 right-0 w-64 h-64 bg-white rounded-full"></div>
        </div>

        <div class="container mx-auto px-4 py-20 md:py-32 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                {{-- Hero Content --}}
                <div class="text-center md:text-left">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4 leading-tight">
                        {{ $landingSettings->hero_title }}
                    </h1>
                    @if($landingSettings->hero_subtitle)
                        <h2 class="text-3xl md:text-5xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-yellow-300 to-orange-400">
                            {{ $landingSettings->hero_subtitle }}
                        </h2>
                    @endif
                    <p class="text-xl md:text-2xl text-blue-100 mb-8 leading-relaxed">
                        {{ $landingSettings->hero_description }}
                    </p>
                    <div class="flex flex-col sm:flex-row items-center md:items-start md:justify-start justify-center gap-4">
                        @foreach($heroButtons as $button)
                            @if($button['style'] === 'primary')
                                <a href="{{ $button['url'] }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-blue-600 rounded-lg font-bold hover:bg-blue-50 transition-all transform hover:scale-105 shadow-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    {{ $button['text'] }}
                                </a>
                            @else
                                <a href="{{ $button['url'] }}" class="inline-flex items-center gap-2 px-8 py-4 bg-blue-500 bg-opacity-20 backdrop-blur-sm border-2 border-white border-opacity-30 text-white rounded-lg font-bold hover:bg-opacity-30 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    {{ $button['text'] }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Hero Image --}}
                <div class="relative">
                    @if($landingSettings->hero_image)
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl transform hover:scale-105 transition-transform duration-300">
                            <img src="{{ asset('storage/' . $landingSettings->hero_image) }}"
                                 alt="{{ $landingSettings->hero_title }}"
                                 class="w-full h-auto object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/20 to-transparent"></div>
                        </div>
                    @else
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl bg-gradient-to-br from-blue-500 to-purple-600 aspect-square flex items-center justify-center">
                            <svg class="w-32 h-32 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-16 max-w-4xl mx-auto">
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

        {{-- Wave Divider --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#F9FAFB"/>
            </svg>
        </div>
    </section>
    @endif

    {{-- Hero Section - Style full_background --}}
    @if($landingSettings->hero_style === 'full_background')
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        {{-- Background Image with Overlay --}}
        @if($landingSettings->hero_image)
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('storage/' . $landingSettings->hero_image) }}"
                     alt="{{ $landingSettings->hero_title }}"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-br from-gray-900/90 via-blue-900/85 to-gray-900/90"></div>
            </div>
        @else
            <div class="absolute inset-0 z-0 bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900"></div>
        @endif

        {{-- Decorative Elements --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute top-20 left-10 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-40 right-10 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        {{-- Content --}}
        <div class="container mx-auto px-4 py-20 relative z-10">
            <div class="max-w-4xl mx-auto text-center text-white">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                    {{ $landingSettings->hero_title }}
                </h1>
                @if($landingSettings->hero_subtitle)
                    <h2 class="text-3xl md:text-5xl font-bold mb-8 bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400">
                        {{ $landingSettings->hero_subtitle }}
                    </h2>
                @endif
                <p class="text-xl md:text-2xl text-gray-300 mb-10 leading-relaxed max-w-3xl mx-auto">
                    {{ $landingSettings->hero_description }}
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @foreach($heroButtons as $button)
                        @if($button['style'] === 'primary')
                            <a href="{{ $button['url'] }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-bold hover:from-blue-700 hover:to-blue-800 transition-all transform hover:scale-105 shadow-2xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                {{ $button['text'] }}
                            </a>
                        @else
                            <a href="{{ $button['url'] }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white/10 backdrop-blur-sm border-2 border-white/30 text-white rounded-lg font-bold hover:bg-white/20 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                {{ $button['text'] }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10 animate-bounce">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </section>
    @endif

    {{-- Hero Section - Style centered_overlay --}}
    @if($landingSettings->hero_style === 'centered_overlay')
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-white">
        {{-- Background Image --}}
        @if($landingSettings->hero_image)
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('storage/' . $landingSettings->hero_image) }}"
                     alt="{{ $landingSettings->hero_title }}"
                     class="w-full h-full object-cover opacity-20">
            </div>
        @endif

        {{-- Content --}}
        <div class="container mx-auto px-4 py-20 relative z-10">
            <div class="max-w-5xl mx-auto">
                <div class="bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl p-8 md:p-16 border border-gray-200">
                    <div class="text-center">
                        <h1 class="text-4xl md:text-6xl font-bold mb-4 leading-tight bg-clip-text text-transparent bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600">
                            {{ $landingSettings->hero_title }}
                        </h1>
                        @if($landingSettings->hero_subtitle)
                            <h2 class="text-2xl md:text-4xl font-semibold mb-6 text-gray-700">
                                {{ $landingSettings->hero_subtitle }}
                            </h2>
                        @endif
                        <p class="text-lg md:text-xl text-gray-600 mb-8 leading-relaxed max-w-3xl mx-auto">
                            {{ $landingSettings->hero_description }}
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            @foreach($heroButtons as $button)
                                @if($button['style'] === 'primary')
                                    <a href="{{ $button['url'] }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-bold hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 shadow-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        {{ $button['text'] }}
                                    </a>
                                @else
                                    <a href="{{ $button['url'] }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-all border-2 border-gray-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        {{ $button['text'] }}
                                    </a>
                                @endif
                            @endforeach
                        </div>

                        {{-- Image Preview if exists --}}
                        @if($landingSettings->hero_image)
                            <div class="mt-12">
                                <img src="{{ asset('storage/' . $landingSettings->hero_image) }}"
                                     alt="{{ $landingSettings->hero_title }}"
                                     class="rounded-2xl shadow-2xl mx-auto max-w-2xl w-full">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Decorative gradient circles --}}
        <div class="absolute top-0 left-0 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 translate-x-1/2 translate-y-1/2"></div>
    </section>
    @endif

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

    {{-- FAQ Section --}}
    @if($landingSettings->show_faq)
        <section class="py-20 bg-gradient-to-b from-white to-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            {{ $landingSettings->faq_title }}
                        </h2>
                        @if($landingSettings->faq_subtitle)
                            <p class="text-xl text-gray-600 leading-relaxed">
                                {{ $landingSettings->faq_subtitle }}
                            </p>
                        @endif
                    </div>

                    @php
                        // Decode faqs from JSON string
                        $faqs = [];
                        if (!empty($landingSettings->faqs)) {
                            $decoded = json_decode($landingSettings->faqs, true);
                            $faqs = is_array($decoded) ? $decoded : [];
                        }
                    @endphp

                    <div class="space-y-4">
                        @foreach($faqs as $index => $faq)
                            <div x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }"
                                 class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                                <button @click="open = !open"
                                        class="w-full px-6 py-5 text-left flex items-center justify-between gap-4 hover:bg-gray-50 transition-colors">
                                    <span class="text-lg font-semibold text-gray-900 flex-1">
                                        {{ $faq['question'] }}
                                    </span>
                                    <svg class="w-6 h-6 text-blue-600 transform transition-transform flex-shrink-0"
                                         :class="{ 'rotate-180': open }"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-y-95"
                                     x-transition:enter-end="opacity-100 transform scale-y-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 transform scale-y-100"
                                     x-transition:leave-end="opacity-0 transform scale-y-95"
                                     class="px-6 pb-5 origin-top">
                                    <div class="text-gray-600 leading-relaxed border-t border-gray-100 pt-4">
                                        {{ $faq['answer'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Contact CTA in FAQ --}}
                    <div class="mt-12 text-center p-8 bg-blue-50 rounded-2xl border border-blue-100">
                        <p class="text-lg text-gray-700 mb-4">
                            Masih ada pertanyaan lain?
                        </p>
                        <a href="/blog" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
