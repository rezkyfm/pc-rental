<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     */
    public function index()
    {
        $payments = Payment::with(['rental.user', 'rental.pc'])
            ->latest()
            ->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $payment->load('rental.user', 'rental.pc');
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(Request $request)
    {
        $rentalId = $request->query('rental_id');
        $rental = null;

        if ($rentalId) {
            $rental = Rental::with('payments')->findOrFail($rentalId);
        } else {
            $rentals = Rental::whereIn('status', ['active', 'pending'])
                ->orderBy('start_time')
                ->get();
        }

        return view('admin.payments.create', compact('rental', 'rentals'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:deposit,rental,extra,refund',
            'method' => 'required|in:cash,credit_card,debit_card,transfer,e-wallet',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Generate payment number
        $paymentNumber = 'PAY-' . date('Ymd') . '-' . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT);

        // Create payment
        $payment = Payment::create([
            'rental_id' => $validated['rental_id'],
            'payment_number' => $paymentNumber,
            'amount' => $validated['amount'],
            'type' => $validated['type'],
            'method' => $validated['method'],
            'status' => 'completed',
            'payment_date' => $validated['payment_date'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment recorded successfully!');
    }

    /**
     * Update payment status.
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,failed,refunded',
        ]);

        $payment->update(['status' => $validated['status']]);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment status updated successfully!');
    }
}