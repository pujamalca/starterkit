@php
    $settings = app(\App\Settings\GeneralSettings::class);
    $siteName = $settings->site_name ?? config('app.name', 'Starter Kit');
    $siteLogo = $settings->site_logo;

    // Get blog categories
    $categories = \App\Models\Category::orderBy('name')->get();

    // Get pages for header
    $headerPages = \App\Models\Page::showInHeader()->get();
@endphp

<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            {{-- Logo / Brand --}}
            <div class="flex items-center gap-8">
                <a href="/" class="flex items-center gap-2">
                    @if($siteLogo)
                        <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" class="h-8 w-auto object-contain">
                    @else
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">{{ substr($siteName, 0, 1) }}</span>
                        </div>
                    @endif
                    <span class="text-xl font-bold text-gray-900">{{ $siteName }}</span>
                </a>

                {{-- Desktop Navigation --}}
                <nav class="hidden md:flex items-center gap-6">
                    <a href="/" class="text-gray-600 hover:text-gray-900 transition-colors font-medium {{ request()->is('/') ? 'text-blue-600' : '' }}">
                        Home
                    </a>

                    {{-- Blog Dropdown --}}
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button type="button" @click="open = !open" class="flex items-center gap-1 text-gray-600 hover:text-gray-900 transition-colors font-medium {{ request()->is('blog*') ? 'text-blue-600' : '' }}">
                            <span>Blog</span>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                             style="display: none;">
                            <div class="py-2">
                                <a href="/blog" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    Semua Artikel
                                </a>
                                @if($categories->count() > 0)
                                    <div class="border-t border-gray-100 my-2"></div>
                                    @foreach($categories as $category)
                                        <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Pages --}}
                    @foreach($headerPages as $headerPage)
                        <a href="{{ route('pages.show', $headerPage->slug) }}" class="text-gray-600 hover:text-gray-900 transition-colors font-medium {{ request()->is('pages/' . $headerPage->slug) ? 'text-blue-600' : '' }}">
                            {{ $headerPage->title }}
                        </a>
                    @endforeach
                </nav>
            </div>

            {{-- Right Side Actions --}}
            <div class="flex items-center gap-4">
                {{-- Search Button --}}
                <button type="button" class="hidden md:flex items-center gap-2 px-3 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Search</span>
                </button>

                {{-- Admin Link --}}
                <a href="/admin" class="hidden md:inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Admin
                </a>

                {{-- Mobile Menu Button --}}
                <button type="button" class="md:hidden p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" id="mobile-menu-button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Navigation --}}
        <nav class="md:hidden hidden pb-4" id="mobile-menu">
            <div class="flex flex-col gap-2">
                <a href="/" class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors {{ request()->is('/') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Home
                </a>

                {{-- Blog with categories --}}
                <div x-data="{ openBlog: false }">
                    <button type="button" @click="openBlog = !openBlog" class="w-full px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors flex items-center justify-between {{ request()->is('blog*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <span>Blog</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openBlog }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openBlog"
                         x-transition
                         class="ml-4 mt-1 space-y-1"
                         style="display: none;">
                        <a href="/blog" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                            Semua Artikel
                        </a>
                        @if($categories->count() > 0)
                            @foreach($categories as $category)
                                <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Dynamic Pages --}}
                @foreach($headerPages as $headerPage)
                    <a href="{{ route('pages.show', $headerPage->slug) }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors {{ request()->is('pages/' . $headerPage->slug) ? 'text-blue-600 bg-blue-50' : '' }}">
                        {{ $headerPage->title }}
                    </a>
                @endforeach

                <div class="border-t border-gray-200 my-2"></div>
                <a href="/admin" class="px-4 py-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors font-medium">
                    Admin Panel
                </a>
            </div>
        </nav>
    </div>
</header>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
});
</script>
@endpush
