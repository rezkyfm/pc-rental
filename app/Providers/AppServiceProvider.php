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
        try {
            if (Schema::hasTable('settings')) {
                $settingsCount = Setting::count();
                if ($settingsCount === 0) {
                    // Initialize settings directly using the seeder
                    $seeder = new \Database\Seeders\SettingsSeeder();
                    $seeder->setContainer($this->app);
                    
                    // Handle the case where the Command class isn't available
                    try {
                        $command = $this->app->make(\Illuminate\Console\Command::class);
                        $seeder->setCommand($command);
                    } catch (\Exception $e) {
                        // Command isn't available, use a simple stub
                        $seeder->setCommand(new class extends \Illuminate\Console\Command {
                            public function info($string, $verbosity = null) {}
                        });
                    }
                    
                    $seeder->run();
                }
            }
        } catch (\Exception $e) {
            // Log the error but don't crash the application
            if ($this->app->bound('log')) {
                $this->app->make('log')->error('Failed to initialize settings: ' . $e->getMessage());
            }
        }
    }
}
