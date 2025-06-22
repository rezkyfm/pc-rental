<?php

namespace App\Livewire;

use App\Models\Payment;
use Livewire\Component;

class PaymentReceipt extends Component
{
    public $payment;
    public $company;
    
    public function mount(Payment $payment)
    {
        $this->payment = $payment->load('rental.user', 'rental.pc');
        
        // Get company information from settings
        $this->company = [
            'name' => \App\Models\Setting::getValue('company_name', 'PC Rental Company'),
            'address' => \App\Models\Setting::getValue('company_address', '123 Main Street, City'),
            'phone' => \App\Models\Setting::getValue('company_phone', '(123) 456-7890'),
            'email' => \App\Models\Setting::getValue('company_email', 'info@pcrental.com'),
            'website' => \App\Models\Setting::getValue('company_website', 'www.pcrental.com'),
            'tax_id' => \App\Models\Setting::getValue('company_tax_id', 'TAX-123456789'),
        ];
    }
    
    public function render()
    {
        return view('livewire.payment-receipt');
    }
}