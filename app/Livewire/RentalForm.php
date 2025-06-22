<?php

namespace App\Livewire;

use App\Models\Rental;
use App\Models\PC;
use App\Models\User;
use App\Models\Payment;
use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RentalForm extends Component
{
    public $user_id;
    public $pc_id;
    public $start_time;
    public $end_time;
    public $deposit_amount = 0;
    public $payment_method = 'cash';
    public $notes;
    
    public $selectedPC = null;
    public $rentalHours = 0;
    public $estimatedTotal = 0;
    public $depositPercentage = 30;

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'pc_id' => 'required|exists:pcs,id',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after:start_time',
        'deposit_amount' => 'required|numeric|min:0',
        'payment_method' => 'required|in:cash,credit_card,debit_card,transfer,e-wallet',
        'notes' => 'nullable|string',
    ];

    public function mount($selectedPcId = null, $selectedDate = null)
    {
        // Set default values
        $this->start_time = Carbon::now()->format('Y-m-d\TH:i');
        $this->end_time = Carbon::now()->addHours(1)->format('Y-m-d\TH:i');
        $this->depositPercentage = (float) Setting::getValue('deposit_percentage', 30);
        
        // Handle pre-selected PC from calendar
        if ($selectedPcId) {
            $this->pc_id = $selectedPcId;
            $this->selectedPC = PC::find($selectedPcId);
            $this->calculateTotals();
        }
        
        // Handle pre-selected date from calendar
        if ($selectedDate) {
            $date = Carbon::parse($selectedDate);
            $this->start_time = $date->copy()->setHour(10)->setMinute(0)->format('Y-m-d\TH:i');
            $this->end_time = $date->copy()->setHour(18)->setMinute(0)->format('Y-m-d\TH:i');
            $this->calculateTotals();
        }
    }

    public function updatedPcId()
    {
        if ($this->pc_id) {
            $this->selectedPC = PC::find($this->pc_id);
            $this->calculateTotals();
        } else {
            $this->selectedPC = null;
            $this->estimatedTotal = 0;
            $this->deposit_amount = 0;
        }
    }

    public function updatedStartTime()
    {
        $this->calculateTotals();
    }

    public function updatedEndTime()
    {
        $this->calculateTotals();
    }

    protected function calculateTotals()
    {
        if (!$this->selectedPC || !$this->start_time || !$this->end_time) {
            return;
        }

        // Calculate rental duration
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        
        if ($end->gt($start)) {
            $this->rentalHours = $start->diffInHours($end);
            $this->estimatedTotal = $this->rentalHours * $this->selectedPC->rental_price_hourly;
            $this->deposit_amount = round($this->estimatedTotal * ($this->depositPercentage / 100), 2);
        } else {
            $this->rentalHours = 0;
            $this->estimatedTotal = 0;
            $this->deposit_amount = 0;
        }
    }

    public function createRental()
    {
        $this->validate();

        // Check if PC is available
        $pc = PC::findOrFail($this->pc_id);
        if ($pc->status !== 'available') {
            $this->addError('pc_id', 'Selected PC is not available for rental!');
            return;
        }

        // Calculate rental duration and total price
        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);
        $hours = $startTime->diffInHours($endTime);

        if ($hours <= 0) {
            $this->addError('end_time', 'End time must be after start time!');
            return;
        }

        $totalPrice = $hours * $pc->rental_price_hourly;

        // Start a transaction
        DB::beginTransaction();

        try {
            // Generate invoice number
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(Rental::count() + 1, 4, '0', STR_PAD_LEFT);

            // Create rental
            $rental = Rental::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => $this->user_id,
                'pc_id' => $this->pc_id,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'total_price' => $totalPrice,
                'deposit_amount' => $this->deposit_amount,
                'status' => 'pending',
                'notes' => $this->notes,
            ]);

            // Create deposit payment
            $paymentNumber = 'PAY-' . date('Ymd') . '-' . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT);

            Payment::create([
                'rental_id' => $rental->id,
                'payment_number' => $paymentNumber,
                'amount' => $this->deposit_amount,
                'type' => 'deposit',
                'method' => $this->payment_method,
                'status' => 'completed',
                'payment_date' => now(),
                'notes' => 'Initial deposit payment',
            ]);

            // If rental starts now, update PC status
            if ($startTime->isPast() || $startTime->isCurrentDay()) {
                $pc->update(['status' => 'rented']);
                $rental->update(['status' => 'active']);
            }

            DB::commit();

            $this->dispatch('swal:success', [
                'title' => 'Success!',
                'text' => 'Rental created successfully!',
                'icon' => 'success',
            ]);

            return redirect()->route('admin.rentals.show', $rental);
            
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Failed to create rental: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $availablePCs = PC::where('status', 'available')->orderBy('name')->get();
        
        return view('livewire.rental-form', [
            'customers' => $customers,
            'availablePCs' => $availablePCs,
        ]);
    }
}