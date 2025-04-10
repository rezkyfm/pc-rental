<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\PC;
use App\Models\User;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    /**
     * Display a listing of the rentals.
     */
    public function index()
    {
        $rentals = Rental::with(['user', 'pc'])
            ->latest()
            ->paginate(10);

        return view('admin.rentals.index', compact('rentals'));
    }

    /**
     * Show the form for creating a new rental.
     */
    public function create(Request $request)
    {
        $pcs = PC::where('status', 'available')->get();
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $depositPercentage = (float) Setting::getValue('deposit_percentage', 30);
        
        // Handle pre-selected PC and date from calendar
        $selectedPcId = $request->input('pc_id');
        $selectedDate = $request->input('date');
        
        return view('admin.rentals.create', compact('pcs', 'customers', 'depositPercentage', 'selectedPcId', 'selectedDate'));
    }

    /**
     * Store a newly created rental in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pc_id' => 'required|exists:pcs,id',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'deposit_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,credit_card,debit_card,transfer,e-wallet',
        ]);

        // Check if PC is available
        $pc = PC::findOrFail($validated['pc_id']);
        if ($pc->status !== 'available') {
            return redirect()->back()
                ->with('error', 'Selected PC is not available for rental!')
                ->withInput();
        }

        // Calculate rental duration and total price
        $startTime = \Carbon\Carbon::parse($validated['start_time']);
        $endTime = \Carbon\Carbon::parse($validated['end_time']);
        $hours = $startTime->diffInHours($endTime);

        $totalPrice = $hours * $pc->rental_price_hourly;

        // Start a transaction
        DB::beginTransaction();

        try {
            // Generate invoice number
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(Rental::count() + 1, 4, '0', STR_PAD_LEFT);

            // Create rental
            $rental = Rental::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => $validated['user_id'],
                'pc_id' => $validated['pc_id'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'total_price' => $totalPrice,
                'deposit_amount' => $validated['deposit_amount'],
                'status' => 'pending',
            ]);

            // Create deposit payment
            $paymentNumber = 'PAY-' . date('Ymd') . '-' . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT);

            Payment::create([
                'rental_id' => $rental->id,
                'payment_number' => $paymentNumber,
                'amount' => $validated['deposit_amount'],
                'type' => 'deposit',
                'method' => $validated['payment_method'],
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

            return redirect()->route('rentals.show', $rental)
                ->with('success', 'Rental created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to create rental: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified rental.
     */
    public function show(Rental $rental)
    {
        $rental->load('user', 'pc.components.category', 'payments');
        return view('admin.rentals.show', compact('rental'));
    }

    /**
     * Complete a rental.
     */
    public function complete(Request $request, Rental $rental)
    {
        if ($rental->status !== 'active') {
            return redirect()->back()
                ->with('error', 'Only active rentals can be completed!');
        }

        $validated = $request->validate([
            'actual_return_time' => 'required|date',
            'payment_method' => 'required|in:cash,credit_card,debit_card,transfer,e-wallet',
        ]);

        $actualReturnTime = \Carbon\Carbon::parse($validated['actual_return_time']);
        $pc = $rental->pc;

        // Start a transaction
        DB::beginTransaction();

        try {
            // Calculate final payment amount
            $totalHours = $rental->start_time->diffInHours($actualReturnTime);
            $totalPrice = $totalHours * $pc->rental_price_hourly;
            $remainingPayment = $totalPrice - $rental->deposit_amount;

            // Update rental
            $rental->update([
                'actual_return_time' => $actualReturnTime,
                'total_price' => $totalPrice,
                'status' => 'completed',
            ]);

            // Create final payment if needed
            if ($remainingPayment > 0) {
                $paymentNumber = 'PAY-' . date('Ymd') . '-' . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT);

                Payment::create([
                    'rental_id' => $rental->id,
                    'payment_number' => $paymentNumber,
                    'amount' => $remainingPayment,
                    'type' => 'rental',
                    'method' => $validated['payment_method'],
                    'status' => 'completed',
                    'payment_date' => now(),
                    'notes' => 'Final payment',
                ]);
            }

            // Update PC status
            $pc->update(['status' => 'available']);

            DB::commit();

            return redirect()->route('rentals.show', $rental)
                ->with('success', 'Rental completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to complete rental: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a rental.
     */
    public function cancel(Rental $rental)
    {
        if (!in_array($rental->status, ['pending', 'active'])) {
            return redirect()->back()
                ->with('error', 'Only pending or active rentals can be cancelled!');
        }

        // Start a transaction
        DB::beginTransaction();

        try {
            // Update rental status
            $rental->update(['status' => 'cancelled']);

            // If the rental was active, update PC status
            if ($rental->status === 'active') {
                $rental->pc->update(['status' => 'available']);
            }

            DB::commit();

            return redirect()->route('rentals.index')
                ->with('success', 'Rental cancelled successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to cancel rental: ' . $e->getMessage());
        }
    }
}