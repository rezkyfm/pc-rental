<?php

namespace Database\Seeders;

use App\Models\PC;
use Illuminate\Database\Seeder;

class PCsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pcs = [
            [
                'name' => 'Gaming Beast',
                'code' => 'GB-001',
                'description' => 'High-end gaming PC with top-tier components for the best gaming experience',
                'rental_price_hourly' => 25.00,
                'rental_price_daily' => 180.00,
                'status' => 'available',
                'assembly_date' => now()->subMonths(1),
            ],
            [
                'name' => 'Pro Gamer',
                'code' => 'PG-002',
                'description' => 'Powerful gaming PC for competitive gaming and streaming',
                'rental_price_hourly' => 20.00,
                'rental_price_daily' => 150.00,
                'status' => 'available',
                'assembly_date' => now()->subMonths(1)->subDays(15),
            ],
            [
                'name' => 'Streamer Setup',
                'code' => 'SS-003',
                'description' => 'Optimized PC for content creation and streaming',
                'rental_price_hourly' => 22.00,
                'rental_price_daily' => 160.00,
                'status' => 'available',
                'assembly_date' => now()->subMonths(2),
            ],
            [
                'name' => 'Casual Gaming',
                'code' => 'CG-004',
                'description' => 'Mid-range PC for casual gaming at 1080p',
                'rental_price_hourly' => 15.00,
                'rental_price_daily' => 110.00,
                'status' => 'available',
                'assembly_date' => now()->subMonths(2)->subDays(10),
            ],
            [
                'name' => 'E-Sports Ready',
                'code' => 'ER-005',
                'description' => 'High refresh rate gaming PC optimized for competitive titles',
                'rental_price_hourly' => 18.00,
                'rental_price_daily' => 130.00,
                'status' => 'available',
                'assembly_date' => now()->subMonths(1)->subDays(20),
            ],
        ];

        foreach ($pcs as $pc) {
            PC::create($pc);
        }
    }
}