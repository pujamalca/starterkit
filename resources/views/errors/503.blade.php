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
    <title>503 - Service Unavailable | {{ $siteName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-amber-50 via-white to-yellow-50 antialiased">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-4xl w-full text-center">
            {{-- Illustration --}}
            <div class="mb-8 flex justify-center">
                <svg class="w-full max-w-md h-auto" viewBox="0 0 800 600" fill="none" xmlns="http://www.w3.org/2000/svg">
                    {{-- Background elements --}}
                    <circle cx="400" cy="300" r="200" fill="#FFFBEB" opacity="0.5"/>
                    <circle cx="150" cy="150" r="80" fill="#FEF3C7" opacity="0.3"/>
                    <circle cx="650" cy="450" r="100" fill="#FDE68A" opacity="0.3"/>

                    {{-- 503 Text as background --}}
                    <text x="400" y="320" text-anchor="middle" fill="#FEF3C7" font-size="200" font-weight="bold" opacity="0.3">503</text>

                    {{-- Maintenance tools and person --}}
                    <g transform="translate(250, 200)">
                        {{-- Toolbox --}}
                        <rect x="220" y="180" width="80" height="60" rx="4" fill="#DC2626"/>
                        <rect x="220" y="180" width="80" height="15" rx="2" fill="#B91C1C"/>
                        <rect x="250" y="170" width="20" height="15" rx="2" fill="#991B1B"/>
                        <circle cx="235" cy="210" r="3" fill="#FEE2E2"/>
                        <circle cx="250" cy="210" r="3" fill="#FEE2E2"/>
                        <circle cx="265" cy="210" r="3" fill="#FEE2E2"/>
                        <circle cx="280" cy="210" r="3" fill="#FEE2E2"/>

                        {{-- Person with wrench --}}
                        <g transform="translate(100, 80)">
                            {{-- Body --}}
                            <ellipse cx="50" cy="90" rx="42" ry="50" fill="#F59E0B"/>

                            {{-- Legs --}}
                            <rect x="33" y="135" width="16" height="55" rx="8" fill="#D97706"/>
                            <rect x="51" y="135" width="16" height="55" rx="8" fill="#D97706"/>

                            {{-- Left arm holding wrench --}}
                            <rect x="18" y="70" width="14" height="48" rx="7" fill="#EA580C" transform="rotate(-25 25 70)"/>

                            {{-- Right arm --}}
                            <rect x="68" y="75" width="14" height="45" rx="7" fill="#EA580C" transform="rotate(20 75 75)"/>

                            {{-- Head --}}
                            <circle cx="50" cy="35" r="24" fill="#FED7AA"/>

                            {{-- Hair --}}
                            <ellipse cx="50" cy="20" rx="25" ry="18" fill="#78350F"/>

                            {{-- Face --}}
                            <circle cx="42" cy="32" r="2" fill="#1F2937"/>
                            <circle cx="58" cy="32" r="2" fill="#1F2937"/>
                            <path d="M 40 42 Q 50 47 60 42" stroke="#1F2937" stroke-width="2" fill="none"/>

                            {{-- Hard hat --}}
                            <ellipse cx="50" cy="15" rx="28" ry="8" fill="#F59E0B"/>
                            <rect x="47" y="8" width="6" height="8" fill="#FBBF24"/>
                        </g>

                        {{-- Wrench --}}
                        <g transform="translate(95, 130) rotate(-45)">
                            <rect x="0" y="0" width="50" height="12" rx="6" fill="#6B7280"/>
                            <rect x="45" y="-8" width="12" height="28" rx="6" fill="#6B7280"/>
                            <rect x="0" y="3" width="15" height="6" rx="3" fill="#9CA3AF"/>
                        </g>

                        {{-- Gears floating --}}
                        <g opacity="0.4">
                            {{-- Gear 1 --}}
                            <circle cx="40" cy="50" r="25" fill="#D97706"/>
                            <circle cx="40" cy="50" r="15" fill="#FBBF24"/>
                            <rect x="37" y="25" width="6" height="8" fill="#D97706"/>
                            <rect x="37" y="67" width="6" height="8" fill="#D97706"/>
                            <rect x="15" y="47" width="8" height="6" fill="#D97706"/>
                            <rect x="57" y="47" width="8" height="6" fill="#D97706"/>

                            {{-- Gear 2 --}}
                            <circle cx="280" cy="100" r="30" fill="#D97706"/>
                            <circle cx="280" cy="100" r="18" fill="#FBBF24"/>
                            <rect x="277" y="70" width="6" height="10" fill="#D97706"/>
                            <rect x="277" y="120" width="6" height="10" fill="#D97706"/>
                            <rect x="250" y="97" width="10" height="6" fill="#D97706"/>
                            <rect x="300" y="97" width="10" height="6" fill="#D97706"/>
                        </g>
                    </g>

                    {{-- Construction cone --}}
                    <g transform="translate(150, 420)">
                        <rect x="0" y="50" width="60" height="8" fill="#1F2937"/>
                        <path d="M 10 50 L 30 0 L 50 50 Z" fill="#F59E0B"/>
                        <rect x="15" y="15" width="30" height="6" fill="#FBBF24"/>
                        <rect x="15" y="30" width="30" height="6" fill="#FBBF24"/>
                    </g>

                    {{-- Floating maintenance symbols --}}
                    <g opacity="0.3">
                        <text x="600" y="250" fill="#D97706" font-size="35">⚙</text>
                        <text x="180" y="250" fill="#D97706" font-size="30">🔧</text>
                        <text x="650" y="400" fill="#D97706" font-size="28">⚙</text>
                    </g>
                </svg>
            </div>

            {{-- Error Message --}}
            <div class="space-y-4">
                <h1 class="text-6xl sm:text-7xl font-bold text-gray-800">
                    503
                </h1>
                <h2 class="text-2xl sm:text-3xl font-semibold text-gray-700">
                    We'll Be Right Back!
                </h2>
                <p class="text-lg text-gray-600 max-w-md mx-auto">
                    We're currently performing scheduled maintenance to improve your experience. We'll be back online shortly!
                </p>
            </div>

            {{-- Estimated time (if available) --}}
            @if(isset($exception) && method_exists($exception, 'retryAfter'))
                <div class="mt-6">
                    <div class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-800 rounded-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">Expected back in {{ $exception->retryAfter() }} seconds</span>
                    </div>
                </div>
            @endif

            {{-- Action Button --}}
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center items-center">
                <button onclick="location.reload()"
                        class="inline-flex items-center px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-md transition duration-150 ease-in-out transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh Page
                </button>
            </div>

            {{-- Additional Info --}}
            <div class="mt-12 text-sm text-gray-500">
                <p>Thank you for your patience!</p>
            </div>
        </div>
    </div>

    {{-- Auto-refresh script --}}
    <script>
        // Auto-refresh every 30 seconds
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
