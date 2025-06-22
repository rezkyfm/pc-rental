<?php

namespace App\Livewire;

use App\Models\Payment;
use App\Models\Rental;
use Livewire\Component;
use Carbon\Carbon;

class PaymentForm extends Component
{
    public $rental_id;
    public $selectedRental = null;
    public $amount = 0;
    public $type = 'rental';
    public $method = 'cash';
    public $payment_date;
    public $notes;
    
    public $depositPaid = 0;
    public $totalRentalAmount = 0;
    public $remainingBalance = 0;
    public $suggestedAmount = 0;

    protected $rules = [
        'rental_id' => 'required|exists:rentals,id',
        'amount' => 'required|numeric|min:0.01',
        'type' => 'required|in:deposit,rental,extra,refund',
        'method' => 'required|in:cash,credit_card,debit_card,transfer,e-wallet',
        'payment_date' => 'required|date',
        'notes' => 'nullable|string',
    ];

    public function mount($rental = null)
    {
        // Set default payment date to today
        $this->payment_date = Carbon::now()->format('Y-m-d\TH:i');
        
        if ($rental) {
            $this->rental_id = $rental->id;
            $this->selectedRental = $rental;
            $this->calculateAmounts();
        }
    }

    public function updatedRentalId()
    {
        if ($this->rental_id) {
            $this->selectedRental = Rental::with(['payments', 'user', 'pc'])->find($this->rental_id);
            $this->calculateAmounts();
        } else {
            $this->selectedRental = null;
            $this->amount = 0;
            $this->resetValidation();
        }
    }
    
    public function updatedType()
    {
        $this->calculateAmounts();
    }

    protected function calculateAmounts()
    {
        if (!$this->selectedRental) {
            return;
        }
        
        // Calculate deposit amount already paid
        $this->depositPaid = $this->selectedRental->payments
            ->where('type', 'deposit')
            ->where('status', 'completed')
            ->sum('amount');
            
        // Calculate total amount of rental
        $this->totalRentalAmount = $this->selectedRental->total_price;
        
        // Calculate total amount already paid (deposits and rentals)
        $totalPaid = $this->selectedRental->payments
            ->whereIn('type', ['deposit', 'rental', 'extra'])
            ->where('status', 'completed')
            ->sum('amount');
            
        // Calculate total refunded
        $totalRefunded = $this->selectedRental->payments
            ->where('type', 'refund')
            ->where('status', 'completed')
            ->sum('amount');
            
        // Calculate remaining balance
        $this->remainingBalance = $this->totalRentalAmount - $totalPaid + $totalRefunded;
        
        // Set suggested amount based on payment type
        if ($this->type === 'deposit') {
            // For deposit, suggest the rental's deposit amount if not already paid
            if ($this->depositPaid < $this->selectedRental->deposit_amount) {
                $this->suggestedAmount = $this->selectedRental->deposit_amount - $this->depositPaid;
            } else {
                $this->suggestedAmount = 0;
            }
        } else if ($this->type === 'rental' || $this->type === 'extra') {
            // For rental payment, suggest the remaining balance
            $this->suggestedAmount = max(0, $this->remainingBalance);
        } else if ($this->type === 'refund') {
            // For refund, suggest the deposit amount if rental is cancelled
            if ($this->selectedRental->status === 'cancelled' && $this->depositPaid > 0) {
                $this->suggestedAmount = $this->depositPaid;
            } else {
                $this->suggestedAmount = 0;
            }
        }
        
        // Update the amount field with the suggested amount
        $this->amount = $this->suggestedAmount;
    }

    public function createPayment()
    {
        $this->validate();
        
        // Additional validation for refund type
        if ($this->type === 'refund' && $this->amount > 0) {
            $this->amount = -abs($this->amount); // Ensure refund amount is negative
        }

        try {
            // Generate payment number
            $paymentNumber = 'PAY-' . date('Ymd') . '-' . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT);
            
            // Create payment
            $payment = Payment::create([
                'rental_id' => $this->rental_id,
                'payment_number' => $paymentNumber,
                'amount' => $this->amount,
                'type' => $this->type,
                'method' => $this->method,
                'status' => 'completed',
                'payment_date' => $this->payment_date,
                'notes' => $this->notes,
            ]);
            
            $this->dispatch('swal:success', [
                'title' => 'Success!',
                'text' => 'Payment recorded successfully.',
                'icon' => 'success',
            ]);
            
            return redirect()->route('admin.payments.show', $payment);
            
        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to create payment: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        $rentals = Rental::with(['user', 'pc'])
            ->whereIn('status', ['active', 'completed', 'pending', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('livewire.payment-form', [
            'rentals' => $rentals
        ]);
    }
}