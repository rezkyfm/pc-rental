<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Operator
        User::create([
            'name' => 'Operator User',
            'email' => 'operator@example.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'email_verified_at' => now(),
        ]);

        // Sample customers
        User::create([
            'name' => 'Joko Widodo',
            'email' => 'joko@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@example.com',
            'password' => Hash::make('password'),
            'phone' => '081298765432',
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // Create more sample customers
        User::factory()->count(20)->create([
            'role' => 'customer',
        ]);
    }
}