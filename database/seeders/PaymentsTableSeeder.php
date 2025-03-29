<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Database\Seeder;

class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all rentals
        $rentals = Rental::all();

        foreach ($rentals as $index => $rental) {
            // Create deposit payment
            Payment::create([
                'rental_id' => $rental->id,
                'payment_number' => 'PAY-' . str_pad(($index * 2) + 1, 5, '0', STR_PAD_LEFT),
                'amount' => $rental->deposit_amount,
                'type' => 'deposit',
                'method' => $this->getRandomPaymentMethod(),
                'status' => 'completed',
                'payment_date' => $rental->start_time,
                'notes' => 'Deposit payment',
            ]);

            // If rental is completed, create final payment
            if ($rental->status === 'completed') {
                Payment::create([
                    'rental_id' => $rental->id,
                    'payment_number' => 'PAY-' . str_pad(($index * 2) + 2, 5, '0', STR_PAD_LEFT),
                    'amount' => $rental->total_price - $rental->deposit_amount,
                    'type' => 'rental',
                    'method' => $this->getRandomPaymentMethod(),
                    'status' => 'completed',
                    'payment_date' => $rental->actual_return_time,
                    'notes' => 'Final payment',
                ]);
            }
        }
    }

    /**
     * Get random payment method.
     *
     * @return string
     */
    private function getRandomPaymentMethod(): string
    {
        $methods = ['cash', 'credit_card', 'debit_card', 'transfer', 'e-wallet'];
        return $methods[array_rand($methods)];
    }
}