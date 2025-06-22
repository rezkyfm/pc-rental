<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected $invoiceService;
    
    /**
     * Constructor
     */
    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }
    
    /**
     * Download invoice PDF for a rental
     *
     * @param Rental $rental
     * @return \Illuminate\Http\Response
     */
    public function downloadInvoice(Rental $rental)
    {
        // Check permissions (only admin, operator, or the rental owner should download)
        if (auth()->user()->role !== 'admin' && 
            auth()->user()->role !== 'operator' && 
            auth()->user()->id !== $rental->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Generate the PDF
        $pdf = $this->invoiceService->generateInvoicePDF($rental);
        
        // Return the PDF for download
        return $pdf->download('invoice_' . $rental->invoice_number . '.pdf');
    }
    
    /**
     * View invoice in browser
     *
     * @param Rental $rental
     * @return \Illuminate\Http\Response
     */
    public function viewInvoice(Rental $rental)
    {
        // Check permissions (only admin, operator, or the rental owner should view)
        if (auth()->user()->role !== 'admin' && 
            auth()->user()->role !== 'operator' && 
            auth()->user()->id !== $rental->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Generate the PDF
        $pdf = $this->invoiceService->generateInvoicePDF($rental);
        
        // Stream the PDF for viewing in browser
        return $pdf->stream('invoice_' . $rental->invoice_number . '.pdf');
    }
}
