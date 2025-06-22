<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all rentals
        $rentals = Rental::all();
        
        if ($rentals->isEmpty()) {
            $this->command->error('No rentals found. Please run RentalsTableSeeder first.');
            return;
        }
        
        $this->command->info('Creating payment records for ' . $rentals->count() . ' rentals...');
        $paymentCount = 0;

        foreach ($rentals as $index => $rental) {
            // Create deposit payment for all rentals (except cancelled ones)
            if ($rental->status !== 'cancelled') {
                Payment::create([
                    'rental_id' => $rental->id,
                    'payment_number' => 'PAY-' . date('Ymd', strtotime($rental->created_at)) . '-' . str_pad(($paymentCount + 1), 4, '0', STR_PAD_LEFT),
                    'amount' => $rental->deposit_amount,
                    'type' => 'deposit',
                    'method' => $this->getRandomPaymentMethod(),
                    'status' => 'completed',
                    'payment_date' => $rental->created_at,
                    'notes' => 'Deposit payment',
                ]);
                $paymentCount++;
            }

            // If rental is completed, create final payment
            if ($rental->status === 'completed') {
                // Calculate remaining payment (may include extra hours if returned late)
                $remainingPayment = $rental->total_price - $rental->deposit_amount;
                
                if ($remainingPayment > 0) {
                    Payment::create([
                        'rental_id' => $rental->id,
                        'payment_number' => 'PAY-' . date('Ymd', strtotime($rental->actual_return_time)) . '-' . str_pad(($paymentCount + 1), 4, '0', STR_PAD_LEFT),
                        'amount' => $remainingPayment,
                        'type' => 'rental',
                        'method' => $this->getRandomPaymentMethod(),
                        'status' => 'completed',
                        'payment_date' => $rental->actual_return_time,
                        'notes' => $rental->actual_return_time > $rental->end_time 
                            ? 'Final payment including late fees' 
                            : 'Final payment',
                    ]);
                    $paymentCount++;
                }
            }
            
            // For some cancelled rentals, create refund payments
            elseif ($rental->status === 'cancelled' && rand(1, 10) <= 7) {
                // Create the initial deposit payment that was later refunded
                $depositPayment = Payment::create([
                    'rental_id' => $rental->id,
                    'payment_number' => 'PAY-' . date('Ymd', strtotime($rental->created_at)) . '-' . str_pad(($paymentCount + 1), 4, '0', STR_PAD_LEFT),
                    'amount' => $rental->deposit_amount,
                    'type' => 'deposit',
                    'method' => $method = $this->getRandomPaymentMethod(),
                    'status' => 'completed',
                    'payment_date' => $rental->created_at,
                    'notes' => 'Deposit payment (later refunded)',
                ]);
                $paymentCount++;
                
                // Create the refund payment
                $refundAmount = rand(5, 10) <= 8 
                    ? $rental->deposit_amount  // Full refund (80% chance)
                    : $rental->deposit_amount * 0.8;  // Partial refund (20% chance)
                    
                Payment::create([
                    'rental_id' => $rental->id,
                    'payment_number' => 'PAY-' . date('Ymd', strtotime($rental->updated_at)) . '-' . str_pad(($paymentCount + 1), 4, '0', STR_PAD_LEFT),
                    'amount' => -$refundAmount, // Negative amount to indicate refund
                    'type' => 'refund',
                    'method' => $method, // Use same method as deposit
                    'status' => 'completed',
                    'payment_date' => Carbon::parse($rental->updated_at)->addHours(rand(1, 48)),
                    'notes' => $refundAmount < $rental->deposit_amount 
                        ? 'Partial deposit refund (cancellation fee applied)' 
                        : 'Full deposit refund',
                ]);
                $paymentCount++;
            }
        }
        
        $this->command->info('Created ' . $paymentCount . ' payment records.');
    }

    /**
     * Get random payment method.
     *
     * @return string
     */
    private function getRandomPaymentMethod(): string
    {
        $methods = [
            'cash' => 40,  // 40% probability
            'credit_card' => 25, // 25% probability
            'debit_card' => 15, // 15% probability
            'transfer' => 10, // 10% probability
            'e-wallet' => 10, // 10% probability
        ];
        
        // Convert to probability distribution
        $total = array_sum($methods);
        $rand = rand(1, $total);
        
        $sum = 0;
        foreach ($methods as $method => $probability) {
            $sum += $probability;
            if ($rand <= $sum) {
                return $method;
            }
        }
        
        return 'cash'; // Fallback
    }
}