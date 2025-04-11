<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(SettingsServiceProvider::class);
        
        // Register SettingsHelper as a singleton
        $this->app->singleton(\App\Helpers\SettingsHelper::class, function () {
            return new \App\Helpers\SettingsHelper();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Initialize default settings if none exist
        if (Schema::hasTable('settings') && Setting::count() === 0) {
            // Initialize settings directly using the seeder
            $seeder = new \Database\Seeders\SettingsSeeder();
            $seeder->setContainer($this->app);
            $seeder->run();
        }
    }
}
