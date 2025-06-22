# Invoice PDF Feature Implementation

This document explains how to set up and use the invoice PDF generation feature in your PC Rental application.

## Installation

### 1. Install Required Package

First, you need to install the Laravel DomPDF package by running this command in your terminal:

```bash
composer require barryvdh/laravel-dompdf
```

### 2. Publish the Configuration (Optional)

If you want to customize the PDF settings, you can publish the configuration file:

```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

## Usage

The implementation adds two main functions for invoices:

1. **Download Invoice**: Allows users to download the invoice as a PDF file
2. **View Invoice**: Allows users to view the invoice in the browser

These functions are available in both the admin interface and the customer interface.

### Admin Interface

In the admin interface, the invoice download and view buttons are located on the rental details page. Administrators can:

- Click "Download Invoice" to download the PDF
- Click "View Invoice" to view the PDF in a new browser tab

### Customer Interface

Similarly, customers can access PDF invoices from their rental details page.

## Implementation Details

### Components Created

1. **InvoiceService**: A service class that handles the generation of PDF invoices.
2. **InvoiceController**: A controller that manages both the download and view actions for invoices.
3. **PDF Invoice Template**: A blade template that defines the layout of the PDF invoice.
4. **UI Elements**: Added buttons to both admin and customer interfaces.

### Routes Added

```php
// For admin
Route::get('/rentals/{rental}/invoice/download', [InvoiceController::class, 'downloadInvoice'])->name('rentals.invoice.download');
Route::get('/rentals/{rental}/invoice/view', [InvoiceController::class, 'viewInvoice'])->name('rentals.invoice.view');

// For customers
Route::get('/rentals/{rental}/invoice/download', [InvoiceController::class, 'downloadInvoice'])->name('rentals.invoice.download');
Route::get('/rentals/{rental}/invoice/view', [InvoiceController::class, 'viewInvoice'])->name('rentals.invoice.view');
```

## Customization

The invoice template is located at `resources/views/pdfs/invoice.blade.php`. You can customize this template to match your branding and include additional information as needed.

The PDF generation is handled by the `InvoiceService` class, which uses business settings from your application database. Make sure you have the following settings defined:

- `business.company_name`
- `business.address`
- `business.city`
- `business.postal_code`
- `business.country`
- `business.tax_id`
- `payment.currency_symbol`
- `payment.receipt_footer`

The feature will use default values if these settings are not available.

## Security

The implementation includes security checks to ensure that only authorized users can access invoices:

- Only admin, operator, or the rental owner can view or download an invoice
- Attempt to access unauthorized invoices will result in a 403 Forbidden error
