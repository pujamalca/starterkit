@php
    $generalSettings = app(\App\Settings\GeneralSettings::class);
    $defaultTitle = $generalSettings->site_name ?? config('app.name', 'Laravel Starter Kit');
    $defaultDescription = $generalSettings->site_description ?? '';
    $siteFavicon = $generalSettings->site_favicon;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $defaultTitle)</title>

    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
    @elseif($defaultDescription)
        <meta name="description" content="{{ $defaultDescription }}">
    @endif

    @if($siteFavicon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $siteFavicon) }}">
    @endif

    @hasSection('canonical_url')
        <link rel="canonical" href="@yield('canonical_url')">
    @endif

    @stack('meta')

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col antialiased">

    {{-- Header / Navbar --}}
    @include('layouts.partials.header')

    {{-- Alert/Preview Banner --}}
    @if (session('success'))
        <div class="bg-green-50 border-b border-green-200">
            <div class="container mx-auto px-4 py-3">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm text-green-800">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border-b border-red-200">
            <div class="container mx-auto px-4 py-3">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm text-red-800">{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif

    @yield('banner')

    {{-- Main Content --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.partials.footer')

    @stack('scripts')
</body>
</html>
