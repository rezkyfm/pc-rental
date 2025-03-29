<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'company_name',
                'value' => 'PC Rental Central',
                'description' => 'The name of the company',
            ],
            [
                'key' => 'company_address',
                'value' => 'Jl. Kebon Jeruk No. 123, Jakarta Barat',
                'description' => 'Physical address of the company',
            ],
            [
                'key' => 'company_phone',
                'value' => '+62 21 12345678',
                'description' => 'Contact phone number',
            ],
            [
                'key' => 'company_email',
                'value' => 'info@pcrentalcentral.com',
                'description' => 'Contact email address',
            ],
            [
                'key' => 'company_website',
                'value' => 'www.pcrentalcentral.com',
                'description' => 'Company website',
            ],
            [
                'key' => 'tax_percentage',
                'value' => '11',
                'description' => 'Tax percentage to apply on rentals',
            ],
            [
                'key' => 'deposit_percentage',
                'value' => '30',
                'description' => 'Deposit percentage of total rental price',
            ],
            [
                'key' => 'rental_agreement_text',
                'value' => 'Standard rental agreement text that outlines terms and conditions...',
                'description' => 'Default text for rental agreements',
            ],
            [
                'key' => 'late_return_fee_hourly',
                'value' => '10',
                'description' => 'Additional fee per hour for late returns',
            ],
            [
                'key' => 'business_hours',
                'value' => '{"monday":"09:00-21:00","tuesday":"09:00-21:00","wednesday":"09:00-21:00","thursday":"09:00-21:00","friday":"09:00-21:00","saturday":"10:00-22:00","sunday":"10:00-20:00"}',
                'description' => 'Business hours for the rental shop',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}