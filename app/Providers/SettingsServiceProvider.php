<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only attempt to load settings if the settings table exists
        if (Schema::hasTable('settings')) {
            // Load all settings
            $settings = $this->loadSettings();

            // Configure email settings
            if (isset($settings['email.admin_notification'])) {
                Config::set('mail.from.address', $settings['email.admin_notification']);
            }
            
            if (isset($settings['email.sender_name'])) {
                Config::set('mail.from.name', $settings['email.sender_name']);
            }
            
            // Make settings available in all views
            view()->share('appSettings', $settings);
        }
    }

    /**
     * Load all settings from database or cache.
     */
    private function loadSettings(): array
    {
        // Try to get settings from cache
        if (Cache::has('application_settings')) {
            return Cache::get('application_settings');
        }

        // If not in cache, load from database
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        
        // Cache for 24 hours (1440 minutes)
        Cache::put('application_settings', $settings, 1440);
        
        return $settings;
    }
}
