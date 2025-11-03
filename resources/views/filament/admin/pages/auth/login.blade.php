@php
    $generalSettings = app(\App\Settings\GeneralSettings::class);
    $siteName = $generalSettings->site_name ?? config('app.name', 'Laravel Starter Kit');
@endphp

<style>
    /* Override Filament's page.simple default styles */
    .fi-simple-page {
        height: 100vh !important;
        max-height: 100vh !important;
        overflow: hidden !important;
        background-color: rgb(17 24 39) !important; /* gray-900 - Filament dark background */
    }

    .fi-simple-main {
        background-color: rgb(17 24 39) !important; /* gray-900 */
    }

    /* Override all Filament background colors to match our design */
    .fi-simple-page-content,
    .fi-simple-main > div,
    .fi-simple-main .fi-grid,
    .fi-simple-main form {
        background-color: transparent !important;
    }

    /* Custom hover state for button */
    a.hover\:bg-gray-750:hover {
        background-color: rgb(31 41 55) !important; /* Between gray-800 and gray-700 */
    }

    /* Hide Filament's default heading and logo - AGGRESSIVE */
    .fi-simple-page-heading,
    .fi-simple-header,
    header.fi-simple-header,
    .fi-simple-page-content > header,
    .fi-simple-page-content > .fi-simple-header,
    .fi-logo,
    img[alt*="logo"],
    .fi-simple-header-heading,
    .fi-simple-header-subheading,
    .fi-simple-main > div > div:first-child:has(h2),
    .fi-simple-main > div:first-child {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        overflow: hidden !important;
        opacity: 0 !important;
    }

    /* Ensure all backgrounds are consistent */
    body,
    .fi-simple-page-content,
    .fi-simple-page-content > div:first-child,
    div[class*="fi-simple"] {
        background-color: rgb(17 24 39) !important;
    }

    /* Hide "or sign up for an account" text - multiple selectors to catch all cases */
    .fi-simple-page h2 + p,
    .fi-simple-page-heading + p,
    .fi-simple-main h2 + p,
    .fi-simple-main p:has(a[href*="register"]),
    .fi-simple-main > div > p,
    h2.fi-simple-page-heading + p {
        display: none !important;
    }

    /* Hide any paragraph that contains "sign up for an account" */
    p:has(a[href*="register"]):not(.fi-simple-footer) {
        display: none !important;
    }

    /* Target the specific paragraph by content */
    .fi-simple-main p {
        display: none !important;
    }

    /* Show back our custom footer */
    .fi-simple-main p:last-child {
        display: block !important;
    }
</style>

<script>
    // Fallback: Hide unwanted elements using JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Hide "or sign up for an account" text
        const paragraphs = document.querySelectorAll('.fi-simple-main p, .fi-simple-page p');
        paragraphs.forEach(p => {
            if (p.textContent.toLowerCase().includes('sign up for an account')) {
                p.style.display = 'none';
            }
        });

        // Aggressively hide "Sign in" heading and logo
        const headings = document.querySelectorAll('h1, h2, .fi-simple-page-heading, .fi-simple-header-heading, .fi-logo, img[alt*="logo"]');
        headings.forEach(h => {
            const text = h.textContent.trim().toLowerCase();
            if (text === 'sign in' || text === '' || h.tagName === 'IMG') {
                h.style.display = 'none';
                h.style.visibility = 'hidden';
                h.style.height = '0';
                h.style.opacity = '0';
                // Also hide parent if it's just a wrapper
                if (h.parentElement && h.parentElement.children.length <= 2) {
                    h.parentElement.style.display = 'none';
                }
            }
        });

        // Hide header completely
        const headers = document.querySelectorAll('header, .fi-simple-header, header.fi-simple-header');
        headers.forEach(header => {
            header.style.display = 'none';
            header.style.visibility = 'hidden';
            header.style.height = '0';
            header.style.opacity = '0';
        });

        // Hide any div that contains logo or heading
        const divs = document.querySelectorAll('.fi-simple-page-content > div, .fi-simple-main > div');
        divs.forEach(div => {
            if (div.querySelector('h1, h2, .fi-logo, img[alt*="logo"]')) {
                const hasOnlyHeader = div.textContent.trim().toLowerCase() === 'sign in' ||
                                     div.querySelector('img[alt*="logo"]') !== null;
                if (hasOnlyHeader) {
                    div.style.display = 'none';
                }
            }
        });
    });
</script>

<x-filament-panels::page.simple>
    <div class="flex h-screen">
        {{-- Left Side - Image/Illustration --}}
        <div class="hidden lg:flex lg:w-1/2 bg-gray-900 relative overflow-hidden">
            {{-- Decorative elements --}}
            <div class="absolute inset-0 opacity-5">
                <div class="absolute top-20 left-20 w-72 h-72 bg-primary-500 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 right-20 w-96 h-96 bg-purple-500 rounded-full blur-3xl"></div>
            </div>

            {{-- Content --}}
            <div class="relative z-10 flex flex-col justify-center pb-5 items-center w-full text-white">
                <div class="max-w-md text-center">
                    {{-- Logo or Icon --}}
                    <div class="flex justify-center mb-8">
                        <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>

                    <h1 class="text-4xl font-bold mb-4">
                        Welcome Back!
                    </h1>
                    <p class="text-xl text-gray-300">
                        Sign in to access your admin dashboard and manage your application.
                    </p>

                    {{-- Features --}}
                    <div class="mt-2 space-y-4 text-left">
                        <div class="flex items-start space-x-3">
                            <svg class="w-6 h-6 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-white">Secure & Protected</h3>
                                <p class="text-sm text-gray-400">Your data is encrypted and secure</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <svg class="w-6 h-6 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-white">Easy Management</h3>
                                <p class="text-sm text-gray-400">Intuitive admin interface</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <svg class="w-6 h-6 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-white">24/7 Access</h3>
                                <p class="text-sm text-gray-400">Manage from anywhere, anytime</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side - Login Form --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-900 overflow-y-auto">
            <div class="w-full max-w-md">
                {{-- Logo on mobile --}}
                <div class="lg:hidden text-center mb-8">
                    <h1 class="text-3xl font-bold text-white">
                        {{ $siteName }}
                    </h1>
                    <p class="mt-2 text-sm text-gray-400">
                        Sign in to your account
                    </p>
                </div>

                {{-- Login Form --}}
                <div>
                    @if (filament()->hasLogin())
                        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

                        <x-filament-panels::form wire:submit="authenticate">
                            {{ $this->form }}

                            <x-filament-panels::form.actions
                                :actions="$this->getCachedFormActions()"
                                :full-width="$this->hasFullWidthFormActions()"
                            />
                        </x-filament-panels::form>

                        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
                    @endif

                    {{-- Additional Links --}}
                    <div class="mt-6 text-center">
                        @if (filament()->hasRegistration())
                            <a href="{{ filament()->getRegistrationUrl() }}"
                               class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-300 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-750 hover:border-gray-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-primary-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                Create New Account
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Footer --}}
                <div class="mt-8 pt-8 border-t border-gray-800 text-center text-xs text-gray-600">
                    <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page.simple>
