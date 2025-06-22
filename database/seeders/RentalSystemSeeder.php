<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RentalSystemSeeder extends Seeder
{
    /**
     * Seed the rental system with sample data.
     */
    public function run(): void
    {
        $this->command->info('Seeding rental system data...');
        
        // Clear existing data first to avoid duplication
        $this->command->info('Truncating existing rental and payment data...');
        
        Schema::disableForeignKeyConstraints();
        DB::table('payments')->truncate();
        DB::table('rentals')->truncate();
        Schema::enableForeignKeyConstraints();
        
        // Reset PC status to available (in case there were active rentals before)
        DB::table('pcs')->update(['status' => 'available']);
        
        // Run the seeders
        $this->command->info('Creating new rental data...');
        $this->call(RentalsTableSeeder::class);
        
        $this->command->info('Creating payment records for rentals...');
        $this->call(PaymentsTableSeeder::class);
        
        $this->command->info('Rental system seeding completed!');
    }
}