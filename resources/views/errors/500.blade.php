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
    <title>500 - Server Error | {{ $siteName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-red-50 via-white to-orange-50 antialiased">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-4xl w-full text-center">
            {{-- Illustration --}}
            <div class="mb-8 flex justify-center">
                <svg class="w-full max-w-md h-auto" viewBox="0 0 800 600" fill="none" xmlns="http://www.w3.org/2000/svg">
                    {{-- Background elements --}}
                    <circle cx="400" cy="300" r="200" fill="#FEF2F2" opacity="0.5"/>
                    <circle cx="150" cy="150" r="80" fill="#FEE2E2" opacity="0.3"/>
                    <circle cx="650" cy="450" r="100" fill="#FECACA" opacity="0.3"/>

                    {{-- 500 Text as background --}}
                    <text x="400" y="320" text-anchor="middle" fill="#FEE2E2" font-size="200" font-weight="bold" opacity="0.3">500</text>

                    {{-- Server/Computer with error --}}
                    <g transform="translate(300, 200)">
                        {{-- Server rack --}}
                        <rect x="20" y="100" width="160" height="180" rx="8" fill="#374151"/>
                        <rect x="30" y="110" width="140" height="50" rx="4" fill="#1F2937"/>
                        <rect x="30" y="170" width="140" height="50" rx="4" fill="#1F2937"/>
                        <rect x="30" y="230" width="140" height="40" rx="4" fill="#1F2937"/>

                        {{-- Server lights/indicators (red for error) --}}
                        <circle cx="45" cy="135" r="4" fill="#EF4444"/>
                        <circle cx="60" cy="135" r="4" fill="#EF4444"/>
                        <circle cx="75" cy="135" r="4" fill="#DC2626"/>
                        <circle cx="45" cy="195" r="4" fill="#EF4444"/>
                        <circle cx="60" cy="195" r="4" fill="#DC2626"/>
                        <circle cx="45" cy="250" r="4" fill="#EF4444"/>

                        {{-- Error symbol on screen --}}
                        <g transform="translate(100, 180)">
                            <circle cx="0" cy="0" r="20" fill="#FEE2E2"/>
                            <text x="0" y="8" text-anchor="middle" fill="#DC2626" font-size="28" font-weight="bold">!</text>
                        </g>

                        {{-- Smoke/steam coming from server --}}
                        <g opacity="0.5">
                            <ellipse cx="60" cy="90" rx="15" ry="10" fill="#9CA3AF"/>
                            <ellipse cx="80" cy="70" rx="20" ry="15" fill="#9CA3AF"/>
                            <ellipse cx="100" cy="85" rx="12" ry="8" fill="#9CA3AF"/>
                            <ellipse cx="120" cy="60" rx="18" ry="12" fill="#9CA3AF"/>
                            <ellipse cx="140" cy="75" rx="15" ry="10" fill="#9CA3AF"/>
                        </g>
                    </g>

                    {{-- Person looking worried --}}
                    <g transform="translate(500, 280)">
                        {{-- Body --}}
                        <ellipse cx="50" cy="90" rx="40" ry="50" fill="#F59E0B"/>

                        {{-- Legs --}}
                        <rect x="32" y="135" width="16" height="55" rx="8" fill="#D97706"/>
                        <rect x="52" y="135" width="16" height="55" rx="8" fill="#D97706"/>

                        {{-- Arms (one up to head in worried gesture) --}}
                        <rect x="15" y="65" width="14" height="45" rx="7" fill="#EA580C" transform="rotate(-45 22 65)"/>
                        <rect x="71" y="65" width="14" height="45" rx="7" fill="#EA580C"/>

                        {{-- Head --}}
                        <circle cx="50" cy="35" r="22" fill="#FED7AA"/>

                        {{-- Hair --}}
                        <ellipse cx="50" cy="22" rx="23" ry="18" fill="#78350F"/>

                        {{-- Worried face --}}
                        <circle cx="42" cy="32" r="2" fill="#1F2937"/>
                        <circle cx="58" cy="32" r="2" fill="#1F2937"/>
                        <path d="M 42 42 Q 50 38 58 42" stroke="#1F2937" stroke-width="2" fill="none"/>
                    </g>

                    {{-- Floating error symbols --}}
                    <g opacity="0.3">
                        <text x="150" y="450" fill="#DC2626" font-size="35">⚠</text>
                        <text x="620" y="220" fill="#DC2626" font-size="30">⚠</text>
                        <text x="200" y="200" fill="#DC2626" font-size="25">✖</text>
                        <text x="650" y="400" fill="#DC2626" font-size="28">✖</text>
                    </g>
                </svg>
            </div>

            {{-- Error Message --}}
            <div class="space-y-4">
                <h1 class="text-6xl sm:text-7xl font-bold text-gray-800">
                    500
                </h1>
                <h2 class="text-2xl sm:text-3xl font-semibold text-gray-700">
                    Internal Server Error
                </h2>
                <p class="text-lg text-gray-600 max-w-md mx-auto">
                    Something went wrong on our end. Our team has been notified and we're working to fix it!
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ url('/') }}"
                   class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition duration-150 ease-in-out transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Back to Home
                </a>

                <button onclick="location.reload()"
                        class="inline-flex items-center px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-lg shadow-md border border-gray-300 transition duration-150 ease-in-out transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Try Again
                </button>
            </div>

            {{-- Additional Help --}}
            <div class="mt-12 text-sm text-gray-500">
                <p>If this problem persists, please <a href="{{ url('/') }}" class="text-red-600 hover:text-red-700 underline font-medium">contact our support team</a></p>
            </div>
        </div>
    </div>
</body>
</html>
