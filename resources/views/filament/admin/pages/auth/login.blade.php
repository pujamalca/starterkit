@php
    $generalSettings = app(\App\Settings\GeneralSettings::class);
    $siteName = $generalSettings->site_name ?? config('app.name', 'Laravel Starter Kit');
    $landingSettings = app(\App\Settings\LandingPageSettings::class);

    $loginShowPanel = $landingSettings->login_show_panel ?? true;
    $loginHeading = $landingSettings->login_panel_heading ?? 'Welcome Back!';
    $loginSubheading = $landingSettings->login_panel_subheading ?? 'Sign in to access your admin dashboard and manage your application.';
    $loginDescription = $landingSettings->login_panel_description ?? null;

    $loginFeatures = [];
    if (! empty($landingSettings->login_panel_features)) {
        $decoded = json_decode($landingSettings->login_panel_features, true);
        $loginFeatures = is_array($decoded) ? $decoded : [];
    }
    if (empty($loginFeatures)) {
        $loginFeatures = \App\Settings\LandingPageSettings::defaultLoginFeatures();
    }

    $gradientVars = [];
    if (! empty($landingSettings->login_panel_gradient_from) && preg_match('/^#(?:[0-9a-fA-F]{3}){1,2}$/', $landingSettings->login_panel_gradient_from)) {
        $gradientVars[] = '--login-gradient-from: ' . $landingSettings->login_panel_gradient_from;
    }
    if (! empty($landingSettings->login_panel_gradient_to) && preg_match('/^#(?:[0-9a-fA-F]{3}){1,2}$/', $landingSettings->login_panel_gradient_to)) {
        $gradientVars[] = '--login-gradient-to: ' . $landingSettings->login_panel_gradient_to;
    }
    $loginPanelStyle = empty($gradientVars) ? null : implode('; ', $gradientVars);

    $loginPanelLogo = $landingSettings->login_panel_logo ? asset('storage/' . $landingSettings->login_panel_logo) : null;
    $showRegisterButton = ($landingSettings->login_enable_registration ?? true) && filament()->hasRegistration();
    $rightPanelWidth = $loginShowPanel ? 'lg:w-1/2' : 'lg:w-full';
@endphp

<x-filament-panels::page.simple>
    <div class="flex flex-col lg:flex-row">
        {{-- Left Side - Welcome Section --}}
        @if ($loginShowPanel)
            <div class="hidden lg:flex lg:w-1/2 flex-col items-center justify-center p-12 fi-login-hero" @if ($loginPanelStyle) style="{{ $loginPanelStyle }}" @endif>
                <div class="w-full max-w-md">
                    @if ($loginPanelLogo)
                        <div class="flex justify-center mb-8">
                            <img src="{{ $loginPanelLogo }}" alt="{{ $loginHeading }}" class="max-h-24 w-auto object-contain">
                        </div>
                    @else
                        <div class="flex justify-center mb-8">
                            <svg class="w-20 h-20 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    @endif

                    <h1 class="text-4xl font-bold text-center mb-4 text-gray-900 dark:text-white">
                        {{ $loginHeading }}
                    </h1>

                    @if (filled($loginSubheading))
                        <p class="text-xl text-center text-gray-700 dark:text-gray-200 mb-6">
                            {{ $loginSubheading }}
                        </p>
                    @endif

                    @if (filled($loginDescription))
                        <p class="text-center text-gray-700 dark:text-gray-300 mb-10">
                            {{ $loginDescription }}
                        </p>
                    @endif

                    {{-- Features --}}
                    <div class="space-y-6">
                        @foreach ($loginFeatures as $feature)
                            @php
                                $featureTitle = trim($feature['title'] ?? '');
                                $featureDescription = trim($feature['description'] ?? '');
                            @endphp

                            @if ($featureTitle === '' && $featureDescription === '')
                                @continue
                            @endif

                            <div class="flex items-start gap-4">
                                <svg class="w-6 h-6 flex-shrink-0 mt-1 text-gray-900 dark:text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    @if ($featureTitle !== '')
                                        <h3 class="font-semibold text-lg text-gray-900 dark:text-white">{{ $featureTitle }}</h3>
                                    @endif
                                    @if ($featureDescription !== '')
                                        <p class="text-gray-700 dark:text-gray-300">{{ $featureDescription }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Right Side - Login Form --}}
        <div class="{{ $rightPanelWidth }} flex items-center justify-center p-8 lg:p-16">
            <div class="w-full max-w-xl">
                {{-- Mobile Logo --}}
                <div class="lg:hidden text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $siteName }}
                    </h1>
                </div>

                {{-- Login Form Content --}}
                {{ $this->content }}

                @if ($showRegisterButton)
                    <div class="mt-6">
                        <x-filament::button
                            tag="a"
                            href="{{ filament()->getRegistrationUrl() }}"
                            color="gray"
                            class="w-full"
                        >
                            {{ __('filament-panels::auth/pages/login.actions.register.label') }}
                        </x-filament::button>
                    </div>
                @endif

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
