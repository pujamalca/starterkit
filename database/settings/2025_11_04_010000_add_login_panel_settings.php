<?php

use App\Settings\LandingPageSettings;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $defaultFeatures = json_encode(LandingPageSettings::defaultLoginFeatures());

        $this->migrator->add('landing_page.login_show_panel', true);
        $this->migrator->add('landing_page.login_panel_logo', null);
        $this->migrator->add('landing_page.login_panel_heading', 'Welcome Back!');
        $this->migrator->add('landing_page.login_panel_subheading', 'Sign in to access your admin dashboard and manage your application.');
        $this->migrator->add('landing_page.login_panel_description', null);
        $this->migrator->add('landing_page.login_panel_features', $defaultFeatures);
        $this->migrator->add('landing_page.login_panel_gradient_from', null);
        $this->migrator->add('landing_page.login_panel_gradient_to', null);
        $this->migrator->add('landing_page.login_enable_registration', true);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('landing_page.login_show_panel');
        $this->migrator->deleteIfExists('landing_page.login_panel_logo');
        $this->migrator->deleteIfExists('landing_page.login_panel_heading');
        $this->migrator->deleteIfExists('landing_page.login_panel_subheading');
        $this->migrator->deleteIfExists('landing_page.login_panel_description');
        $this->migrator->deleteIfExists('landing_page.login_panel_features');
        $this->migrator->deleteIfExists('landing_page.login_panel_gradient_from');
        $this->migrator->deleteIfExists('landing_page.login_panel_gradient_to');
        $this->migrator->deleteIfExists('landing_page.login_enable_registration');
    }
};
