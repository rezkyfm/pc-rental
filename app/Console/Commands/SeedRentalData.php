<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\RentalSystemSeeder;

class SeedRentalData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:rentals {--fresh : Whether to clear existing rental data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with sample rental and payment data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('fresh') || $this->confirm('This will reset all existing rental and payment data. Continue?', true)) {
            $this->info('Starting rental system data seeding...');
            
            // Call the seeder
            $this->call('db:seed', [
                '--class' => RentalSystemSeeder::class,
            ]);
            
            $this->info('Sample rental data seeded successfully!');
            $this->info('You can now view the sample rentals in the admin panel.');
            
            return 0;
        }
        
        $this->info('Operation cancelled.');
        return 1;
    }
}