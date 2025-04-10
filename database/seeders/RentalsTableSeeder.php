<?php

namespace Database\Seeders;

use App\Models\PC;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RentalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get customers
        $customers = User::where('role', 'customer')->get();
        
        if ($customers->isEmpty()) {
            $this->command->info('No customers found. Creating a few customers first...');
            
            // Create some customers if none exist
            for ($i = 1; $i <= 5; $i++) {
                User::create([
                    'name' => "Customer {$i}",
                    'email' => "customer{$i}@example.com",
                    'password' => bcrypt('password'),
                    'role' => 'customer',
                    'phone' => '08' . rand(10000000, 99999999),
                ]);
            }
            
            $customers = User::where('role', 'customer')->get();
        }

        // Get PCs
        $pcs = PC::all();
        
        if ($pcs->isEmpty()) {
            $this->command->error('No PCs found. Please run PCsTableSeeder first.');
            return;
        }

        $this->command->info('Creating historical rentals (last 3 months)...');
        // Generate historical rentals (last 3 months)
        for ($i = 0; $i < 30; $i++) {
            $customer = $customers->random();
            $pc = $pcs->random();
            
            // Start time between 3 months ago and 1 week ago
            $startTime = Carbon::now()->subDays(rand(7, 90))->setHour(rand(8, 20))->setMinute(0)->setSecond(0);
            
            // Duration between 1 and 12 hours
            $duration = rand(1, 12);
            $endTime = (clone $startTime)->addHours($duration);
            
            // 80% of historical rentals are completed, 20% canceled
            $status = rand(1, 10) <= 8 ? 'completed' : 'cancelled';
            
            // Calculate price
            $totalPrice = $pc->rental_price_hourly * $duration;
            $depositAmount = round($totalPrice * 0.3, 2); // 30% deposit
            
            // Create the rental
            Rental::create([
                'invoice_number' => 'INV-' . date('Ymd', $startTime->timestamp) . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'user_id' => $customer->id,
                'pc_id' => $pc->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'actual_return_time' => $status === 'completed' ? $endTime : null,
                'total_price' => $totalPrice,
                'deposit_amount' => $depositAmount,
                'status' => $status,
                'notes' => $status === 'completed' ? 'Historical completed rental' : 'Historical cancelled rental',
            ]);
        }

        $this->command->info('Creating recent completed rentals (last week)...');
        // Generate recent completed rentals (last week)
        for ($i = 0; $i < 10; $i++) {
            $customer = $customers->random();
            $pc = $pcs->random();
            
            // Start time between last week and yesterday
            $startTime = Carbon::now()->subDays(rand(1, 7))->setHour(rand(8, 18))->setMinute(0)->setSecond(0);
            
            // Duration between 1 and 8 hours
            $duration = rand(1, 8);
            $endTime = (clone $startTime)->addHours($duration);
            
            // Some rentals might have returned late
            $actualReturnTime = rand(1, 10) <= 7 
                ? (clone $endTime) 
                : (clone $endTime)->addMinutes(rand(15, 120));
            
            // Calculate price
            $actualDuration = $startTime->diffInHours($actualReturnTime);
            $totalPrice = $pc->rental_price_hourly * $actualDuration;
            $depositAmount = round($totalPrice * 0.3, 2); // 30% deposit
            
            // Create the rental
            Rental::create([
                'invoice_number' => 'INV-' . date('Ymd', $startTime->timestamp) . '-' . str_pad($i + 31, 4, '0', STR_PAD_LEFT),
                'user_id' => $customer->id,
                'pc_id' => $pc->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'actual_return_time' => $actualReturnTime,
                'total_price' => $totalPrice,
                'deposit_amount' => $depositAmount,
                'status' => 'completed',
                'notes' => $actualReturnTime->gt($endTime) ? 'Customer returned late' : 'Recent completed rental',
            ]);
        }

        $this->command->info('Creating active rentals...');
        // Generate active rentals (happening now)
        for ($i = 0; $i < min(5, (int)($pcs->count() * 0.3)); $i++) { // Use up to 30% of PCs for active rentals
            $customer = $customers->random();
            $pc = $pcs->where('status', 'available')->first();
            
            if (!$pc) {
                $this->command->info('No more available PCs for active rentals.');
                break;
            }
            
            // Start time between 1 and 6 hours ago
            $startTime = Carbon::now()->subHours(rand(1, 6))->subMinutes(rand(0, 55));
            
            // Duration between 3 and 12 hours from start
            $duration = rand(3, 12);
            $endTime = (clone $startTime)->addHours($duration);
            
            // Calculate estimated price
            $totalPrice = $pc->rental_price_hourly * $duration;
            $depositAmount = round($totalPrice * 0.3, 2); // 30% deposit
            
            // Create the rental
            Rental::create([
                'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad($i + 41, 4, '0', STR_PAD_LEFT),
                'user_id' => $customer->id,
                'pc_id' => $pc->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'actual_return_time' => null,
                'total_price' => $totalPrice,
                'deposit_amount' => $depositAmount,
                'status' => 'active',
                'notes' => 'Active rental',
            ]);
            
            // Update PC status to rented
            $pc->update(['status' => 'rented']);
        }

        $this->command->info('Creating pending future rentals...');
        // Generate pending rentals (future bookings)
        for ($i = 0; $i < 8; $i++) {
            $customer = $customers->random();
            $pc = $pcs->random();
            
            // Start time between tomorrow and next 2 weeks
            $startTime = Carbon::now()->addDays(rand(1, 14))->setHour(rand(9, 18))->setMinute(0)->setSecond(0);
            
            // Duration between 2 and 10 hours
            $duration = rand(2, 10);
            $endTime = (clone $startTime)->addHours($duration);
            
            // Calculate estimated price
            $totalPrice = $pc->rental_price_hourly * $duration;
            $depositAmount = round($totalPrice * 0.3, 2); // 30% deposit
            
            // Create the rental
            Rental::create([
                'invoice_number' => 'INV-' . date('Ymd', $startTime->timestamp) . '-' . str_pad($i + 46, 4, '0', STR_PAD_LEFT),
                'user_id' => $customer->id,
                'pc_id' => $pc->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'actual_return_time' => null,
                'total_price' => $totalPrice,
                'deposit_amount' => $depositAmount,
                'status' => 'pending',
                'notes' => 'Future booking',
            ]);
        }
        
        $this->command->info('Created ' . Rental::count() . ' rental records.');
    }
}