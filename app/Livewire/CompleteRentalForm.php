<?php

namespace App\Livewire;

use App\Models\Rental;
use App\Models\Payment;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompleteRentalForm extends ModalComponent
{
    public $rentalId;
    public $rental;
    public $actual_return_time;
    public $payment_method = 'cash';
    public $totalHours = 0;
    public $totalPrice = 0;
    public $depositAmount = 0;
    public $remainingPayment = 0;

    protected $rules = [
        'actual_return_time' => 'required|date',
        'payment_method' => 'required|in:cash,credit_card,debit_card,transfer,e-wallet',
    ];

    public function mount($rentalId)
    {
        $this->rentalId = $rentalId;
        $this->rental = Rental::with(['user', 'pc', 'payments'])->findOrFail($rentalId);
        
        if ($this->rental->status !== 'active') {
            $this->addError('status', 'Only active rentals can be completed!');
            return;
        }
        
        $this->actual_return_time = Carbon::now()->format('Y-m-d\TH:i');
        $this->depositAmount = $this->rental->deposit_amount;
        $this->calculateTotals();
    }

    public function updatedActualReturnTime()
    {
        $this->calculateTotals();
    }

    protected function calculateTotals()
    {
        if (!$this->rental || !$this->actual_return_time) {
            return;
        }

        $startTime = $this->rental->start_time;
        $actualReturnTime = Carbon::parse($this->actual_return_time);
        
        if ($actualReturnTime->gt($startTime)) {
            $this->totalHours = $startTime->diffInHours($actualReturnTime);
            $this->totalPrice = $this->totalHours * $this->rental->pc->rental_price_hourly;
            $this->remainingPayment = $this->totalPrice - $this->depositAmount;
            if ($this->remainingPayment < 0) {
                $this->remainingPayment = 0;
            }
        } else {
            $this->totalHours = 0;
            $this->totalPrice = 0;
            $this->remainingPayment = 0;
        }
    }

    public function completeRental()
    {
        if ($this->rental->status !== 'active') {
            $this->addError('status', 'Only active rentals can be completed!');
            return;
        }

        $this->validate();

        $actualReturnTime = Carbon::parse($this->actual_return_time);
        $pc = $this->rental->pc;

        // Start a transaction
        DB::beginTransaction();

        try {
            // Update rental
            $this->rental->update([
                'actual_return_time' => $actualReturnTime,
                'total_price' => $this->totalPrice,
                'status' => 'completed',
            ]);

            // Create final payment if needed
            if ($this->remainingPayment > 0) {
                $paymentNumber = 'PAY-' . date('Ymd') . '-' . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT);

                Payment::create([
                    'rental_id' => $this->rental->id,
                    'payment_number' => $paymentNumber,
                    'amount' => $this->remainingPayment,
                    'type' => 'rental',
                    'method' => $this->payment_method,
                    'status' => 'completed',
                    'payment_date' => now(),
                    'notes' => 'Final payment',
                ]);
            }

            // Update PC status
            $pc->update(['status' => 'available']);

            DB::commit();

            $this->dispatch('swal:success', [
                'title' => 'Success!',
                'text' => 'Rental completed successfully!',
                'icon' => 'success',
            ]);
            
            $this->dispatch('refreshComponent');
            $this->closeModal();
            
            return redirect()->route('admin.rentals.show', $this->rental);
            
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to complete rental: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function render()
    {
        return view('livewire.complete-rental-form');
    }
}