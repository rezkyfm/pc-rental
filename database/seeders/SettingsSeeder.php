<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Try to clear settings cache if available
        try {
            if (class_exists('\Illuminate\Support\Facades\Cache') && app()->bound('cache')) {
                \Illuminate\Support\Facades\Cache::forget('application_settings');
            }
        } catch (\Exception $e) {
            // Ignore cache errors during seeding
        }

        // Define default settings for all categories
        $settings = [
            // General Settings
            'general.site_name' => ['value' => 'PC Rental Service', 'description' => 'Application name'],
            'general.site_description' => ['value' => 'Professional PC rental services for gaming, work, and events', 'description' => 'Application description'],
            'general.contact_email' => ['value' => 'contact@pcrental.com', 'description' => 'Contact email address'],
            'general.contact_phone' => ['value' => '+1234567890', 'description' => 'Contact phone number'],
            'general.address' => ['value' => '123 Tech Street, Digital City', 'description' => 'Business address'],
            'general.business_hours' => ['value' => 'Mon-Fri: 9am-6pm, Sat: 10am-4pm, Sun: Closed', 'description' => 'Business hours'],
            'general.date_format' => ['value' => 'Y-m-d', 'description' => 'Date format (PHP date format)'],
            'general.time_format' => ['value' => 'H:i', 'description' => 'Time format (PHP time format)'],
            
            // Payment Settings
            'payment.currency' => ['value' => 'IDR', 'description' => 'Currency code'],
            'payment.currency_symbol' => ['value' => 'Rp', 'description' => 'Currency symbol'],
            'payment.deposit_percentage' => ['value' => '30', 'description' => 'Default deposit percentage'],
            'payment.tax_percentage' => ['value' => '10', 'description' => 'Tax percentage'],
            'payment.late_fee_hourly' => ['value' => '15', 'description' => 'Late return fee per hour'],
            'payment.payment_methods' => ['value' => 'cash,credit_card,debit_card,transfer,e-wallet', 'description' => 'Available payment methods (comma-separated)'],
            'payment.receipt_footer' => ['value' => 'Thank you for your business!', 'description' => 'Footer text for receipts'],
            'payment.invoice_prefix' => ['value' => 'INV-', 'description' => 'Prefix for invoice numbers'],
            
            // Rental Settings
            'rental.min_rental_hours' => ['value' => '1', 'description' => 'Minimum rental duration (hours)'],
            'rental.max_rental_days' => ['value' => '30', 'description' => 'Maximum rental duration (days)'],
            'rental.business_hours_start' => ['value' => '09:00', 'description' => 'Business hours start time'],
            'rental.business_hours_end' => ['value' => '21:00', 'description' => 'Business hours end time'],
            'rental.allow_extensions' => ['value' => 'true', 'description' => 'Allow rental extensions'],
            'rental.extension_max_days' => ['value' => '7', 'description' => 'Maximum extension period (days)'],
            'rental.cancellation_policy' => ['value' => '24 hours notice required for full refund, otherwise 50% penalty.', 'description' => 'Cancellation policy'],
            'rental.require_id' => ['value' => 'true', 'description' => 'Require ID for rental'],
            
            // Business Settings
            'business.company_name' => ['value' => 'PC Rental, Inc.', 'description' => 'Registered company name'],
            'business.tax_id' => ['value' => '123456789', 'description' => 'Tax identification number'],
            'business.address' => ['value' => '123 Main Street', 'description' => 'Company address'],
            'business.city' => ['value' => 'Any City', 'description' => 'Company city'],
            'business.postal_code' => ['value' => '12345', 'description' => 'Company postal code'],
            'business.country' => ['value' => 'Indonesia', 'description' => 'Company country'],
            'business.registration_number' => ['value' => 'REG-123456', 'description' => 'Business registration number'],
            'business.year_established' => ['value' => '2022', 'description' => 'Year business established'],
            
            // Email Settings
            'email.admin_notification' => ['value' => 'admin@pcrental.com', 'description' => 'Admin notification email'],
            'email.send_rental_confirmation' => ['value' => 'true', 'description' => 'Send rental confirmation emails'],
            'email.send_payment_receipt' => ['value' => 'true', 'description' => 'Send payment receipt emails'],
            'email.rental_reminder_hours' => ['value' => '24', 'description' => 'Hours before rental to send reminder'],
            'email.sender_name' => ['value' => 'PC Rental Service', 'description' => 'Email sender name'],
            'email.footer_text' => ['value' => 'Thank you for choosing PC Rental Service.', 'description' => 'Email footer text'],
            'email.signature' => ['value' => 'The PC Rental Team', 'description' => 'Email signature'],
            'email.support_email' => ['value' => 'support@pcrental.com', 'description' => 'Support email address'],
        ];

        // Insert or update settings
        foreach ($settings as $key => $data) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $data['value'],
                    'description' => $data['description'],
                ]
            );

            $this->command->info("Setting created/updated: {$key}");
        }

        try {
            if (class_exists('\Illuminate\Support\Facades\Cache') && app()->bound('cache')) {
                \Illuminate\Support\Facades\Cache::forget('application_settings');
            }
        } catch (\Exception $e) {
            // Ignore cache errors during seeding
        }

        $this->command->info('All settings have been seeded successfully!');
    }
}
