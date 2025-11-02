@php
    $settings = app(\App\Settings\GeneralSettings::class);
    $siteName = $settings->site_name ?? config('app.name', 'Starter Kit');
    $siteLogo = $settings->site_logo;

    // Get blog categories
    $categories = \App\Models\Category::orderBy('name')->get();

    // Get pages for header
    $headerPages = \App\Models\Page::showInHeader()->get();

    // Get navigation menus from landing settings
    $landingSettings = app(\App\Settings\LandingPageSettings::class);
    $navigationMenus = [];
    if (!empty($landingSettings->navigation_menus)) {
        $decoded = json_decode($landingSettings->navigation_menus, true);
        $allMenus = is_array($decoded) ? collect($decoded)->where('show', true)->sortBy('order')->values()->all() : [];

        // Group menus by position
        $navigationMenus = [
            'left' => collect($allMenus)->where('position', 'left')->values()->all(),
            'center' => collect($allMenus)->where('position', 'center')->values()->all(),
            'right' => collect($allMenus)->where('position', 'right')->values()->all(),
        ];
    } else {
        $navigationMenus = ['left' => [], 'center' => [], 'right' => []];
    }
@endphp

<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center h-16">
            {{-- Logo / Brand --}}
            <div class="flex items-center gap-8 flex-shrink-0">
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

                {{-- Desktop Navigation LEFT --}}
                <nav class="hidden md:flex items-center gap-6">
                    @foreach($navigationMenus['left'] as $menu)
                        @if($menu['type'] === 'blog_dropdown')
                            {{-- Blog Dropdown --}}
                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                <button type="button" @click="open = !open" class="flex items-center gap-1 text-gray-600 hover:text-gray-900 transition-colors font-medium {{ request()->is('blog*') ? 'text-blue-600' : '' }}">
                                    <span>{{ $menu['label'] }}</span>
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
                                     class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                    <div class="py-2">
                                        <a href="{{ $menu['url'] }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
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
                        @elseif(!empty($menu['children']) && count($menu['children']) > 0)
                            {{-- Menu with Children (Sub Menu) --}}
                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                <button type="button" @click="open = !open" class="flex items-center gap-1 text-gray-600 hover:text-gray-900 transition-colors font-medium">
                                    <span>{{ $menu['label'] }}</span>
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
                                     class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                    <div class="py-2">
                                        @foreach($menu['children'] as $child)
                                            @if($child['show'] ?? true)
                                                <a href="{{ $child['url'] }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                                    {{ $child['label'] }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Regular Link --}}
                            <a href="{{ $menu['url'] }}" class="text-gray-600 hover:text-gray-900 transition-colors font-medium {{ request()->is(trim($menu['url'], '/')) ? 'text-blue-600' : '' }}">
                                {{ $menu['label'] }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Dynamic Pages --}}
                    @foreach($headerPages as $headerPage)
                        <a href="{{ route('pages.show', $headerPage->slug) }}" class="text-gray-600 hover:text-gray-900 transition-colors font-medium {{ request()->is('pages/' . $headerPage->slug) ? 'text-blue-600' : '' }}">
                            {{ $headerPage->title }}
                        </a>
                    @endforeach
                </nav>
            </div>

            {{-- Desktop Navigation CENTER --}}
            <nav class="hidden md:flex items-center gap-6 flex-1 justify-center">
                @foreach($navigationMenus['center'] as $menu)
                    @if($menu['type'] === 'blog_dropdown')
                        {{-- Blog Dropdown --}}
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button type="button" @click="open = !open" class="flex items-center gap-1 text-gray-600 hover:text-gray-900 transition-colors font-medium {{ request()->is('blog*') ? 'text-blue-600' : '' }}">
                                <span>{{ $menu['label'] }}</span>
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
                                 class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="py-2">
                                    <a href="{{ $menu['url'] }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
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
                    @else
                        {{-- Regular Link --}}
                        <a href="{{ $menu['url'] }}" class="text-gray-600 hover:text-gray-900 transition-colors font-medium {{ request()->is(trim($menu['url'], '/')) ? 'text-blue-600' : '' }}">
                            {{ $menu['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>

            {{-- Desktop Navigation RIGHT + Actions --}}
            <div class="flex items-center gap-4 flex-shrink-0">
                {{-- Navigation RIGHT --}}
                <nav class="hidden md:flex items-center gap-6">
                    @foreach($navigationMenus['right'] as $menu)
                        @if($menu['type'] === 'blog_dropdown')
                            {{-- Blog Dropdown --}}
                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                <button type="button" @click="open = !open" class="flex items-center gap-1 text-gray-600 hover:text-gray-900 transition-colors font-medium {{ request()->is('blog*') ? 'text-blue-600' : '' }}">
                                    <span>{{ $menu['label'] }}</span>
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
                                     class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                    <div class="py-2">
                                        <a href="{{ $menu['url'] }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
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
                        @else
                            {{-- Regular Link --}}
                            <a href="{{ $menu['url'] }}" class="text-gray-600 hover:text-gray-900 transition-colors font-medium {{ request()->is(trim($menu['url'], '/')) ? 'text-blue-600' : '' }}">
                                {{ $menu['label'] }}
                            </a>
                        @endif
                    @endforeach
                </nav>

                {{-- Search Button --}}
                @if($landingSettings->show_search)
                <button
                    type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-search'))"
                    class="hidden md:flex items-center gap-2 px-3 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors cursor-pointer z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Search</span>
                </button>
                @endif

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
                @php
                    // Combine all menus for mobile (left, center, right)
                    $allMobileMenus = array_merge(
                        $navigationMenus['left'] ?? [],
                        $navigationMenus['center'] ?? [],
                        $navigationMenus['right'] ?? []
                    );
                @endphp
                @foreach($allMobileMenus as $menu)
                    @if($menu['type'] === 'blog_dropdown')
                        {{-- Blog with categories --}}
                        <div x-data="{ openBlog: false }">
                            <button type="button" @click="openBlog = !openBlog" class="w-full px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors flex items-center justify-between {{ request()->is('blog*') ? 'text-blue-600 bg-blue-50' : '' }}">
                                <span>{{ $menu['label'] }}</span>
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openBlog }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="openBlog"
                                 x-transition
                                 class="ml-4 mt-1 space-y-1">
                                <a href="{{ $menu['url'] }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
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
                    @else
                        {{-- Regular Link --}}
                        <a href="{{ $menu['url'] }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors {{ request()->is(trim($menu['url'], '/')) ? 'text-blue-600 bg-blue-50' : '' }}">
                            {{ $menu['label'] }}
                        </a>
                    @endif
                @endforeach

                {{-- Dynamic Pages --}}
                @foreach($headerPages as $headerPage)
                    <a href="{{ route('pages.show', $headerPage->slug) }}" class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors {{ request()->is('pages/' . $headerPage->slug) ? 'text-blue-600 bg-blue-50' : '' }}">
                        {{ $headerPage->title }}
                    </a>
                @endforeach

                <div class="border-t border-gray-200 my-2"></div>

                @if($landingSettings->show_search)
                <button
                    type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-search'))"
                    class="w-full px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors text-left flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Search</span>
                </button>
                @endif

                <a href="/admin" class="px-4 py-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors font-medium">
                    Admin Panel
                </a>
            </div>
        </nav>
    </div>
</header>

{{-- Search Modal --}}
@if($landingSettings->show_search)
<div x-data="{
    open: false,
    query: '',
    results: [],
    loading: false,
    async search() {
        if (this.query.length < 2) {
            this.results = [];
            return;
        }
        this.loading = true;
        try {
            const response = await fetch(`/api/search?q=${encodeURIComponent(this.query)}`);
            const data = await response.json();
            this.results = data.data || [];
        } catch (error) {
            console.error('Search error:', error);
            this.results = [];
        } finally {
            this.loading = false;
        }
    },
    goToFirst() {
        if (this.results.length > 0) {
            window.location.href = '/blog/' + this.results[0].slug;
        }
    }
}"
@open-search.window="open = true"
@keydown.escape.window="open = false"
x-show="open"
x-cloak
class="fixed inset-0 z-50 overflow-y-auto backdrop-blur-sm bg-white/10"
style="display: none;"
@click="open = false">
    {{-- Modal Container --}}
    <div class="flex items-start justify-center min-h-screen pt-20 px-4" @click.stop>
        <div x-show="open" x-transition class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl border border-gray-200">
            {{-- Search Input --}}
            <div class="p-4 border-b border-gray-200">
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           x-model="query"
                           @input.debounce.300ms="search()"
                           @keydown.enter="goToFirst()"
                           placeholder="Cari artikel..."
                           class="w-full pl-12 pr-4 py-3 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-lg"
                           autofocus>
                    <button @click="open = false" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Search Results --}}
            <div class="max-h-96 overflow-y-auto">
                {{-- Loading --}}
                <div x-show="loading" class="p-8 text-center">
                    <div class="inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    <p class="mt-4 text-gray-600">Mencari...</p>
                </div>

                {{-- No Results --}}
                <div x-show="!loading && query && results.length === 0" class="p-8 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-4 text-gray-600">Tidak ada hasil ditemukan</p>
                    <p class="mt-2 text-sm text-gray-500">Coba kata kunci lain</p>
                </div>

                {{-- Results List --}}
                <div x-show="!loading && results.length > 0" class="divide-y divide-gray-100">
                    <template x-for="(result, index) in results" :key="result.slug">
                        <a :href="'/blog/' + result.slug"
                           class="block p-4 hover:bg-gray-50 transition-colors">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1" x-text="result.title"></h3>
                            <p class="text-sm text-gray-600 mb-2 line-clamp-2" x-text="result.excerpt"></p>
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                <span x-text="result.category"></span>
                                <span>•</span>
                                <span x-text="result.date"></span>
                            </div>
                        </a>
                    </template>
                </div>

                {{-- Empty State --}}
                <div x-show="!loading && !query" class="p-8 text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <p class="mt-4">Mulai ketik untuk mencari artikel...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

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
