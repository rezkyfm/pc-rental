# PC Rental Settings Implementation

This document provides information about the Settings system implemented in this PC Rental application.

## Overview

The settings system provides a flexible way to manage application-wide configurations through the database. 
It includes comprehensive settings for these categories:

- **General Settings**: Basic application information
- **Payment Settings**: Currency, rates, and payment options
- **Rental Settings**: Rental rules and time constraints
- **Business Settings**: Company information 
- **Email Settings**: Email notification settings

## Initializing Settings

You have several options to initialize the default settings:

### Option 1: Using Artisan Command
```bash
php artisan app:initialize-settings
```

### Option 2: Using the Seeder
```bash
php artisan db:seed --class=SettingsSeeder
```

### Option 3: Using the PHP Script
```bash
php seed-settings.php
```

### Option 4: Using SQL Import
If you prefer direct database import:
```bash
mysql -u username -p database_name < settings.sql
```
Or import the `settings.sql` file via phpMyAdmin.

## Using Settings in Your Code

### Using the Facade

```php
use App\Facades\Settings;

// Get a single setting
$siteName = Settings::get('general.site_name', 'Default Site Name');

// Check if a feature is enabled
if (Settings::isEnabled('rental.allow_extensions')) {
    // Handle extension logic
}

// Get formatted value (with currency symbol, percentage sign, etc.)
$depositAmount = Settings::getFormatted('payment.deposit_percentage', 30, 'percentage');

// Get all settings in a category
$allPaymentSettings = Settings::getCategory('payment');
```

### Using the Helper Directly

```php
use App\Helpers\SettingsHelper;

$siteName = SettingsHelper::get('general.site_name', 'Default Site Name');
```

### In Blade Templates

```html
<h1>{{ Settings::get('general.site_name', 'PC Rental') }}</h1>

<p>Contact us at: {{ Settings::get('general.contact_email') }}</p>

<p>Deposit required: {{ Settings::getFormatted('payment.deposit_percentage', 30, 'percentage') }}</p>
```

## Managing Settings

Settings can be managed through the admin interface at `/admin/settings`.

## Available Setting Keys

### General Settings
- `general.site_name`: Application name
- `general.site_description`: Application description
- `general.contact_email`: Contact email address
- `general.contact_phone`: Contact phone number
- `general.address`: Business address
- `general.business_hours`: Business hours
- `general.date_format`: Date format (PHP date format)
- `general.time_format`: Time format (PHP time format)

### Payment Settings
- `payment.currency`: Currency code
- `payment.currency_symbol`: Currency symbol
- `payment.deposit_percentage`: Default deposit percentage
- `payment.tax_percentage`: Tax percentage
- `payment.late_fee_hourly`: Late return fee per hour
- `payment.payment_methods`: Available payment methods (comma-separated)
- `payment.receipt_footer`: Footer text for receipts
- `payment.invoice_prefix`: Prefix for invoice numbers

### Rental Settings
- `rental.min_rental_hours`: Minimum rental duration (hours)
- `rental.max_rental_days`: Maximum rental duration (days)
- `rental.business_hours_start`: Business hours start time
- `rental.business_hours_end`: Business hours end time
- `rental.allow_extensions`: Allow rental extensions
- `rental.extension_max_days`: Maximum extension period (days)
- `rental.cancellation_policy`: Cancellation policy
- `rental.require_id`: Require ID for rental

### Business Settings
- `business.company_name`: Registered company name
- `business.tax_id`: Tax identification number
- `business.address`: Company address
- `business.city`: Company city
- `business.postal_code`: Company postal code
- `business.country`: Company country
- `business.registration_number`: Business registration number
- `business.year_established`: Year business established

### Email Settings
- `email.admin_notification`: Admin notification email
- `email.send_rental_confirmation`: Send rental confirmation emails
- `email.send_payment_receipt`: Send payment receipt emails
- `email.rental_reminder_hours`: Hours before rental to send reminder
- `email.sender_name`: Email sender name
- `email.footer_text`: Email footer text
- `email.signature`: Email signature
- `email.support_email`: Support email address
