<?php

namespace App\Http\Controllers;

use App\Models\PC;
use App\Models\Rental;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display the customer home page.
     */
    public function index()
    {
        $pcs = PC::where('status', 'available')
            ->orderBy('rental_price_hourly')
            ->take(6)
            ->get();

        return view('welcome', compact('pcs'));
    }

    /**
     * Display all available PCs.
     */
    public function browsePCs()
    {
        $pcs = PC::where('status', 'available')
            ->orderBy('rental_price_hourly')
            ->paginate(12);

        return view('customer.browse-pcs', compact('pcs'));
    }

    /**
     * Display PC details.
     */
    public function pcDetails(PC $pc)
    {
        $pc->load('components.category');
        $similarPCs = PC::where('id', '!=', $pc->id)
            ->where('status', 'available')
            ->take(3)
            ->get();

        return view('customer.pc-details', compact('pc', 'similarPCs'));
    }

    /**
     * Show the rental form for a PC.
     */
    public function rentPC(PC $pc)
    {
        if ($pc->status !== 'available') {
            return redirect()->route('pc.details', $pc)
                ->with('error', 'This PC is currently not available for rental.');
        }

        $depositPercentage = (float) Setting::getValue('deposit_percentage', 30);
        $depositAmount = $pc->rental_price_hourly * 2 * ($depositPercentage / 100);

        return view('customer.rent-pc', compact('pc', 'depositAmount'));
    }

    /**
     * Process the rental request.
     */
    public function processRental(Request $request, PC $pc)
    {
        // Validate request
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'duration' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,credit_card,debit_card,transfer,e-wallet',
        ]);

        // Check if PC is available
        if ($pc->status !== 'available') {
            return redirect()->route('pc.details', $pc)
                ->with('error', 'This PC is currently not available for rental.');
        }

        // Calculate start and end times
        $startDateTime = \Carbon\Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
        $endDateTime = (clone $startDateTime)->addHours($validated['duration']);

        // Calculate total price and deposit
        $totalPrice = $pc->rental_price_hourly * $validated['duration'];
        $depositPercentage = (float) Setting::getValue('deposit_percentage', 30);
        $depositAmount = $totalPrice * ($depositPercentage / 100);

        // Get current user
        $user = Auth::user();

        // Create rental record
        $rental = new Rental();
        $rental->invoice_number = 'INV-' . date('Ymd') . '-' . str_pad(Rental::count() + 1, 4, '0', STR_PAD_LEFT);
        $rental->user_id = $user->id;
        $rental->pc_id = $pc->id;
        $rental->start_time = $startDateTime;
        $rental->end_time = $endDateTime;
        $rental->total_price = $totalPrice;
        $rental->deposit_amount = $depositAmount;
        $rental->status = 'pending';
        $rental->save();

        // Create payment record for deposit
        $paymentNumber = 'PAY-' . date('Ymd') . '-' . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT);

        $payment = new Payment();
        $payment->rental_id = $rental->id;
        $payment->payment_number = $paymentNumber;
        $payment->amount = $depositAmount;
        $payment->type = 'deposit';
        $payment->method = $validated['payment_method'];
        $payment->status = 'pending';
        $payment->payment_date = now();
        $payment->save();

        // Redirect to payment page
        return redirect()->route('customer.payment', $payment)
            ->with('success', 'Your rental request has been created. Please complete the payment.');
    }

    /**
     * Display user rentals.
     */
    public function myRentals()
    {
        $user = Auth::user();
        $rentals = Rental::where('user_id', $user->id)
            ->with('pc', 'payments')
            ->latest()
            ->paginate(10);

        return view('customer.my-rentals', compact('rentals'));
    }

    /**
     * Display rental details.
     */
    public function rentalDetails(Rental $rental)
    {
        // Check if rental belongs to the current user
        if ($rental->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $rental->load('pc.components.category', 'payments');
        return view('customer.rental-details', compact('rental'));
    }
}