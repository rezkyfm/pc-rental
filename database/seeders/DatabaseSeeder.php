<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            ComponentCategoriesTableSeeder::class,
            ComponentsTableSeeder::class,
            PCsTableSeeder::class,
            PCComponentsTableSeeder::class,
            SettingsTableSeeder::class,

            // Uncomment untuk mengisi data sampel
            // RentalsTableSeeder::class,
            // PaymentsTableSeeder::class,
            // MaintenanceTableSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
