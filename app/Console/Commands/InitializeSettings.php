<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Database\Seeders\SettingsSeeder;

class InitializeSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initialize-settings {--force : Force reset all settings}';

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
        if ($this->option('force') || Setting::count() === 0) {
            $this->call('db:seed', [
                '--class' => SettingsSeeder::class,
            ]);
            
            $this->info('Settings have been initialized successfully!');
        } else {
            if ($this->confirm('Settings already exist. Do you want to reset them to default values?')) {
                $this->call('db:seed', [
                    '--class' => SettingsSeeder::class,
                ]);
                
                $this->info('Settings have been reset to default values!');
            } else {
                $this->info('Initialization cancelled. Settings remain unchanged.');
            }
        }
    }
}
