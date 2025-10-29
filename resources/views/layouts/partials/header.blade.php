<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            {{-- Logo / Brand --}}
            <div class="flex items-center gap-8">
                <a href="/" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">S</span>
                    </div>
                    <span class="text-xl font-bold text-gray-900">{{ config('app.name', 'Starter Kit') }}</span>
                </a>

                {{-- Desktop Navigation --}}
                <nav class="hidden md:flex items-center gap-6">
                    <a href="/" class="text-gray-600 hover:text-gray-900 transition-colors font-medium {{ request()->is('/') ? 'text-blue-600' : '' }}">
                        Home
                    </a>
                    <a href="/blog" class="text-gray-600 hover:text-gray-900 transition-colors font-medium {{ request()->is('blog*') ? 'text-blue-600' : '' }}">
                        Blog
                    </a>
                    <a href="/pages/tentang-kami" class="text-gray-600 hover:text-gray-900 transition-colors font-medium {{ request()->is('pages/tentang-kami') ? 'text-blue-600' : '' }}">
                        Tentang
                    </a>
                    <a href="/pages/kontak" class="text-gray-600 hover:text-gray-900 transition-colors font-medium {{ request()->is('pages/kontak') ? 'text-blue-600' : '' }}">
                        Kontak
                    </a>
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
                <a href="/blog" class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors {{ request()->is('blog*') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Blog
                </a>
                <a href="/pages/tentang-kami" class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors {{ request()->is('pages/tentang-kami') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Tentang
                </a>
                <a href="/pages/kontak" class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors {{ request()->is('pages/kontak') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Kontak
                </a>
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
