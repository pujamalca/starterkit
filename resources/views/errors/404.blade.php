@php
    $generalSettings = app(\App\Settings\GeneralSettings::class);
    $siteName = $generalSettings->site_name ?? config('app.name', 'Laravel Starter Kit');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>404 - Page Not Found | {{ $siteName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 antialiased">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-4xl w-full text-center">
            {{-- Illustration --}}
            <div class="mb-8 flex justify-center">
                <svg class="w-full max-w-md h-auto" viewBox="0 0 800 600" fill="none" xmlns="http://www.w3.org/2000/svg">
                    {{-- Background elements --}}
                    <circle cx="400" cy="300" r="200" fill="#EEF2FF" opacity="0.5"/>
                    <circle cx="150" cy="150" r="80" fill="#DBEAFE" opacity="0.3"/>
                    <circle cx="650" cy="450" r="100" fill="#E0E7FF" opacity="0.3"/>

                    {{-- 404 Text as background --}}
                    <text x="400" y="320" text-anchor="middle" fill="#E5E7EB" font-size="200" font-weight="bold" opacity="0.3">404</text>

                    {{-- Person looking through binoculars --}}
                    <g transform="translate(300, 250)">
                        {{-- Body --}}
                        <ellipse cx="50" cy="120" rx="45" ry="55" fill="#6366F1"/>

                        {{-- Legs --}}
                        <rect x="30" y="165" width="18" height="60" rx="9" fill="#4F46E5"/>
                        <rect x="52" y="165" width="18" height="60" rx="9" fill="#4F46E5"/>

                        {{-- Arms --}}
                        <rect x="10" y="80" width="15" height="50" rx="7" fill="#8B5CF6" transform="rotate(-30 17 80)"/>
                        <rect x="75" y="80" width="15" height="50" rx="7" fill="#8B5CF6" transform="rotate(30 83 80)"/>

                        {{-- Head --}}
                        <circle cx="50" cy="60" r="25" fill="#FDE68A"/>

                        {{-- Hair --}}
                        <ellipse cx="50" cy="45" rx="26" ry="20" fill="#92400E"/>

                        {{-- Binoculars --}}
                        <g transform="translate(25, 50)">
                            <rect x="0" y="0" width="15" height="20" rx="7" fill="#1F2937"/>
                            <rect x="35" y="0" width="15" height="20" rx="7" fill="#1F2937"/>
                            <rect x="15" y="8" width="20" height="4" fill="#374151"/>
                            <circle cx="7.5" cy="10" r="6" fill="#60A5FA" opacity="0.5"/>
                            <circle cx="42.5" cy="10" r="6" fill="#60A5FA" opacity="0.5"/>
                        </g>
                    </g>

                    {{-- Floating elements --}}
                    <circle cx="200" cy="400" r="8" fill="#A78BFA" opacity="0.4"/>
                    <circle cx="600" cy="200" r="12" fill="#818CF8" opacity="0.4"/>
                    <circle cx="700" cy="350" r="6" fill="#C4B5FD" opacity="0.4"/>
                    <circle cx="100" cy="500" r="10" fill="#A78BFA" opacity="0.4"/>

                    {{-- Question marks --}}
                    <text x="150" y="250" fill="#9CA3AF" font-size="40" opacity="0.5">?</text>
                    <text x="650" y="300" fill="#9CA3AF" font-size="35" opacity="0.5">?</text>
                    <text x="550" y="150" fill="#9CA3AF" font-size="30" opacity="0.5">?</text>
                </svg>
            </div>

            {{-- Error Message --}}
            <div class="space-y-4">
                <h1 class="text-6xl sm:text-7xl font-bold text-gray-800">
                    404
                </h1>
                <h2 class="text-2xl sm:text-3xl font-semibold text-gray-700">
                    Oops! Page Not Found
                </h2>
                <p class="text-lg text-gray-600 max-w-md mx-auto">
                    The page you're looking for seems to have wandered off. Let's get you back on track!
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ url('/') }}"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-150 ease-in-out transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Back to Home
                </a>

                <button onclick="history.back()"
                        class="inline-flex items-center px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-lg shadow-md border border-gray-300 transition duration-150 ease-in-out transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Go Back
                </button>
            </div>

            {{-- Additional Help --}}
            <div class="mt-12 text-sm text-gray-500">
                <p>Need help? <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-700 underline font-medium">Contact Support</a></p>
            </div>
        </div>
    </div>
</body>
</html>
