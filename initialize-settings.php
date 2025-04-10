<?php

/**
 * This script initializes all settings without requiring artisan command
 * Run it with: php initialize-settings.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Run the seeder
$seeder = new Database\Seeders\SettingsSeeder();
$seeder->setContainer($app)->setCommand(
    $app->make(Illuminate\Console\Command::class)
);
$seeder->run();

echo "Settings have been initialized successfully!\n";
