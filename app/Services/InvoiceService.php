<?php

namespace App\Services;

use App\Models\Rental;
use App\Models\Setting;
use PDF;
use Carbon\Carbon;

class InvoiceService
{
    /**
     * Generate a PDF invoice for a rental
     *
     * @param Rental $rental
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateInvoicePDF(Rental $rental)
    {
        // Load the rental with relationships
        $rental->load(['pc', 'user', 'payments']);
        
        // Get company settings
        $companyName = Setting::getValue('business.company_name', 'PC Rental, Inc.');
        $companyAddress = Setting::getValue('business.address', '123 Main Street');
        $companyCity = Setting::getValue('business.city', 'Any City');
        $companyPostalCode = Setting::getValue('business.postal_code', '12345');
        $companyCountry = Setting::getValue('business.country', 'Indonesia');
        $companyTaxId = Setting::getValue('business.tax_id', '123456789');
        $currencySymbol = Setting::getValue('payment.currency_symbol', 'Rp');
        $footerText = Setting::getValue('payment.receipt_footer', 'Thank you for your business!');
        
        // Calculate rental details
        $duration = $rental->getDurationInHoursAttribute();
        $hourlyRate = $rental->pc->rental_price_hourly;
        
        // Format dates
        $startDate = $rental->start_time->format('M d, Y H:i');
        $endDate = $rental->end_time ? $rental->end_time->format('M d, Y H:i') : 'Not specified';
        $returnDate = $rental->actual_return_time ? $rental->actual_return_time->format('M d, Y H:i') : 'Not returned yet';
        $invoiceDate = Carbon::now()->format('M d, Y');
        
        // Get payments
        $depositPayment = $rental->payments->where('type', 'deposit')->first();
        $rentalPayment = $rental->payments->where('type', 'rental')->first();
        
        // Generate PDF
        $pdf = PDF::loadView('pdfs.invoice', [
            'rental' => $rental,
            'companyName' => $companyName,
            'companyAddress' => $companyAddress,
            'companyCity' => $companyCity,
            'companyPostalCode' => $companyPostalCode,
            'companyCountry' => $companyCountry,
            'companyTaxId' => $companyTaxId,
            'currencySymbol' => $currencySymbol,
            'footerText' => $footerText,
            'duration' => $duration,
            'hourlyRate' => $hourlyRate,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'returnDate' => $returnDate,
            'invoiceDate' => $invoiceDate,
            'depositPayment' => $depositPayment,
            'rentalPayment' => $rentalPayment,
        ]);
        
        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }
}
