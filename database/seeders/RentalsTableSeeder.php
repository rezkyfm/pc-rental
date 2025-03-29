<?php

namespace Database\Seeders;

use App\Models\PC;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Database\Seeder;

class RentalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get customers
        $customers = User::where('role', 'customer')->take(5)->get();

        // Get PCs
        $pcs = PC::all();

        // Generate some completed rentals
        for ($i = 0; $i < 15; $i++) {
            $customer = $customers->random();
            $pc = $pcs->random();
            $startTime = now()->subDays(rand(5, 60))->subHours(rand(1, 12));
            $duration = rand(1, 8); // hours
            $endTime = (clone $startTime)->addHours($duration);

            $rental = Rental::create([
                'invoice_number' => 'INV-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'user_id' => $customer->id,
                'pc_id' => $pc->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'actual_return_time' => $endTime,
                'total_price' => $pc->rental_price_hourly * $duration,
                'deposit_amount' => $pc->rental_price_hourly * $duration * 0.3,
                'status' => 'completed',
                'notes' => 'Completed rental',
            ]);
        }

        // Generate some active rentals
        for ($i = 0; $i < 3; $i++) {
            $customer = $customers->random();
            $pc = $pcs[$i]; // Assign specific PCs to active rentals
            $startTime = now()->subHours(rand(1, 5));
            $duration = rand(2, 6); // hours
            $endTime = (clone $startTime)->addHours($duration);

            $rental = Rental::create([
                'invoice_number' => 'INV-' . str_pad($i + 16, 5, '0', STR_PAD_LEFT),
                'user_id' => $customer->id,
                'pc_id' => $pc->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'actual_return_time' => null,
                'total_price' => $pc->rental_price_hourly * $duration,
                'deposit_amount' => $pc->rental_price_hourly * $duration * 0.3,
                'status' => 'active',
                'notes' => 'Active rental',
            ]);

            // Update PC status to rented
            $pc->update(['status' => 'rented']);
        }

        // Generate some pending rentals (future bookings)
        for ($i = 0; $i < 2; $i++) {
            $customer = $customers->random();
            $pc = $pcs->random();
            $startTime = now()->addDays(rand(1, 7))->addHours(rand(1, 12));
            $duration = rand(2, 8); // hours
            $endTime = (clone $startTime)->addHours($duration);

            $rental = Rental::create([
                'invoice_number' => 'INV-' . str_pad($i + 19, 5, '0', STR_PAD_LEFT),
                'user_id' => $customer->id,
                'pc_id' => $pc->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'actual_return_time' => null,
                'total_price' => $pc->rental_price_hourly * $duration,
                'deposit_amount' => $pc->rental_price_hourly * $duration * 0.3,
                'status' => 'pending',
                'notes' => 'Future booking',
            ]);
        }
    }
}