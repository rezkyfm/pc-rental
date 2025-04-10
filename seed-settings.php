<?php

/**
 * Simple script to directly add settings to the database
 * This script can be run directly: php seed-settings.php
 */

// Define settings
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

try {
    // Connect to the database
    $host = '127.0.0.1';
    $database = 'pc_rental';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully!\n";
    
    // Check if settings table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'settings'");
    if ($stmt->rowCount() == 0) {
        echo "Settings table does not exist. Creating...\n";
        
        // Create settings table
        $pdo->exec("CREATE TABLE settings (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `key` VARCHAR(255) UNIQUE NOT NULL,
            `value` TEXT NULL,
            description TEXT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        )");
        
        echo "Settings table created.\n";
    }
    
    // Add settings
    $stmt = $pdo->prepare("INSERT INTO settings (`key`, `value`, `description`, `created_at`, `updated_at`) 
                          VALUES (:key, :value, :description, NOW(), NOW())
                          ON DUPLICATE KEY UPDATE 
                          `value` = :value, `description` = :description, `updated_at` = NOW()");
    
    $count = 0;
    foreach ($settings as $key => $data) {
        $stmt->execute([
            ':key' => $key,
            ':value' => $data['value'],
            ':description' => $data['description']
        ]);
        $count++;
    }
    
    echo "Added $count settings to the database.\n";
    echo "Settings initialization complete!\n";
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
