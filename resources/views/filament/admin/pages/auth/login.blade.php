@php
    $generalSettings = app(\App\Settings\GeneralSettings::class);
    $siteName = $generalSettings->site_name ?? config('app.name', 'Laravel Starter Kit');
@endphp

<x-filament-panels::page.simple>
    <div class="grid lg:grid-cols-5 min-h-screen">
        {{-- Left Side - Welcome Section --}}
        <div class="hidden lg:flex lg:col-span-2 flex-col items-center justify-center p-12 bg-gradient-to-br from-primary-600 to-primary-800 dark:from-primary-700 dark:to-primary-900">
            <div class="w-full max-w-md">
                {{-- Logo Icon --}}
                <div class="flex justify-center mb-8">
                    <svg class="w-20 h-20 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>

                <h1 class="text-4xl font-bold text-center mb-4 text-gray-900 dark:text-white">
                    Welcome Back!
                </h1>
                <p class="text-xl text-center text-gray-700 dark:text-gray-200 mb-12">
                    Sign in to access your admin dashboard and manage your application.
                </p>

                {{-- Features --}}
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <svg class="w-6 h-6 flex-shrink-0 mt-1 text-gray-900 dark:text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900 dark:text-white">Secure & Protected</h3>
                            <p class="text-gray-700 dark:text-gray-300">Your data is encrypted and secure</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <svg class="w-6 h-6 flex-shrink-0 mt-1 text-gray-900 dark:text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900 dark:text-white">Easy Management</h3>
                            <p class="text-gray-700 dark:text-gray-300">Intuitive admin interface</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <svg class="w-6 h-6 flex-shrink-0 mt-1 text-gray-900 dark:text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900 dark:text-white">24/7 Access</h3>
                            <p class="text-gray-700 dark:text-gray-300">Manage from anywhere, anytime</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side - Login Form --}}
        <div class="lg:col-span-3 flex items-center justify-center p-8 lg:p-16">
            <div class="w-full max-w-xl">
                {{-- Mobile Logo --}}
                <div class="lg:hidden text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $siteName }}
                    </h1>
                </div>

                {{-- Login Form Content --}}
                {{ $this->content }}

                {{-- Footer --}}
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-center text-xs text-gray-600 dark:text-gray-400">
                        &copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page.simple>
