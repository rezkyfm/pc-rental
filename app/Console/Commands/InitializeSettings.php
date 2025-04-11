<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;

class InitializeSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initialize-settings {--force : Force the initialization even if settings exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize default application settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (Setting::count() > 0 && !$this->option('force')) {
            $this->error('Settings already exist. Use --force to override.');
            return 1;
        }

        // Define default settings
        $defaultSettings = [
            [
                'key' => 'site_name',
                'value' => 'PC Rental System',
                'type' => 'string',
            ],
            [
                'key' => 'business_hours',
                'value' => '09:00-18:00',
                'type' => 'string',
            ],
            [
                'key' => 'currency',
                'value' => 'USD',
                'type' => 'string',
            ],
            [
                'key' => 'default_rental_duration',
                'value' => '1',
                'type' => 'integer',
            ],
        ];

        // Clear existing settings if force option is used
        if ($this->option('force')) {
            Setting::truncate();
        }

        // Insert default settings
        foreach ($defaultSettings as $setting) {
            Setting::create($setting);
        }

        $this->info('Default settings have been initialized successfully.');
        return 0;
    }
}
