<?php

namespace App\Livewire;

use App\Models\Payment;
use App\Models\Rental;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Carbon\Carbon;

class RefundPaymentForm extends ModalComponent
{
    public $paymentId;
    public $payment;
    public $amount;
    public $method;
    public $payment_date;
    public $notes;
    
    public $originalPayment;
    public $maxRefundAmount;

    protected $rules = [
        'amount' => 'required|numeric|min:0.01',
        'method' => 'required|in:cash,credit_card,debit_card,transfer,e-wallet',
        'payment_date' => 'required|date',
        'notes' => 'nullable|string',
    ];

    public function mount($paymentId)
    {
        $this->paymentId = $paymentId;
        $this->originalPayment = Payment::with('rental')->findOrFail($paymentId);
        
        // Set default values
        $this->amount = abs($this->originalPayment->amount);
        $this->method = $this->originalPayment->method;
        $this->payment_date = Carbon::now()->format('Y-m-d\TH:i');
        $this->notes = 'Refund for payment #' . $this->originalPayment->payment_number;
        
        // Calculate max refund amount
        $this->maxRefundAmount = abs($this->originalPayment->amount);
        
        // Check if there are already refunds for this payment
        $existingRefunds = Payment::where('notes', 'like', '%' . $this->originalPayment->payment_number . '%')
            ->where('type', 'refund')
            ->where('status', 'completed')
            ->sum('amount');
            
        if ($existingRefunds < 0) { // Refunds are stored as negative amounts
            $this->maxRefundAmount += $existingRefunds; // Add negative value
        }
    }

    public function createRefund()
    {
        $this->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $this->maxRefundAmount,
        ]);

        try {
            // Generate payment number for refund
            $paymentNumber = 'REF-' . date('Ymd') . '-' . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT);
            
            // Create refund payment (negative amount)
            $refund = Payment::create([
                'rental_id' => $this->originalPayment->rental_id,
                'payment_number' => $paymentNumber,
                'amount' => -$this->amount, // Negative for refunds
                'type' => 'refund',
                'method' => $this->method,
                'status' => 'completed',
                'payment_date' => $this->payment_date,
                'notes' => $this->notes,
            ]);
            
            $this->dispatch('swal:success', [
                'title' => 'Success!',
                'text' => 'Refund processed successfully.',
                'icon' => 'success',
            ]);
            
            $this->dispatch('refreshComponent');
            $this->closeModal();
            
            // Redirect to the refund details
            return redirect()->route('admin.payments.show', $refund);
            
        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to process refund: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function render()
    {
        return view('livewire.refund-payment-form');
    }
}