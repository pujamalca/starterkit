<footer class="bg-gray-900 text-white mt-auto">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            {{-- Footer Column 1 - About --}}
            <div class="md:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">S</span>
                    </div>
                    <h3 class="text-xl font-bold">{{ config('app.name', 'Starter Kit') }}</h3>
                </div>
                <p class="text-gray-400 text-sm mb-4 max-w-md">
                    Laravel Starter Kit yang lengkap dengan Filament Admin Panel, API RESTful, Content Management System, dan fitur-fitur modern lainnya untuk mempercepat development aplikasi web Anda.
                </p>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="GitHub">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="LinkedIn">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Footer Column 2 - Quick Links --}}
            <div>
                <h3 class="text-lg font-bold mb-4">Link Cepat</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="/" class="text-gray-400 hover:text-white transition-colors">Home</a></li>
                    <li><a href="/blog" class="text-gray-400 hover:text-white transition-colors">Blog</a></li>
                    <li><a href="/pages/tentang-kami" class="text-gray-400 hover:text-white transition-colors">Tentang Kami</a></li>
                    <li><a href="/pages/kontak" class="text-gray-400 hover:text-white transition-colors">Kontak</a></li>
                    <li><a href="/pages/faq" class="text-gray-400 hover:text-white transition-colors">FAQ</a></li>
                </ul>
            </div>

            {{-- Footer Column 3 - Legal --}}
            <div>
                <h3 class="text-lg font-bold mb-4">Legal</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="/pages/kebijakan-privasi" class="text-gray-400 hover:text-white transition-colors">Kebijakan Privasi</a></li>
                    <li><a href="/pages/syarat-ketentuan" class="text-gray-400 hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                    <li><a href="/admin" class="text-gray-400 hover:text-white transition-colors">Admin Panel</a></li>
                    <li><a href="/api/documentation" class="text-gray-400 hover:text-white transition-colors">API Documentation</a></li>
                </ul>
            </div>
        </div>

        {{-- Footer Bottom --}}
        <div class="border-t border-gray-800 pt-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-400">
                    &copy; {{ now()->year }} {{ config('app.name', 'Laravel Starter Kit') }}. All rights reserved.
                </p>
                <div class="flex items-center gap-4 text-sm text-gray-400">
                    <span>Built with Laravel {{ app()->version() }}</span>
                    <span>•</span>
                    <span>Powered by Filament</span>
                </div>
            </div>
        </div>
    </div>
</footer>
