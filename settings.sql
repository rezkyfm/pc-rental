-- PC Rental Settings SQL Import
-- Run this file to import all default settings
-- You can import this via phpMyAdmin or mysql command line:
-- mysql -u username -p database_name < settings.sql

-- Create settings table if it doesn't exist
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- General Settings
INSERT INTO `settings` (`key`, `value`, `description`, `created_at`, `updated_at`) VALUES
('general.site_name', 'PC Rental Service', 'Application name', NOW(), NOW()),
('general.site_description', 'Professional PC rental services for gaming, work, and events', 'Application description', NOW(), NOW()),
('general.contact_email', 'contact@pcrental.com', 'Contact email address', NOW(), NOW()),
('general.contact_phone', '+1234567890', 'Contact phone number', NOW(), NOW()),
('general.address', '123 Tech Street, Digital City', 'Business address', NOW(), NOW()),
('general.business_hours', 'Mon-Fri: 9am-6pm, Sat: 10am-4pm, Sun: Closed', 'Business hours', NOW(), NOW()),
('general.date_format', 'Y-m-d', 'Date format (PHP date format)', NOW(), NOW()),
('general.time_format', 'H:i', 'Time format (PHP time format)', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
`value` = VALUES(`value`), 
`description` = VALUES(`description`), 
`updated_at` = NOW();

-- Payment Settings
INSERT INTO `settings` (`key`, `value`, `description`, `created_at`, `updated_at`) VALUES
('payment.currency', 'IDR', 'Currency code', NOW(), NOW()),
('payment.currency_symbol', 'Rp', 'Currency symbol', NOW(), NOW()),
('payment.deposit_percentage', '30', 'Default deposit percentage', NOW(), NOW()),
('payment.tax_percentage', '10', 'Tax percentage', NOW(), NOW()),
('payment.late_fee_hourly', '15', 'Late return fee per hour', NOW(), NOW()),
('payment.payment_methods', 'cash,credit_card,debit_card,transfer,e-wallet', 'Available payment methods (comma-separated)', NOW(), NOW()),
('payment.receipt_footer', 'Thank you for your business!', 'Footer text for receipts', NOW(), NOW()),
('payment.invoice_prefix', 'INV-', 'Prefix for invoice numbers', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
`value` = VALUES(`value`), 
`description` = VALUES(`description`), 
`updated_at` = NOW();

-- Rental Settings
INSERT INTO `settings` (`key`, `value`, `description`, `created_at`, `updated_at`) VALUES
('rental.min_rental_hours', '1', 'Minimum rental duration (hours)', NOW(), NOW()),
('rental.max_rental_days', '30', 'Maximum rental duration (days)', NOW(), NOW()),
('rental.business_hours_start', '09:00', 'Business hours start time', NOW(), NOW()),
('rental.business_hours_end', '21:00', 'Business hours end time', NOW(), NOW()),
('rental.allow_extensions', 'true', 'Allow rental extensions', NOW(), NOW()),
('rental.extension_max_days', '7', 'Maximum extension period (days)', NOW(), NOW()),
('rental.cancellation_policy', '24 hours notice required for full refund, otherwise 50% penalty.', 'Cancellation policy', NOW(), NOW()),
('rental.require_id', 'true', 'Require ID for rental', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
`value` = VALUES(`value`), 
`description` = VALUES(`description`), 
`updated_at` = NOW();

-- Business Settings
INSERT INTO `settings` (`key`, `value`, `description`, `created_at`, `updated_at`) VALUES
('business.company_name', 'PC Rental, Inc.', 'Registered company name', NOW(), NOW()),
('business.tax_id', '123456789', 'Tax identification number', NOW(), NOW()),
('business.address', '123 Main Street', 'Company address', NOW(), NOW()),
('business.city', 'Any City', 'Company city', NOW(), NOW()),
('business.postal_code', '12345', 'Company postal code', NOW(), NOW()),
('business.country', 'Indonesia', 'Company country', NOW(), NOW()),
('business.registration_number', 'REG-123456', 'Business registration number', NOW(), NOW()),
('business.year_established', '2022', 'Year business established', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
`value` = VALUES(`value`), 
`description` = VALUES(`description`), 
`updated_at` = NOW();

-- Email Settings
INSERT INTO `settings` (`key`, `value`, `description`, `created_at`, `updated_at`) VALUES
('email.admin_notification', 'admin@pcrental.com', 'Admin notification email', NOW(), NOW()),
('email.send_rental_confirmation', 'true', 'Send rental confirmation emails', NOW(), NOW()),
('email.send_payment_receipt', 'true', 'Send payment receipt emails', NOW(), NOW()),
('email.rental_reminder_hours', '24', 'Hours before rental to send reminder', NOW(), NOW()),
('email.sender_name', 'PC Rental Service', 'Email sender name', NOW(), NOW()),
('email.footer_text', 'Thank you for choosing PC Rental Service.', 'Email footer text', NOW(), NOW()),
('email.signature', 'The PC Rental Team', 'Email signature', NOW(), NOW()),
('email.support_email', 'support@pcrental.com', 'Support email address', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
`value` = VALUES(`value`), 
`description` = VALUES(`description`), 
`updated_at` = NOW();
